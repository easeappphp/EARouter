<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use EARouter\EARouter;

//$eaRouter = new EARouter(RouterInterface $routerInterface);
$eaRouter = new EARouter();
$eaRouterUriPathParams = $eaRouter->getUriPathParams($_SERVER['REQUEST_URI']);

print_r($eaRouterUriPathParams);