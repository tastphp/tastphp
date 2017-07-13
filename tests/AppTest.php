<?php
use PHPUnit\Framework\TestCase;

class Apptest extends TestCase
{
    public function testApp()
    {
        $app = new \TastPHP\App\AppKernel();
        $result = $app['router']->match('/','GET');
        $this->assertEquals($result,'it works!');
    }
}