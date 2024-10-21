<?php

namespace App\Requests;
use Core\Validator;

class SearchAirportRequestName
{
    public static function validate($data)
    {
        $validator = new Validator();
        $rules = [
            'name' => 'required'
         ];
        return $validator->validate($data, $rules);
    }
}
