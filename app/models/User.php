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
    public $approved;

    public function changeRole($id, $newRole)
    {
        $this->findRole($newRole);
        $this->findUser($id);
        $result = $this->QueryBuilder->updateFields($this->table_name, ['role' => $newRole], ['id' => $id]);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('role_updated')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }
    public  function findRole($role)
    {
        if (!in_array($role, ['admin', 'operator'])) {
            Response::jsonResponse(["status" => HTTP_BAD_REQUEST, "message" => trans('invalid_role')]);
        }
    }
    public function findUser($id)
    {
        $user = $this->QueryBuilder->find($this->table_name, ['id' => $id], ['password']);
        if (!$user) {
            Response::jsonResponse(["status" => HTTP_BAD_REQUEST, "message" => trans('user_not_exist')]);
        }
        return $user;
    }

    public function updateInfo($userID, $data)
    {
        $fields = [
            'full_name' => $data['full_name'],
            'email' => $data['email']
        ];
        $conditions = [
            'id' => $userID
        ];
        $result = $this->QueryBuilder->updateFields($this->table_name, $fields, $conditions);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('profile_updated')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }
    public function resetPassword($userID, $newPass)
    {
        $fields = [
            'password' => password_hash($newPass, PASSWORD_BCRYPT)
        ];
        $conditions = [
            'id' => $userID
        ];
        $result = $this->QueryBuilder->updateFields($this->table_name, $fields, $conditions);
        if (!$result) {
            $response = Response::jsonResponse(["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')]);
        } else {
            $response = Response::jsonResponse(["status" => HTTP_OK, "message" => trans('password_changed')]);
        }
        return $response;
    }

    public function changeStatus($userID, $status)
    {
        $this->findUser($userID);
        return $this->QueryBuilder->updateFields($this->table_name, ['approved' => $status], ['id' => $userID]);
    }
    public function getAllUsers($page)
    {
        $columns = 'id, full_name, email, role,approved';
        return $this->QueryBuilder->paginate($this->table_name, $page, config('PAGINATE_NUM'), $columns);
    }

    public function create(array $data)
    {
        $result = $this->QueryBuilder->insert($this->table_name, [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('user_registration_successful')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
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
        $result = $this->checkPassword($user['password'], $data['password']);
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
        $user = $this->QueryBuilder->find($this->table_name, ['email' => $email]);
        return $user;
    }

    public function checkPassword($password, $inputPassword)
    {
        return password_verify($inputPassword, $password);
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
        return $user['approved'] == config('USER_STATUS_APPROVED');
    }
}
