<?php

namespace App\Providers;

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Symfony\Component\Console\Application;
use App\Console\Commands\AddEndpointCommand;

class AppServiceProvider
{

    /**
     * @return App
     */
    public static function setup()
    {
        $app = new App([
            'settings' => [
                'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

                'app' => __DIR__.'/../../config/app.php',

                'views' => [
                    'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../../storage/views'
                ]
            ],
        ]);

        return $app;
    }

    public static function container()
    {
        $container = self::setup()->getContainer();

        $container['console'] = self::console();
        $container['view'] = self::view($container);

        return $container;
    }

    /**
     * @return Application
     */
    public static function console()
    {
        $application = new Application();
        $application->add(new AddEndpointCommand());
        return $application;
    }

    /**
     * @param $container
     * @return Twig
     */
    public static function view($container)
    {
        $view = new Twig(__DIR__ . '/../../resources/views', [
            'cache' => $container->settings['views']['cache']
        ]);
        $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new TwigExtension($container['router'], $basePath));
        return $view;
    }


    public static function database()
    {
        //
    }
}