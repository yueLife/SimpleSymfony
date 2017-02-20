<?php

namespace PublicBundle\Controller;

use PublicBundle\Entity\SendEmails;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/public/ajax/email")
 */
class EmailController extends Controller
{
    /**
     * @Route("/forgetPassword", name="forgetPassword")
     */
    public function forgetPasswordAction()
    {
        $email = $this->getRequest()->request->get('email');

        $em = $this->getDoctrine()->getEntityManager();
        $usersEm = $em->getRepository('PublicBundle\Entity\Users');
        $userInfo = $usersEm->findOneByEmail($email);

        if (!$userInfo) {
            return new JsonResponse(array(
                'state' => false,
                'msg' => '邮箱验证失败请检查邮箱地址！'
            ));
        } else {
            $username = $userInfo->getUsername();
            $salt = $this->get('toolsService')->getRandCharService(4);
            $token = hash('sha512', $username.$salt.'joywell');

            $sendEmailInfo = new SendEmails();
            $sendEmail = $sendEmailInfo->setUid($userInfo->getId())->setUsername($username)->setEmail($email)->setToken($token)->setSalt($salt)->setCreateTime(time());
            $em->persist($sendEmailInfo);
            $em->flush();

            $emailData['subject'] = '邮箱验证重置密码';
            $emailData['emailAdd'] = $email;
            $emailData['view'] = 'PublicBundle:Main/Emails:forgetPassword.html.twig';
            $emailData['arr'] = array(
                'username' => $username,
                'token' => $token,
            );

            $this->get('toolsService')->sendEmailService($emailData);
            return new JsonResponse(array(
                'state' => true,
                'msg' => '邮件发送成功！请查收！'
            ));
        }
    }
}
