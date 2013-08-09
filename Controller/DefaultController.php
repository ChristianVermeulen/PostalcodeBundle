<?php

namespace ChristianVermeulen\PostalcodeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ChristianVermeulenPostalcodeBundle:Default:index.html.twig', array('name' => $name));
    }
}
