<?php

namespace App\Service;

use App\Model\Container;
use App\Service\Service;
use App\Validator\UserValidator;
use App\Repository\UserRepository;
use App\Exception\UserException;
use Exception;

class UserService extends Service
{
    /**
     * intermediary function containing the business rules for the actual insertion of the user into the database
     * @param array newUser parameter to be analyzed
     * @param array errors collection of errors that are detected during the attribute verification process
     * @throws \Exception If there is any unexpected failure in the register process
     */
    public static function create(array $newUser, array &$errors): void
    {
        try {
            $user = Container::getModel("User");

            echo '<pre>';
            print_r($newUser);
            echo '</pre>';
            UserValidator::validate($user, $newUser, $errors);

            if (count($errors) == 0) {
                $encryption_key = UserService::generate_encryption_key();
                $user->__set("name_user", $newUser["name"]);
                $user->__set("username", $newUser["username"]);
                $user->__set("email", $newUser["email"]);
                $user->__set(
                    "password",
                    password_hash($newUser["password"], PASSWORD_BCRYPT)
                );
                $user->__set("encryption_key", $encryption_key);
                UserRepository::insert($user);
            }
        } catch (UserException $exception) {
            $errors["exception"] = $exception->getMessage();
        }
    }


    /**
     * 
     */
    public static function update($params, &$errors)
    {
    }

    public static function updatePhoto(){

    }
    public static function delete($params, &$errors)
    {
    }
    public static function get($param)
    {
    }

    /**
     * Performs authentication
     * @param array $userLogin data required for authentication
     * @param array $errors an array to store exceptions that occurred during the authentication process
     * @throws \Exception If there is any unexpected failure in the authentication process
     */
    public static function authenticateUser($userLogin, &$errors): void
    {
        try {
            $userLogged = Container::getModel("User");
            $user = UserValidator::validateLogin(
                $userLogged,
                $userLogin,
                $errors
            );

            if (count($errors) == 0) {
                UserService::storageInSession(
                    $user->__get("username"),
                    $user->__get("encryption_key"),
                    $user->__get("image_url")
                );
            }
        } catch (\Exception $exception) {
            $errors["exception"] = $exception->getMessage();
        }
    }


    /**
     * 
     */
    public static function storageInSession(
        string $username,
        string $encryption_key,
        string $image_url
    ) {
    
        if ($encryption_key) {
            $_SESSION["username"] = userService::encrypt(
                $username,
                $encryption_key
            );
            $_SESSION["encryption_key"] = $encryption_key;
            $_SESSION["loggedin"] = true;
            $_SESSION["img_url"] = $image_url;
        } else {
            // throw new UserException("Session key storage failed", 3, null, 500);
        }
    }
    /** * generate a pseudo-random key for encryption * @return string — The encoded data, as a string. */
    public static function generate_encryption_key()
    {
        return base64_encode(openssl_random_pseudo_bytes(32));
    }

   /**
     * function that encrypts a given message using the user's own key
     * @param string data that it will be encrypted
     * @param string encryption_key user's own key for encrypts
     * @return string — The encoded data, as a string
     */
    public static function encrypt($data, $encryption_key)
    {
        $encryption_key = base64_decode($encryption_key);
        $iv = openssl_random_pseudo_bytes(
            openssl_cipher_iv_length("aes-256-cbc")
        );
        $encrypted = openssl_encrypt(
            $data,
            "aes-256-cbc",
            $encryption_key,
            0,
            $iv
        );
        return base64_encode($encrypted . "::" . $iv);
    }

    /**
     * @return string|false — The decrypted string on success or false on failure.
     */
    public static function decrypt($data, $encryption_key)
    {
        $encryption_key = base64_decode($encryption_key);
        list($encrypted_data, $iv) = explode("::", base64_decode($data), 2);
        return openssl_decrypt(
            $encrypted_data,
            "aes-256-cbc",
            $encryption_key,
            0,
            $iv
        );
    }
}
