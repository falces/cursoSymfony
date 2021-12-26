<?php

namespace Container6IExBUG;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getCategoryControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\Api\CategoryController' shared autowired service.
     *
     * @return \App\Controller\Api\CategoryController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/framework-bundle/Controller/AbstractController.php';
        include_once \dirname(__DIR__, 4).'/vendor/friendsofsymfony/rest-bundle/Controller/AbstractFOSRestController.php';
        include_once \dirname(__DIR__, 4).'/vendor/friendsofsymfony/rest-bundle/Controller/ControllerTrait.php';
        include_once \dirname(__DIR__, 4).'/src/Controller/Api/CategoryController.php';

        $container->services['App\\Controller\\Api\\CategoryController'] = $instance = new \App\Controller\Api\CategoryController();

        $instance->setContainer(($container->privates['.service_locator.eIcC2Rx'] ?? $container->load('get_ServiceLocator_EIcC2RxService'))->withContext('App\\Controller\\Api\\CategoryController', $container));

        return $instance;
    }
}