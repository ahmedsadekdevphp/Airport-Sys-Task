<?php

require_once '../core/Validatore.php';

class ChangeRoleRequest
{
    public static function validate($data)
    {
        $validator = new Validator();
        $rules = [
            'role' => 'required'
                ];
        return $validator->validate($data, $rules);
    }
}
