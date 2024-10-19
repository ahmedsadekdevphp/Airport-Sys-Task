<?php

require_once '../core/Validatore.php';

class UpdateAirportRequest
{
    public static function validate($data,$id)
    {
        $validator = new Validator();
        $rules = [
            'name' => 'required',
            'airport_code' => 'required|unique:airports',
            'city' => 'required',
        ];
        return $validator->validate($data, $rules,$id);
    }
}
