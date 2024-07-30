<?php

namespace App\Controller;

use App\Service\UserService;
use App\Exception\UserException;

class UserController extends Controller
{
    /**
     * Register the user, if the parameters are valid.
     * @param REQUEST = {name, username, email, password, repeat_password}
     * @return RESPONSE 400 for non-compliance with business rules.
     * @return RESPONSE 201 for successful register.
     * @return RESPONSE 500 error that the server did not know how to handle
     * @return RESPONSE 403 if the user is already authenticated.
     */
    public function register_user()
    {
        try {
            if ($this->checkSession()) {
                return $this->sendResponse(403, [
                    "message" => "You are already authenticated",
                ]);
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
                    "error" => "Invalid data.",
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
        } catch (UserException $exception) {
            error_log($exception->getMessage());
            return $this->sendResponse(500, [
                "message" => "Internal server error",
                "error" => $exception->getMessage(),
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

    public function update()
    {
    }

    /**
     * Perform authentication.
     * Check if the username and password correspond to any
     * user in the database and store in the session
     * @return RESPONSE 400 for non-compliance with business rules.
     * @return RESPONSE 401 password and keys are incompatible
     * @return RESPONSE 200 for successful logged.
     * @return RESPONSE 500 error that the server did not know how to handle
     * @return RESPONSE 403 if the user is already authenticated.
     */
    public function authenticate()
    {
        try {
            if ($this->checkSession()) {
                return $this->sendResponse(403, [
                    "message" => "success",
                ]);
                return;
            }
            $errors = [];
            $input = file_get_contents("php://input");
            $userLogin = json_decode($input, true);

            // Check if there was a decoding error
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->sendResponse(400, [
                    "error" => "Invalid data.",
                ]);
            }

            $attributes = ["key", "password"];

            foreach ($attributes as $attribute) {
                $this->checkIfIsEmpty($userLogin, $attribute, $errors);
            }

            if (!empty($errors)) {
                return $this->sendResponse(400, [
                    "message" => "Bad Request.",
                    "errors" => $errors,
                ]);
            }

            $authenticationErrors = UserService::authenticateUser(
                $userLogin,
                $errors
            );

            if (!empty($authenticationErrors)) {
                return $this->sendResponse(401, [
                    "message" => "Login attempt failed",
                    "errors" => $authenticationErrors,
                ]);
            }
            return $this->sendResponse(200, [
                "message" => "success",
            ]);
        } catch (UserException $exception) {
            // Log the exception for debugging purposes
            error_log($exception->getMessage());
            return $this->sendResponse(500, [
                "message" => "Internal server error",
                "error" => $exception->getMessage(),
            ]);
        }
    }

    /**
     * End the user session
     * @return RESPONSE 401 if not authenticated.
     * @return RESPONSE 200 for successful logout.
     * @return RESPONSE 500 error that the server did not know how to handle
     */
    public function logout()
    {
        try {
            if ($this->checkSession()) {
                session_start();
                session_destroy();
                header("Location: /login");
                return $this->sendResponse(200, [
                    "message" => "Logout successful",
                ]);
            } else {
                return $this->sendResponse(401, [
                    "message" => "You are not authenticated",
                ]);
            }
        } catch (\Exception $exception) {
            return $this->sendResponse(500, [
                "message" => "Internal server error",
            ]);
        }
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
