<?php
require_once '../app/models/User.php';
require_once '../core/Controller.php';
require_once '../app/requests/ProfileUpdateRequest.php';
require_once '../app/requests/ChangePasswordRequest.php';
class ProfileController extends Controller
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    public function Update()
    {
        $userID = 18;
        $request = $this->data;
        $validatedData = ProfileUpdateRequest::validate($request, $userID);
        $response = $this->user->updateInfo($userID, $validatedData);
        Response::jsonResponse($response);
    }

    public function changePassword()
    {
        $userID = 18;
        $validatedData = ChangePasswordRequest::validate($this->data, $userID);
        $user = $this->user->findUser($userID);
        $result = $this->user->checkPassword($user['password'], $validatedData['old_password']);
        if (!$result) {
            $response = [
                "status" => HTTP_UNAUTHORIZED,
                "message" => trans('wrong_password')
            ];
        } else {
            $response = $this->user->resetPassword($userID, $validatedData['password']);
        }
        Response::jsonResponse($response);
    }
}
