<?php

namespace App\Requests;
use Core\Validator;

class SearchAirportRequestCode
{
    public static function validate($data)
    {
        $validator = new Validator();
        $rules = [
            'code' => 'required'
         ];
        return $validator->validate($data, $rules);
    }
}
