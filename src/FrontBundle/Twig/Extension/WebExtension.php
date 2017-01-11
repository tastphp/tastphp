<?php
namespace TastPHP\FrontBundle\Twig\Extension;

class WebExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('debug', [$this, 'debug'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('debugScript', [$this, 'debugScript'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('debugCss', [$this, 'debugCss'], ['is_safe' => ['html']])
        ];
    }

    public function debug()
    {
        $debugBarRenderer = $this->container->singleton('debugbar')->getJavascriptRenderer();
        return $debugBarRenderer->render();
    }

    public function debugScript()
    {
        $debugBarRenderer = $this->container->singleton('debugbar')->getJavascriptRenderer();
        return $debugBarRenderer->dumpJsAssets();
    }

    public function debugCss()
    {
        $debugBarRenderer = $this->container->singleton('debugbar')->getJavascriptRenderer();
        return $debugBarRenderer->dumpCssAssets();
    }

    public function getName()
    {
        return 'web_twig_extension';
    }
}