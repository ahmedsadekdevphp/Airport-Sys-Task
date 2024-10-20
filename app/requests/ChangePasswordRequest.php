<?php

require_once '../core/Validatore.php';

class ChangePasswordRequest
{
    public static function validate($data)
    {
        $validator = new Validator();
        $rules = [
            'old_password'  => 'required',
            'password'  => 'required|password',
            'confirm_password' => 'required|confirm_password'
        ];
        return $validator->validate($data, $rules);
    }
}
