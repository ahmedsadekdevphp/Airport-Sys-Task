<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\User;
use App\Requests\AuthRequest;
use App\Services\Response;

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
}
