<?php
require_once '../core/Model.php';
require_once '../app/services/JwtService.php';

class User extends Model
{
    private $table_name = "airport_users";

    public $full_name;
    public $email;
    public $password;
    public $role;
    public $is_approved;


    public function create(array $data)
    {
        $full_name = htmlspecialchars(strip_tags($data['full_name']));
        $email = htmlspecialchars(strip_tags($data['email']));
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $query = "INSERT INTO " . $this->table_name . " SET full_name=:full_name, email=:email, password=:password";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        return $stmt->execute();
    }

    /**
     * Authenticates a user by their email and password.
     *
     * @param array $data The login credentials containing:
     * - 'email': The user's email address.
     * - 'password': The user's password.
     *
     * @return array An associative array containing:
     * - 'status': HTTP status code.
     * - 'message': A descriptive message about the login result.
     * - 'data': Optional. An associative array containing the generated token if login is successful.
     */
    public function Login(array $data)
    {
        $user = $this->getUser($data['email']);
        if (!$user) {
            return [
                "status" => HTTP_UNAUTHORIZED,
                "message" => trans('Email_not_found')
            ];
        }
        $result = $this->checkPassword($user, $data['password']);
        if (!$result) {
            return [
                "status" => HTTP_UNAUTHORIZED,
                "message" => trans('wrong_password')
            ];
        }
        $active = $this->checkIsActivated($user);
        if (!$active) {
            return ["status" => HTTP_FORBIDDEN, "message" => trans('user_not_activated')];
        }

        $token = $this->generateToken($user);
        return [
            "status" => HTTP_OK,
            "message" => trans('login_successful'),
            "data" => ['token' => $token]
        ];
    }
    /**
     * Generates a JWT  for  user.
     *
     * @param array $user An associative array containing user information:
     * - 'id': The unique identifier of the user.
     * - 'email': The user's email address.
     *  - 'role': The user's role in the application.
     * @return string The generated JWT token for the user.
     */

    private function generateToken($user)
    {
        return JWTService::generateToken([
            "id" => $user['id'],
            "email" => $user['email'],
            "role" => $user['role']
        ]);
    }
    /**
     * Retrieves a user record from the database by email.
     * @param string $email The email address of the user to retrieve.
     * @return array|null An associative array containing the user's information if found, 
     * or null if no user with the specified email exists.
     */
    private function getUser($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function checkPassword($user, $password)
    {
        return password_verify($password, $user['password']);
    }
    /**
     * Checks if the user's account is activated based on the 'approved' status.
     * @param array $user An associative array containing user information, 
     *  which must include the 'approved' key.
     *
     * @return bool Returns true if the user's account is activated (approved), 
     * or false if it is not activated.
     */
    private function checkIsActivated($user)
    {
        return $user['approved'] == 1;
    }
}
