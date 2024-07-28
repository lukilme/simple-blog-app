<?php 

namespace App\Model;

use App\Connection;

class Container{
    public static function getModel($model){
        $class = "\\App\\Models\\".ucfirst($model);
       
        $connection = Connection::getDb();
      
        return new $class($connection);
    }
}