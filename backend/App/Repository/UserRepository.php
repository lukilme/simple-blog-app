<?php
namespace App\Repository;
use App\Model\User;

class UserRepository
{
    /**
     * Inserts a new user into the database.
     * @param User $user The user object to be inserted.
     * @return User The inserted user object.
     * @throws \Exception If the insertion fails.
     */
    public static function insert(User $user){
        try {
            $query = "INSERT INTO `User` (
                username,
                email,
                password_hash,
                encryption_key,
                name_user,
                registration_date
              ) values (
                :username,
                :email,
                :password_hash,
                :encryption_key,
                :name_user,
                :registration_date);";
            $stmt = $user->db->prepare($query);
            $user->__set("registration_date", date("Y-m-d H:i:s"));
            $stmt->bindValue(":username", $user->__get("username"));
            $stmt->bindValue(":password_hash", $user->__get("password"));
            $stmt->bindValue(":email", $user->__get("email"));
            $stmt->bindValue(":name_user", $user->__get("name_user"));
            $stmt->bindValue(":encryption_key", $user->__get("encryption_key"));
            $stmt->bindValue(
                ":registration_date",
                $user->__get("registration_date")
            );
            $stmt->execute();
            return $user;
        } catch (\PDOException $e) {
            throw new \Exception("Failed to insert user: " . $e->getMessage());
        }
    }
    /**
     * Ensures integrity by verifying the data provided by the database
     * @param User $user The object that will be filled with data from the database
     * @param array $result The object provided by the database
     * @return User The object with its regular fields on success
     * @return null If the fields are irregular
     */
    public static function checkData($result, User $user){
        if (
            !empty($result["id_user"]) &&
            !empty($result["email"]) &&
            !empty($result["username"]) &&
            !empty($result["registration_date"]) &&
            !empty($result["name_user"]) &&
            !empty($result["password_hash"]) &&
            !empty($result["image_url"]) &&
            !empty($result["encryption_key"])
        ) {
            $user->__set("id_user", $result["id_user"]);
            $user->__set("name_user", $result["name_user"]);
            $user->__set("registration_date", $result["registration_date"]);
            $user->__set("email", $result["email"]);
            $user->__set("username", $result["username"]);
            $user->__set("encryption_key", $result["encryption_key"]);
            $user->__set("image_url", $result["image_url"]);
            $user->__set("password", $result["password_hash"]);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Update user data
     * @param User $user The object with the updated data
     * @return array $result an array with something provided by the database after the update
     * @throws \Exception If the update fails.
     */
    public static function update(User $user){
        try {
            $query = "UPDATE `User` SET
                    name_user = :name_user,
                      username = :username,
                      email = :email,
                      password_hash = :password_hash,
                      encryption_key = :encryption_key
                    WHERE id_user = :id_user";

            $stmt = $user->db->prepare($query);
            $stmt->bindValue(":id_user", $user->__get("id_user"));
            $stmt->bindValue(":username", $user->__get("username"));
            $stmt->bindValue(":password_hash", $user->__get("password"));
            $stmt->bindValue(":email", $user->__get("email"));
            $stmt->bindValue(":name_user", $user->__get("name_user"));
            $stmt->bindValue(":encryption_key", $user->__get("encryption_key"));

            $stmt->execute();
        } catch (\PDOException $e) {
            throw new \Exception("Failed to update user: " . $e->getMessage());
        }
    }
    /**
     * Deletes the user from the database
     * @param User $user The object that will serve as a reference to be deleted from the database
     * @return array $result an array with something provided by the database after removal
     * @throws \Exception If there is a failure to delete the user.
     */
    public static function deleteUser(User $user){
        try {
            $query = "DELETE FROM `User` WHERE id_user = :id_user;";
            $stmt = $user->db->prepare($query);
            $stmt->bindValue(":id_user", $user->__get("id_user"));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new \Exception("Failed to delete user: " . $e->getMessage());
        }
    }
    /**
     * Searches the database for the user based on reference data
     * @param User $user The object that will serve as a reference to be searched
     * @param array $keys The attribute that will be used to search the database
     * @return array User $user The desired object if found
     * @return null If not found, returns null.
     * @throws \Exception If user lookup fails.
     */
    public static function searchForCompatibleUser(
        User $user,
        $key = "id_user"
    ) {
        try {
            $query = "SELECT 
            id_user,
            username,
            email,
            password_hash,
            name_user,
            registration_date,
            image_url,
            is_adm
        FROM `User` WHERE $key = :$key;";
            $stmt = $user->db->prepare($query);
            $stmt->bindValue(":$key", $user->__get($key));
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $user = UserRepository::checkData($result, $user);
            return $user;
        } catch (\PDOException $e) {
            throw new \Exception("Failed to get user: " . $e->getMessage());
        }
    }
    /**
     * Searches the database for the user based on reference data
     * @param User $user The object that will serve as a reference to be searched
     * @param string $key The attribute that will be used to search the database
     * @return User $user The desired object if found
     * @return null If not found, returns null.
     * @throws \Exception If user lookup fails.
     */
    public static function searchUserBy(User $user, $key = "id_user"){
        try {
            $query = "SELECT 
                id_user,
                username,
                email,
                password_hash,
                encryption_key,
                name_user,
                registration_date,
                image_url,
                is_adm
            FROM `User` WHERE $key = :$key;";

            $stmt = $user->db->prepare($query);
            $stmt->bindValue(":$key", $user->__get($key));
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result) {
                $foundUser = new User($user->db);
                $foundUser = self::checkData($result, $foundUser);
                return $foundUser;
            } else {
                return null;
            }
        } catch (\PDOException $e) {
            throw new \Exception("Failed to get user: " . $e->getMessage());
        }
    }

    public static function saveProfilePicture($image)
    {
    }
    /**
     * Compares username and password with those stored in the database
     * @param User $user The object that will serve as a reference to be searched
     * @return User $user The object if the data is equal they are equal
     * @return null If the supplied data does not exist or does not match the data in the database
     * @throws \Exception If user authentication fails.
     */
    public static function authenticate(User $user){
        try {
            $query = "SELECT
            id_user,
            username,
            email,
            password_hash,
            name_user,
            registration_date,
            image_url,
            is_adm
            FROM `User`
            WHERE username = :username and
            password_hash = :password_hash;";
            $stmt = $user->db->prepare($query);
            $stmt->bindValue(":username", $user->__get("username"));
            $stmt->bindValue(":password_hash", $user->__get("password"));
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $user = UserRepository::checkData($result, $user);
            return $user;
        } catch (\PDOException $e) {
            throw new \Exception("Failed to get user: " . $e->getMessage());
        }
    }
}
