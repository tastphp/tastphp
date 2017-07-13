<?php

namespace TastPHP\Service;

class ServiceKernel
{
    private static $instance = null;

    protected $container = [];

    private $connection = null;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function registerService($name)
    {
        if (empty($this->container[$name])) {
            $service = $this->register('Service', $name);
            $this->container[$name] = $service;
        }
        return $this->container[$name];
    }

    public function registerDao($name)
    {
        if (empty($this->container[$name])) {
            $dao = $this->register('Dao', $name);
            $dao->setContainer($this->container);
            $dao->setConnection($this->getConnection());
            $this->container[$name] = $dao;
        }
        return $this->container[$name];
    }

    public function getContainer()
    {
        return $this->container;
    }

    private function register($type, $name)
    {
        list($module, $classType) = explode('.', $name);
        $extraDir = $type == 'Dao' ? '\\Dao' : '';
        $className = __NAMESPACE__ . "\\" . $module . "$extraDir\\Impl\\" . $classType . "Impl";
        return new $className;
    }
}