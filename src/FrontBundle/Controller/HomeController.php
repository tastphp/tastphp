<?php

namespace TastPHP\FrontBundle\Controller;

use TastPHP\Common\Controller;
use TastPHP\Framework\Event\HttpEvent;

class HomeController extends Controller
{
    public function indexAction()
    {
         return $this->render('home/index.html.twig');
    }
}