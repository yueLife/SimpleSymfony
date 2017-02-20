<?php

namespace PublicBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/public")
 */
class IndexController extends Controller
{
    /**
     * @Route("/hello", name="publicIndex")
     * @Template("PublicBundle:Main:index.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }

    /**
     * @Route("/verify", name="verifyUser")
     * @Template("PublicBundle:Main/Emails:verifyUser.html.twig")
     */
    public function verifyUserAction()
    {
        $token = $email = $this->getRequest()->get('token');
        $em = $this->getDoctrine()->getEntityManager();
        $sendEmails = $em->getRepository('PublicBundle\Entity\SendEmails');
        $emailInfo = $sendEmails->findOneByToken($token);
        $time = time();
        $lifeTime = $emailInfo->getCreateTime() + $emailInfo->getLifeTime();
        if ($time >= $lifeTime) {
            $token = '链接已失效';
        }else{
            $token = '密码重置成功！';
        }
        return array('token' => $token);
    }
}
