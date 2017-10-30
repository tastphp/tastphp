# Release Notes

## v1.3.7 （2017.10.30）
* change tastphp framework composer version to [2.0.0](https://github.com/tastphp/framework/releases/tag/v2.0.0)
* modify  registerService function:
```
    protected function registerService($name)
    {
        return $this->container['serviceKernel']->registerService($name);
    }
```

## v1.3.6 （2017.10.20）
* modify composer.lock

## v1.3.5 （2017.10.20）
### Added
* CacheConfigCommand && CacheRouteCommand
* default register twig

## v1.3.4 （2017.10.12）
* change Tastphp core Framework version to ~1.7

## v1.3.3 （2017.10.12）
* remove console GenerateAdminController & GenerateAdminRoutes Command

## v1.3.2 （2017.7.31）
* Change console.php to console
* compatible no config file

## v1.3.1 （2017.7.13）
*  modify app example version
## v1.3.0 （2017.7.13）
### Added 
* support psr-11 and psr-7
* Fixed some issues

## v1.2.0
### Fixed
* Fixed some bugs