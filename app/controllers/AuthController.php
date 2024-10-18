<?php
require_once '../app/models/User.php';
require_once '../app/requests/AuthRequest.php';
require_once '../core/Controller.php';
class AuthController extends Controller
{
    public function login()
    {
        $request = $this->data;
        $validatedData = AuthRequest::validate($request);
        $user = new User();
        $response = $user->Login($validatedData);
        Response::jsonResponse($response);
    }

    public function index(){

    }
}
