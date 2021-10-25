<?php
namespace App;

use DI\ContainerBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use DI\Container;

class DIContainer
{
    private Container $container;

    public function __construct()
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->addDefinitions([
            Environment::class => function() {
                $loader = new FilesystemLoader('app/Views');
                $twig = new Environment($loader);
                $twig->addGlobal('session', $_SESSION);
                return $twig;
            }]);
        $this->container = $containerBuilder->build();
    }

    public function getContainer():Container
    {
        return $this->container;
    }
}