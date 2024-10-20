<?php

require_once '../core/Validatore.php';

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
