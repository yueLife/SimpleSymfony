<?php

namespace GoodsPickBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/goods")
 */
class GoodsController extends Controller
{
    /**
     * @Route("/index", name="goodsIndex")
     * @Template("GoodsPickBundle:Main:index.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }
}
