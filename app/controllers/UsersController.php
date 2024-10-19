<?php
require_once '../app/models/User.php';
require_once '../core/Controller.php';
class UsersController extends Controller
{
    public function index()
    {
        $user = new User();
        $users = $user->getAllUsers();
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $users]);
    }

    
}
