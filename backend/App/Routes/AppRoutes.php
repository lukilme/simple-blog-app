<?php
namespace App\Routes;

class AppRoutes {
    public static function init(&$routes) {
        $routes['index'] = array('route' => '/', 'controller' => 'appController', 'action' => 'index');
        $routes['home'] = array('route' => '/home', 'controller' => 'appController', 'action' => 'home');
        $routes['about'] = array('route' => '/about', 'controller' => 'appController', 'action' => 'about');
        $routes['analysis'] = array('route' => '/analysis', 'controller' => 'appController', 'action' => 'analysis');
        $routes['friends'] = array('route' => '/friends', 'controller' => 'appController', 'action' => 'friends');
        $routes['tasks'] = array('route' => '/tasks', 'controller' => 'appController', 'action' => 'tasks');
    }
}
