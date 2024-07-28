<?php

namespace App\Controller;

use App\Service\UserService;
use App\Exception\UserException;

class UserController extends Controller
{
    /**
     * renders login page
     */
    public function login()
    {
        if ($this->checkSession()) {
            header("Location: /home");
            exit();
        } else {
            $this->render("login");
        }
    }

    /**
     * if logged in, renders the user profile page
     */
    public function perfil()
    {
        if ($this->checkSession()) {
            $this->render("perfil");
        } else {
            header("Location: /login");
            exit();
        }
    }

    /**
     * renders register page
     */
    public function register()
    {
        if ($this->checkSession()) {
            header("Location: /home");
            exit();
        } else {
            $this->render("register");
        }
    }

    /**
     * Register the user, if the parameters are valid
     * @param REQUEST = {name, username, email, password, repeat_password}
     * @return RESPONSE 400 for non-compliance with business rules
     * @return RESPONSE 201 for successful register
     * @return RESPONSE 500 error that the server did not know how to handle
     */
    public function register_user()
    {
        try {
            if ($this->checkSession()) {
                header("Location: /home");
                exit();
            }

            $errors = [];
            $registerAttributes = [
                "name",
                "username",
                "email",
                "password",
                "repeat_password",
            ];

            $input = file_get_contents("php://input");
            $newUser = json_decode($input, true);
            // Check if there was a decoding error
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->sendResponse(400, [
                    "errors" => ["general" => "Invalid data."],
                ]);
            }

            foreach ($registerAttributes as $attribute) {
                $this->checkIfIsEmpty($newUser, $attribute, $errors);
            }

            UserService::create($newUser, $errors);

            if (!empty($errors)) {
                return $this->sendResponse(400, ["errors" => $errors]);
            }

            return $this->sendResponse(201, [
                "message" => "Registration successful",
            ]);
        } catch (UserException $e) {
            return $this->sendResponse(500, [
                "errors" => "Internal server error",
            ]);
        }
    }


    /**
    * function to send message to the customer
    * @param number statusCode number code that summarizes the final result of the operation
    * @param array|string data content with requested data or response with errors
    * @return RESPONSE response with message code and content
    */
    private function sendResponse($statusCode, $data)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }

    /**
     * utility function to check if fields are filled in
     */
    private function checkIfIsEmpty(
        array $newUser,
        string $attribute,
        array &$errors
    ) {
        if (!isset($newUser[$attribute]) || strlen($newUser[$attribute]) == 0) {
            $errors[$attribute] = ucfirst($attribute) . " is required.";
        }
    }

    public function update(){
        
    }

    /**
     * Perform authentication.
     * Check if the username and password correspond to any
     * user in the database and store in the session
     */
    public function authenticate()
    {
        try {
            if ($this->checkSession()) {
                header("Location: /home");
                exit();
            }
            $errors = [];
            $userLogin = [];
            $loginAttributes = ["key", "password"];
            foreach ($loginAttributes as $attribute) {
                $this->checkIfIsEmpty($_POST, $attribute, $errors);
                $userLogin[$attribute] = $_POST[$attribute];
            }

            if (count($errors) != 0) {
                echo "<pre>";
                print_r($errors);
                echo "</pre>";
                if (isset($errors["key"]) && isset($errors["password"])) {
                    header("Location: /login?error=username&password");
                    exit();
                }
                if (isset($errors["key"])) {
                    header("Location: /login?error=key");
                    exit();
                } else {
                    header("Location: /login?error=password");
                    exit();
                }
            }

            UserService::authenticateUser($userLogin, $errors);

            if (count($errors) != 0) {
                //TODO: exception thrown for errors occurring during authentication
                echo "<pre>";
                print_r($errors);
                echo "</pre>";
                //header("Location: /login?error=" . $errors["exception"]);
            } else {
                header("Location: /home");
            }
        } catch (UserException $exception) {
            switch ($exception->getErrorCode()) {
                case UserException::INVALID_SESSION_CODE:
                    break;
            }
            //TODO: exception handling
            echo "<pre>";
            print_r($exception);
            echo "</pre>";
        }
    }

    /**
     * End the user session and take them to the login page
     */
    public static function logout(){
        session_start();
        session_destroy();
        header("Location: /login");
    }

    /**
     * Checks session integrity.
     * Checks if the user is logged in and if the data
     * contained in the session corresponds to the business rules
     */
    private static function checkSession()
    {
        ini_set("session.use_only_cookies", 1);
        ini_set("session.cookie_httponly", 1);
        ini_set("session.cookie_secure", 1);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $session_keys = ["username", "encryption_key", "loggedin", "img_url"];
        foreach ($session_keys as $key) {
            if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) {
                return false;
            }
        }
        return true;
    }
}
