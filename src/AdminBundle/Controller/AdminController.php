<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/index", name="adminIndex")
     * @Template("AdminBundle:Main:index.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }
}
