<?php

require_once '../core/Validatore.php';

class AuthRequest
{
    public static function validate($data)
    {
        $validator = new Validator();
        $rules = [
            'email' => 'required|email',
            'password'  => 'required'
        ];
        return $validator->validate($data, $rules);
    }
}
