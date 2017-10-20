<?php

namespace TastPHP\App;

use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Event\FilterControllerEvent;
use TastPHP\Framework\Kernel;
use TastPHP\FrontBundle\Listener\MiddlewareListener;
use TastPHP\FrontBundle\Listener\RequestListener;

class AppKernel extends Kernel
{
    public function __construct(array $values = [])
    {
        // $this->replaceListener(AppEvent::REQUEST,RequestListener::class.'@onRequestAction');
        // $this->replaceListener(AppEvent::MIDDLEWARE,MiddlewareListener::class.'@onMiddlewareAction');
        $this->registerTwigService();
        parent::__construct($values);
    }

}