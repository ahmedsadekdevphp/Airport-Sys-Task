<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Requests\ResetPasswordRequest;
use App\Requests\ChangeRoleRequest;
use App\Services\Response;

class UsersController extends Controller
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : config('FIRST_PAGE');
        $users = $this->user->getAllUsers($page);
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $users]);
    }


    public function activateUser($userId)
    {
        $result = $this->user->changeStatus($userId, config('USER_STATUS_APPROVED'));
        if (!$result) {
            Response::jsonResponse(["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')]);
        }
        Response::jsonResponse(["status" => HTTP_OK, "message" => trans('user_activated')]);
    }

    public function disableUser($userId)
    {
        $result = $this->user->changeStatus($userId, config('USER_STATUS_DISABLED'));
        if (!$result) {
            Response::jsonResponse(["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')]);
        }
        Response::jsonResponse(["status" => HTTP_OK, "message" => trans('user_disabled')]);
    }

    public function changeRole($userId)
    {
        $validatedData = ChangeRoleRequest::validate($this->data);
        $response = $this->user->changeRole($userId, $validatedData['role']);
        Response::jsonResponse($response);
    }


    public function resetPassword($userId)
    {
        $validatedData = ResetPasswordRequest::validate($this->data);
        $response = $this->user->resetPassword($userId, $validatedData['password']);
        return $response;
    }
}
