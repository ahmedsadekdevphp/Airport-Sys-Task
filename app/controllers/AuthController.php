<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Requests\AuthRequest;
use App\Services\Response;

class AuthController extends Controller
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }


    public function login()
    {
        $request = $this->data;
        $validatedData = AuthRequest::validate($request);
        $response = $this->user->Login($validatedData);
        Response::jsonResponse($response);
    }

    public function logout()
    {
        $this->user->updateTokenVersion(auth('id'));
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        Response::jsonResponse(["status" => HTTP_OK, "message" => trans('your_are_loged_out')]);
    }
}
