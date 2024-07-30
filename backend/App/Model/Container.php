<?php 

namespace App\Model;

use App\Connection;

class Container{
    public static function getModel($model){
        $class = "\\App\\Model\\".ucfirst($model);
       
        $connection = Connection::getDb();
      
        return new $class($connection);
    }
}