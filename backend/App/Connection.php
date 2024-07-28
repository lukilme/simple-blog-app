<?php 
namespace App;

use Dotenv\Dotenv;

class Connection {
    public static function getDb(){
        try{
            // Carregue as variáveis de ambiente

            $host = $_ENV['DB_HOST'];
            $db_name = $_ENV['DB_DATABASE'];
            $user = $_ENV['DB_USERNAME'];
            $pass = $_ENV['DB_PASSWORD'];
            $port = $_ENV['DB_PORT'];
            
            $connection = new \PDO(
                "mysql:host=$host;port=$port;dbname=$db_name",
                $user,
                $pass
            );
            
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo '<pre>';
            print_r($connection);
            echo '</pre>';
            return $connection;
        } catch(\Exception $e){
            echo "Error to connect on database: " . $e->getMessage();
        }
    }
}
