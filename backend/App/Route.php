<?php

namespace App;
use App\Routes\Init;
use App\Routes\UserRoutes;

class Route extends Init{

    protected function initRoutes(){
        $router = array();
        $this->setRoutes($router);
   }       
}

