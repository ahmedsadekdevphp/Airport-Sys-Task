<?php

require_once '../core/Validatore.php';

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
