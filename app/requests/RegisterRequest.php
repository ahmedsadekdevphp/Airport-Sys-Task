<?php

require_once '../core/Validatore.php';

class RegisterRequest
{
    public static function validateRegistration($data)
    {
        $validator = new Validator();
        $rules = [
            'full_name' => 'required',
             'email' => 'required|email|unique:airport_users',
            'password'  => 'required|password'
        ];
       return $validator->validate($data, $rules);
    }
}
