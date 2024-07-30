<?php

namespace App;
use App\Routes\Init;
use App\Routes\UserRoutes;
use App\Routes\AppRoutes;

class Route extends Init{

    protected function initRoutes(){
        $router = array();
        UserRoutes::init($router);
        AppRoutes::init($router);
        $this->setRoutes($router);
   }       
}

