<?php

namespace App\Model;

class User extends Model
{
    private $id_user;
    private $username;
    private $email;
    private $password;
    private $name_user;
    private $registration_date;
    private $encryption_key;
    private $image_url;
    private $is_adm;

    public function __get(string $attribute)
    {
        //$this->checkAttribute($attribute);
        return $this->$attribute;
    }

    public function __set(string $attribute, $value)
    {
        //$this->checkAttribute($attribute);
        $value = trim($value);
        $this->$attribute = $value;
    }
    private function checkAttribute(string $attribute)
    {
        if (property_exists(User::class, $attribute)) {
            //throw new UserException("adsdsa",43, null, 34);
            echo "The attribute '$attribute' exists in the User class.";
        } else {
            echo "The attribute '$attribute' does not exist in the User class.";
        }
    }
}
