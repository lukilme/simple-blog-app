<?php 

namespace App\Service;

abstract class Service{
    abstract protected static function create(array $params, array &$errors);
    abstract protected static function update(array $params, array &$errors);
    abstract protected static function delete(array $params, array &$errors);
    abstract protected static function get($param);
}