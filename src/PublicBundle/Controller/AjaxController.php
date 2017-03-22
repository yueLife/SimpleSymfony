<?php

namespace PublicBundle\Controller;

use PublicBundle\Entity\SendEmails;
use PublicBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/public/ajax")
 */
class AjaxController extends Controller
{
    /**
     * @Route("/email/forgetPassword", name="forgetPasswordAjax")
     */
    public function forgetPasswordAjax(Request $request)
    {
        $email = $request->get('email');

        $em = $this->getDoctrine()->getManager();
        $usersEm = $em->getRepository('PublicBundle:Users');
        $userInfo = $usersEm->findOneByEmail($email);

        if (!$userInfo) {
            return new JsonResponse(array(
                'state' => false,
                'msg' => '邮箱验证失败或未注册，请检查邮箱地址！'
            ));
        } else {
            $username = $userInfo->getUsername();
            $salt = $this->get('toolsService')->getRandCharService(4);
            $token = hash('sha256', $username.$salt.uniqid());
            $validType = base64_encode('forgetPassword');

            $newSendEmail = new SendEmails();
            $newSendEmail->setUid($userInfo->getId())->setUsername($username)->setEmail($email)->setToken($token)->setValidType($validType)->setSalt($salt);
            $em->persist($newSendEmail);
            $em->flush();

            $emailData['subject'] = '邮箱验证重置密码';
            $emailData['emailAdd'] = $email;
            $emailData['view'] = 'PublicBundle:Main/Emails:forgetPassword.html.twig';
            $emailData['arr'] = array(
                'username' => $username,
                'token' => $token,
                'validType' => $validType
            );

            $this->get('toolsService')->sendEmailService($emailData);
            return new JsonResponse(array('state' => true, 'msg' => '邮件发送成功！请查收！'));
        }
    }

    /**
     * @Route("/email/resetPassword", name="resetPasswordAjax")
     */
    public function resetPasswordAjax(Request $request)
    {
        $token = $request->get('token');
        $password = $request->get('password');
        $em = $this->getDoctrine()->getManager();
        $sendEmailEm = $em->getRepository('PublicBundle:SendEmails');
        $usersEm = $em->getRepository('PublicBundle:Users');

        $uid = $sendEmailEm->findOneByToken($token)->getUid();
        $usersEm->findOneById($uid)->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $em->flush();

        return new JsonResponse(array('state' => true, 'msg' => '修改成功！请重新登录！'));
    }

    /**
     * @Route("/email/register", name="registerAjax")
     */
    public function registerAjax(Request $request)
    {
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        $rePassword = $request->get('rePassword');

        $em = $this->getDoctrine()->getManager();
        $usersEm = $em->getRepository('PublicBundle:Users');

        if (!preg_match('/^[\w-]+@[\w-]+(\.[\w-]+)+$/', $email)) {
            return new JsonResponse(array('state' => false, 'msg' => '邮箱格式不正确，请重新输入！'));
        } elseif ($password != $rePassword) {
            return new JsonResponse(array('state' => false, 'msg' => '两次密码不一致，请重新输入！'));
        } elseif ($usersEm->findOneByEmail($email)) {
            return new JsonResponse(array('state' => false, 'msg' => '该邮箱已注册，请检查邮箱地址！'));
        } elseif ($usersEm->findOneByUsername($username)) {
            return new JsonResponse(array('state' => false, 'msg' => '该用户名已注册，请重新输入！'));
        } else {
            $salt = $this->get('toolsService')->getRandCharService(4);
            $token = hash('sha256', $username.$salt.uniqid());
            $validType = base64_encode('register');

            $newUser = new Users($request->getClientIp());
            $newUser->setUsername($username)->setEmail($email)->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $em->persist($newUser);
            $em->flush();

            $sendEmailInfo = new SendEmails();
            $sendEmailInfo->setUid($newUser->getId())->setUsername($username)->setEmail($email)->setToken($token)->setValidType($validType)->setSalt($salt);
            $em->persist($sendEmailInfo);
            $em->flush();

            $emailData['subject'] = '邮箱注册验证';
            $emailData['emailAdd'] = $email;
            $emailData['view'] = 'PublicBundle:Main/Emails:forgetPassword.html.twig';
            $emailData['arr'] = array(
                'username' => $username,
                'token' => $token,
                'validType' => $validType
            );

            $this->get('toolsService')->sendEmailService($emailData);
            return new JsonResponse(array('state' => true, 'msg' => '邮件发送成功！请查收！'));
        }
    }
}
