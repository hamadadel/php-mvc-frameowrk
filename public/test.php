<?php
$uri = '/products/view/dell/123/';
preg_match_all('#products\/view\/([^\/]+)\/([^\/]+)#', $uri, $matches);
var_dump($matches);

//
//require_once '../vendor/autoload.php';
//use Framework\Routing\Router;
//$router = new Router;
//$routes = require_once __DIR__.'/../app/routes.php';
//$routes($router);
//
//function normalize(string $path): string
//{
//    $path = trim($path, '/');
//    $path = "/{$path}/";
//    // replace the / sign if found between 2 and unlimited times with only one /
//    return $path = preg_replace('/[\/]{2,}/', '/', $path);
//}
//
//$normalizedPaths = array_map(function($path) {
//    return normalize($path);
//}, $router->paths());
//
//var_dump($normalizedPaths);
//
//$parameterNames = [];
//
//$callback = function($match) use (&$parameterNames) {
//    $parameterNames[] = rtrim($match[1], '?');
//    var_dump($match, $parameterNames);
//    if (str_ends_with($match[1], '?'))
//        return '([^/]*)(?:/?)';
//    return '([^/]+)/';
//};
//
//$pattern  = preg_replace_callback('#{([^}]+)}/#', $callback, $normalizedPaths[8]);
//echo $pattern;
//$pattern  = preg_replace_callback('#{([^}]+)}/#', $callback, $normalizedPaths[7]);
//echo $pattern;