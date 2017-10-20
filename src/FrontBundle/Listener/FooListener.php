<?php

namespace TastPHP\FrontBundle\Listener;

use TastPHP\Framework\Event\HttpEvent;

class FooListener
{
    public function onFooAction(HttpEvent $event)
    {
//        dump("onFooAction!");
    }
}