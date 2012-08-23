<?php

namespace Open\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HWI\OAuthBundle\Controller\ConnectController;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OpenUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
