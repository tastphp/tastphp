<?php

namespace TastPHP\FrontBundle\Controller;

use TastPHP\Common\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('home/index.html.twig');
    }
}