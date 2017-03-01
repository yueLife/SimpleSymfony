<?php

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/public")
 */
class PublicController extends Controller
{
    /**
     * @Route("/index", name="publicIndex")
     * @Template("PublicBundle:Main:index.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }

    /**
     * @Route("/verifyToken", name="verifyToken")
     * @Template("PublicBundle:Main/Emails:verifyToken.html.twig")
     */
    public function verifyTokenAction(Request $request)
    {
        $data['title'] = '邮箱验证-';
        $data['token'] = $request->get('token');

        $em = $this->getDoctrine()->getManager();
        $sendEmailsEm = $em->getRepository('PublicBundle\Entity\SendEmails');

        $emailInfo = $sendEmailsEm->findOneByToken($data['token']);
        $lifeTime = $emailInfo->getCreateTime() + $emailInfo->getLifeTime();
        if (time() >= $lifeTime) {
            $data['msg'] = '链接不存在或者已失效，请重新申请！';
            return $data;
        }
        $data['type'] = base64_decode($request->get('type'));

        switch ($data['type']) {
            case 'register':
                $uid = $sendEmailsEm->findOneByToken($data['token'])->getUid();
                $usersEm = $em->getRepository('PublicBundle\Entity\Users');
                $usersEm->findOneById($uid)->setValidEmail(true);
                $em->flush();
                $data['msg'] = '邮箱验证成功，请重新登录！'; break;
            case 'forgetPassword':
                $data['msg'] = '邮箱验证成功，请重置您的密码！';break;
            default:
                $data['msg'] = '链接不存在或者已失效，请重新申请！';break;
        }
        return $data;
    }
}
