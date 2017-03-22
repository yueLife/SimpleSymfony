<?php

namespace UnusedWordsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/words")
 */
class WordsController extends Controller
{
    /**
     * @Route("/index", name="wordsIndex")
     * @Template("UnusedWordsBundle:Main:index.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }
}
