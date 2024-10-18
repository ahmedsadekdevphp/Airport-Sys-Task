<?php
require_once '../core/Model.php';

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
}
