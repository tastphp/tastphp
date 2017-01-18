<?php
class Apptest extends PHPUnit_Framework_TestCase
{
    public function testApp()
    {
        $app = new \TastPHP\App\AppKernel();
        $result = $app['router']->match('/','GET');
        $this->assertEquals($result,'it works!');
    }
}