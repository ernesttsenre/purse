<?php

namespace BalanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BalanceBundle:Default:index.html.twig');
    }
}
