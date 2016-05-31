<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin(
    'Pcl',
    ['path' => '/pcl'],
    function (RouteBuilder $routes) {
        $routes->fallbacks('DashedRoute');
    }
);
