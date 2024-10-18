<?php
class RoleMiddleware
{
    private $Role;

    public function __construct($Role)
    {
        $this->Role = $Role;
    }

    public function handle()
    {
        $userRole = $this->getUserRole();
        if ($userRole !== $this->Role) {
            http_response_code(403); // Forbidden
            echo json_encode(['message' => 'Access denied. Insufficient permissions.']);
            exit;
        }
    }

    private function getUserRole()
    {
        return 'operator';
    }
}
