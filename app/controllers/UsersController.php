<?php
require_once '../app/models/User.php';
require_once '../core/Controller.php';
require_once '../app/requests/ResetPasswordRequest.php';

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


    public function activateUser()
    {
        $userId = 27;
        $result = $this->user->changeStatus($userId, config('USER_STATUS_APPROVED'));
        if (!$result) {
            Response::jsonResponse(["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')]);
        }
        Response::jsonResponse(["status" => HTTP_OK, "message" => trans('user_activated')]);
    }

    public function disableUser()
    {
        $userId = 27;
        $result = $this->user->changeStatus($userId, config('USER_STATUS_APPROVED'));
        if (!$result) {
            Response::jsonResponse(["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')]);
        }
        Response::jsonResponse(["status" => HTTP_OK, "message" => trans('user_disabled')]);
    }

    public function resetPassword()
    {
        $userId = 27;
        $request = $this->data;
        $validatedData = ResetPasswordRequest::validate($request);
        $result = $this->user->resetPassword($userId, $validatedData['password']);
        if (!$result) {
        Response::jsonResponse(["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')]);
        }
        Response::jsonResponse(["status" => HTTP_OK, "message" => trans('password_changed')]);
    }
}
