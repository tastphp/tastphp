<?php

namespace TastPHP\Service\Common;
use TastPHP\Service\ServiceKernel;

class BaseService
{
    public function registerDao($name)
    {
        return $this->getKernel()->registerDao($name);
    }

    public function registerService($name)
    {
        return $this->getKernel()->registerService($name);
    }

    public function getIp()
    {
        $container = $this->getKernel()->getContainer();
        return json_encode($container['Request']->getClientIps());
    }

    protected function getKernel()
    {
        return ServiceKernel::instance();
    }
}