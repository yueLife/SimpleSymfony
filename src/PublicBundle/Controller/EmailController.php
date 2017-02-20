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
            return new JsonResponse(array(
                'state' => true,
                'msg' => '邮件发送成功！请查收！'
            ));
        }
//        $username = $userInfo->getUsername();
//        $salt = $this->get('toolsService')->getRandChar(4);
//        $token = hash('sha512', $username.$salt.'joywell');
//
//        $sendEmailInfo = new SendEmails();
//        $sendEmail = $sendEmailInfo->setUid($userInfo->getId())->setUsername($username)->setEmail($email)->setToken($token)->setSalt($salt)->setCreateTime(time());
//        $em->persist($sendEmailInfo);
//        $em->flush();
//
//        $message = \Swift_Message::newInstance()
//            ->setSubject('邮箱验证重置密码')
//            ->setFrom('wiki@joywell.com.cn')
//            ->setTo($email)
//            ->setBody(
//                $this->renderView(
//                    'PublicBundle:Main/Emails:forgetPassword.html.twig',
//                    array(
//                        'username' => $username,
//                        'token' => $token,
//                    )
//                ),
//                'text/html'
//            );
//        $this->get('mailer')->send($message);

        return new JsonResponse(array('state' => 'success'));
    }
}
