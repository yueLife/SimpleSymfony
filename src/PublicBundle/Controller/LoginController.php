<?php

namespace PublicBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="loginRoute")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        $lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

        $errorMsg = '';
        if ($error) {
            switch ($error->getLine()) {
                case 42:
                    $errorMsg = '用户 '.$lastUsername . ' 已被禁用！请联系管理员。'; break;
                case 73:
                    $errorMsg = '用户名错误！请重新输入用户名。'; break;
                case 90:
                    $errorMsg = '密码错误！请重新输入密码。'; break;
                default:
                    $errorMsg = '未知错误！请联系管理员！'; break;
            }
        }

        return $this->render(
            'PublicBundle:Main:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'errorMsg' => $errorMsg
            )
        );
    }

    /**
     * @Route("/login_check", name="loginCheck")
     */
    public function loginCheckAction()
    {
        throw new \RuntimeException('您必须配置检查路径被防火墙使用表单登录在安全防火墙配置处理。');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // 删除用户信息
        $this->get('security.context')->setToken(null);
        // 删除 session中的信息
        $this->getRequest()->getSession()->clear();
        return $this->render(
            'PublicBundle:Main:login.html.twig',
            array(
                'last_username' => '',
                'errorMsg' => ''
            )
        );
    }

    /**
     * 登录成功后，所要的操作
     *
     * @Route("/loginSet", name="loginSet")
     */
    public function loginSetAction()
    {
        $_userInfo = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $userInfo = $em->getRepository('PublicBundle\Entity\Users')->findOneById($_userInfo->getId());

        // 存储信息到session
        $session = $this->getRequest()->getSession();
        $session->set('time', $userInfo->getLastLogin());

        // 更新用户信息
        $userInfo->setLastLogin(date('Y/m/d H:i:s', time()));
        $em->flush();

        switch ($_userInfo->getRole()) {
            case 'ROLE_ADMIN_USER':
                return $this->redirect($this->generateUrl('adminIndex')); break;
            case 'ROLE_WORDS_USER':
                return $this->redirect($this->generateUrl('wordsIndex')); break;
            case 'ROLE_GOODS_USER':
                return $this->redirect($this->generateUrl('goodsIndex')); break;
            default:
                return $this->redirect($this->generateUrl('publicIndex')); break;
        }
    }
}
