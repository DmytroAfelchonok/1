<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions

// 1. namespace
namespace guestbook;

session_start();

// 2. use

// 3. require_once
require_once 'vendor/autoload.php';

require_once 'Controllers/HomeController.php';
require_once 'Controllers/RegisterController.php';
require_once 'Controllers/LoginController.php';
require_once 'Controllers/AdminController.php';
require_once 'Controllers/LogoutController.php';
require_once 'Controllers/GuestbookController.php';


// TODO 2: ROUTING

switch ($_SERVER['REQUEST_URI']) {
    case '/':
        $controllerClassName = 'HomeController';
        break;
    case '/register':
        $controllerClassName = 'RegisterController';
        break;
    case '/login':
        $controllerClassName = 'LoginController';
        break;
    case '/logout':
        $controllerClassName = 'LogoutController';
        break;
    case '/admin':
        $controllerClassName = 'AdminController';
        break;
    case '/guestbook':
        $controllerClassName = 'GuestbookController';
        break;
    default:
        echo 'Path not found.';
        die;
}

$arr = [
    '1' => 1,
    '2' => 2,
    '3' => 3,
];

var_dump($arr);

dump($arr);

$controllerClassName = "guestbook\Controllers\\$controllerClassName";

$controller = new $controllerClassName();
$controller->execute();