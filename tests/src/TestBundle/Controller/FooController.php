<?php

namespace TastPHP\Tests\TestBundle\Controller;

class FooController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function indexAction()
    {
        return 'it works!';
    }
}