<?php

use App\Router;

require_once 'vendor/autoload.php';

session_start();

$router = new Router();
$router->start();

unset($_SESSION['errors']);