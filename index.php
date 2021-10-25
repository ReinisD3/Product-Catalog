<?php

use App\DIContainer;
use App\Router;

require_once 'vendor/autoload.php';

session_start();

$container = (new DIContainer())->getContainer();

$router = new Router($container);

$router->start();

unset($_SESSION['errors']);
unset($_SESSION['data']);