<?php
require_once '../app/models/User.php';
require_once '../app/requests/RegisterRequest.php';
require_once '../core/Controller.php';
class RegisterController extends Controller
{
    /**
     * Registers a new user.
     *
     * This method handles the registration process by validating the incoming
     * request data, creating a new user, and returning a JSON response
     * indicating the success or failure of the registration.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response with the registration status and message.
     */

    public function register()
    {
        $request = $this->data;
        RegisterRequest::validateRegistration($request);
        $user = new User();
        if ($user->create($request)) {
            $response = ["status" => HTTP_OK, "message" => trans('user_registration_successful')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('User_registration_failed')];
        }
        Response::jsonResponse($response);
    }
}
