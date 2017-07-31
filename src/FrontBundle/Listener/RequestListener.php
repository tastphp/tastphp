<?php

namespace TastPHP\FrontBundle\Listener;

use TastPHP\Framework\Event\HttpEvent;

class RequestListener
{
    public function onRequestAction(HttpEvent $event)
    {
        dump("onRequestAction!");
    }
}
