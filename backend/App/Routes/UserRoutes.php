<?php

namespace App\Routes;

class UserRoutes {
    public static function init(&$routes) {
        $routes['register_user'] = array('route' => '/register_user', 'controller' => 'userController', 'action' => 'register_user');
        $routes['authenticate'] = array('route' => '/authenticate', 'controller' => 'userController', 'action' => 'authenticate');
        $routes['delete_user'] = array('route' => '/delete_user', 'controller' => 'userController', 'action' => 'delete');
        $routes['get_user'] = array('route' => '/get_user', 'controller' => 'userController', 'action' => 'getUser');
        $routes['upload_image'] = array('route' => '/upload_image', 'controller' => 'userController', 'action' => 'uploadImage');
        $routes['update_user'] = array('route' => '/update_user', 'controller' => 'userController', 'action' => 'update');
        $routes['logout'] = array('route' => '/logout', 'controller' => 'userController', 'action' => 'logout');
    }
}
