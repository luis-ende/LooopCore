<?php

namespace LooopCore\FrameworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LooopCoreFrameworkBundle:Default:index.html.twig', array('name' => $name));
    }
}
