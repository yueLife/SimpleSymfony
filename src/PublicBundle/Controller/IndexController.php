<?php

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function verifyUserAction(Request $request)
    {
        $token = $email = $request->get('token');
        if (!$token) {
            $data['state'] = false;
            $data['msg'] = '链接不存在或者已失效，请重新检查！';
        }else {
            $em = $this->getDoctrine()->getManager();
            $sendEmails = $em->getRepository('PublicBundle\Entity\SendEmails');
            $emailInfo = $sendEmails->findOneByToken($token);
            $time = time();
            $lifeTime = $emailInfo->getCreateTime() + $emailInfo->getLifeTime();
            if ($time >= $lifeTime) {
                $data['state'] = false;
                $data['msg'] = '链接不存在或者已失效，请重新检查！';
            }else{
                $data['state'] = true;
            }
        }

        return array(
            'token' => $token,
            'data'=> $data
        );
    }
}
