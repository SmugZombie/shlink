<?php
use Acelaya\UrlShortener\Factory\CacheFactory;
use Acelaya\UrlShortener\Factory\EntityManagerFactory;
use Acelaya\UrlShortener\Service\UrlShortener;
use Acelaya\ZsmAnnotatedServices\Factory\V3\AnnotatedFactory;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;
use Zend\Expressive\Application;
use Zend\Expressive\Container;
use Zend\Expressive\Helper;
use Zend\Expressive\Router;
use Zend\Expressive\Template;
use Zend\Expressive\Twig;
use Zend\ServiceManager\Factory\InvokableFactory;

return [

    'services' => [
        'factories' => [
            Application::class => Container\ApplicationFactory::class,

            // Routes
            Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
            Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
            Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
            Helper\ServerUrlHelper::class => InvokableFactory::class,
            Router\RouterInterface::class => InvokableFactory::class,
            Router\AuraRouter::class => InvokableFactory::class,

            // View
            'Zend\Expressive\FinalHandler' => Container\TemplatedErrorHandlerFactory::class,
            Template\TemplateRendererInterface::class => Twig\TwigRendererFactory::class,

            // Services
            EntityManager::class => EntityManagerFactory::class,
            GuzzleHttp\Client::class => InvokableFactory::class,
            UrlShortener::class => AnnotatedFactory::class,
            Cache::class => CacheFactory::class,
        ],
        'aliases' => [
            'em' => EntityManager::class,
            'httpClient' => GuzzleHttp\Client::class,
        ]
    ],

];
