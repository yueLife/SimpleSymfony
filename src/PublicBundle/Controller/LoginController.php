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

// use HuoBundle\Entity\Users;

class LoginController extends Controller
{
    /**
     * @Route("/public/login", name="loginRoute")
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

        return $this->render(
            'PublicBundle:Main:login.html.twig',
            array('error' => $error)
        );
    }

    /**
     * @Route("/public/login_check", name="loginCheck")
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
            array('error' => '')
        );
    }

    /**
     * 登录成功后，所要的操作
     *
     * @Route("/pus/loginSet", name="loginSet")
     */
    public function loginSetAction()
    {
        return $this->render(
            'PublicBundle:Main:loginTest.html.twig',
            array('error' => '')
        );
        // $_userInfo = $this->get('security.context')->getToken()->getUser();
        // $em = $this->getDoctrine()->getEntityManager();
        // $userInfo = $em->getRepository('HuoBundle\Entity\Users')->findOneById($_userInfo->getId());

        // // 存储信息到session
        // $session = $this->getRequest()->getSession();
        // $session->set('time', $userInfo->getLastLogin());
        // $session->set('personSet', $userInfo->getPersonSet());

        // // 更新用户信息
        // $userInfo->setLastLogin(date('Y/m/d H:i:s', time()));
        // $em->flush();

        // if ($_userInfo->getRole() == 'ROLE_SUPER_ADMIN') {
        //     return $this->redirect($this->generateUrl('adminIndex'));
        // }else{
        //     return $this->redirect($this->generateUrl('home'));
        // }
    }
}
