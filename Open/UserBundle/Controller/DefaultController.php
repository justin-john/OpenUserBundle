<?php

namespace Sewolabs\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HWI\OAuthBundle\Controller\ConnectController;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SewolabsUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
