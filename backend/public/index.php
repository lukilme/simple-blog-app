<?php 

require_once "../vendor/autoload.php";
require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();



$route = new \App\Route();