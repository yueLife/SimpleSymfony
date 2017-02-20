<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class IndexController extends Controller
{
    /**
     * @Route("/hello", name="adminIndex")
     * @Template("AdminBundle:Main:index.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }
}
