<?php 
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use EARouter\EARouter;

//$eaRouter = new EARouter(RouterInterface $routerInterface);

$routeFileArray = array('routes/route-file1.php');

$eaRouter = new EARouter();
$routes = $eaRouter->getFromFilepathsArray($routeFileArray);
echo "<pre>";
//print_r($routes);

$eaRouterUriPathParams = $eaRouter->getUriPathParams($_SERVER['REQUEST_URI']);
//print_r($eaRouterUriPathParams);

$matchedRouteResponse = $eaRouter->matchRoute($routes, $_SERVER['REQUEST_URI'], [], $_SERVER["REQUEST_METHOD"], "500");

print_r($matchedRouteResponse);




