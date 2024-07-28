<?php
namespace App\Validator;

use App\Repository\UserRepository;
use App\Model\User;

class UserValidator
{
    const MIN_NAME_LENGTH = 4;
    const MAX_NAME_LENGTH = 50;
    const MIN_USERNAME_LENGTH = 5;
    const MAX_USERNAME_LENGTH = 32;
    const MIN_EMAIL_LENGTH = 14;
    const MAX_EMAIL_LENGTH = 50;
    const MIN_PASSWORD_LENGTH = 5;
    const MAX_PASSWORD_LENGTH = 32;

    public static function validate(User $user, array $newUser, array &$errors)
    {
        if (!isset($errors["name"])) {
            self::validateName($newUser["name"], $errors);
        }
        self::validateUsername($user, $newUser["username"], $errors);
        self::validateEmail($user, $newUser["email"], $errors);
        self::validatePassword(
            $newUser["password"],
            $newUser["repeat_password"],
            $errors
        );
    }

    public static function validateLogin(
        User $user,
        array $loginUser,
        array &$errors
    ): User | null {
        $user = self::validateUsernameOrEmail(
            $user,
            $loginUser["key"],
            $errors
        );

        if (empty($errors) && $user !== null) {
            self::validateLoginPassword($user, $loginUser, $errors);
        }
        if(count($errors)==0){
            return $user;
        }else{
            return null;
        }
    }

    public static function update(User $user, array $updateUser, array &$errors)
    {
        if (
            isset($updateUser["name"]) &&
            $user->__get("name_user") != $updateUser["name"]
        ) {
            self::validateName($updateUser["name"], $errors);
        }

        if (
            isset($updateUser["email"]) &&
            $user->__get("email") != $updateUser["email"]
        ) {
            self::validateEmail($user, $updateUser["email"], $errors);
        }

        if (
            isset($updateUser["username"]) &&
            $user->__get("username") != $updateUser["username"]
        ) {
            self::validateUsername($user, $updateUser["username"], $errors);
        }
    }

    public static function updatePasswordValidate(
        User $user,
        array $updateUser,
        array &$errors
    ) {
        if (
            strlen($updateUser["password"]) == 0 &&
            strlen($updateUser["repeat_password"]) == 0 &&
            strlen($updateUser["old_password"]) == 0
        ) {
            return false;
        } else {
            if (
                !password_verify(
                    $updateUser["old_password"],
                    $user->__get("password")
                )
            ) {
                $errors["old_password"] = "Password is incorrect";
                return false;
            } else {
                UserValidator::validatePassword(
                    $updateUser["password"],
                    $updateUser["repeat_password"],
                    $errors
                );
                if (count($errors)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    private static function validateUsernameOrEmail(
        User &$userLogin,
        string $key,
        array &$errors
    ): ?User {
        if (filter_var($key, FILTER_VALIDATE_EMAIL)) {
            if (self::validateEmailSize($key)) {
                $errors["key"] = "Email invalid size";
                return null;
            }
            return self::validateLoginEmail($userLogin, $key, $errors);
        } elseif (preg_match("/^[a-zA-Z0-9]+$/", $key)) {
            if (self::validateUsernameSize($key)) {
                $errors["key"] = "Username invalid size";
                return null;
            }
            return self::validateLoginUsername($userLogin, $key, $errors);
        } else {
            $errors["key"] = "It is neither username nor email";
            return null;
        }
    }

    private static function validateLoginPassword(
        User $user,
        array $loginUser,
        array &$errors
    ): void {
        if (self::validatePasswordSize($loginUser["password"])) {
            $errors["password"] = "Password invalid size";
            return;
        }

        if (
            !password_verify($loginUser["password"], $user->__get("password"))
        ) {
            $errors["login"] = "Password is wrong";
        }
    }

    private static function validateLoginUsername(
        User $userLogin,
        string $key,
        array &$errors
    ): ?User {
        $userLogin->__set("username", $key);
        $user = UserRepository::searchUserBy($userLogin, "username");

        if ($user === null) {
            $errors["key"] = "Username not found";
            return null;
        }

        return $user;
    }

    private static function validateLoginEmail(
        User $userLogin,
        string $key,
        array &$errors
    ): ?User {
        $userLogin->__set("email", $key);
        $user = UserRepository::searchUserBy($userLogin, "email");

        if ($user === null) {
            $errors["key"] = "Email not found";
            return null;
        }

        return $user;
    }

    private static function validateName(string $name, array &$errors)
    {
        if (UserValidator::validateNameSize($name)) {
            $errors["name"] =
                "The name must have at least " .
                self::MIN_NAME_LENGTH .
                " and a maximum of " .
                self::MAX_NAME_LENGTH .
                " characters.";
        } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            $errors["name"] = "The name format is not valid.";
        }
    }

    private static function validateUsername(
        User $user,
        string $username,
        array &$errors
    ) {
        $originalUsername = $user->__get("username");

        $user->__set("username", $username);
        $existingUser = UserRepository::searchUserBy($user, "username");

        $user->__set("username", $originalUsername);

        if (UserValidator::validateUsernameSize($username)) {
            $errors["username"] =
                "The username must have at least " .
                self::MIN_USERNAME_LENGTH .
                " and a maximum of " .
                self::MAX_USERNAME_LENGTH .
                " characters.";
        } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
            $errors["username"] = "The username format is not valid.";
        } elseif (
            $existingUser != null &&
            $existingUser->__get("id_user") != $user->__get("id_user")
        ) {
            $errors["username"] = "This username has already been registered.";
        }
    }

    private static function validateEmail(User $user, $email, &$errors)
    {
        $originalEmail = $user->__get("email");

        $user->__set("email", $email);
        $existingUser = UserRepository::searchUserBy($user, "email");

        $user->__set("email", $originalEmail);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "The email format is not valid.";
        } elseif (UserValidator::validateEmailSize($email)) {
            $errors["email"] =
                "The email must have at least " .
                self::MIN_EMAIL_LENGTH .
                " and a maximum of " .
                self::MAX_EMAIL_LENGTH .
                " characters.";
        } elseif (
            $existingUser != null &&
            $existingUser->__get("id_user") != $user->__get("id_user")
        ) {
            $errors["email"] = "This email has already been registered.";
        }
    }

    private static function validatePassword(
        string $password,
        string $repeatPassword,
        array &$errors
    ) {
        if (UserValidator::validatePasswordSize($password)) {
            $errors["password"] =
                "The password must have at least " .
                self::MIN_PASSWORD_LENGTH .
                " and a maximum of " .
                self::MAX_PASSWORD_LENGTH .
                " characters.";
        } elseif ($password !== $repeatPassword) {
            $errors["password"] = "The passwords are different.";
            $errors["repeat_password"] = "The passwords are different.";
        }
    }

    private static function validateNameSize(string $name): bool
    {
        return strlen($name) < self::MIN_NAME_LENGTH ||
            strlen($name) > self::MAX_NAME_LENGTH;
    }

    private static function validatePasswordSize(string $password): bool
    {
        return strlen($password) < self::MIN_PASSWORD_LENGTH ||
            strlen($password) > self::MAX_PASSWORD_LENGTH;
    }

    private static function validateUsernameSize(string $username): bool
    {
        return strlen($username) < self::MIN_USERNAME_LENGTH ||
            strlen($username) > self::MAX_USERNAME_LENGTH;
    }

    private static function validateEmailSize(string $email): bool
    {
        return strlen($email) < self::MIN_EMAIL_LENGTH ||
            strlen($email) > self::MAX_EMAIL_LENGTH;
    }
}
