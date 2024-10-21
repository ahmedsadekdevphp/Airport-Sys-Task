<?php
namespace App\Requests;
use Core\Validator;

class CreateAirportRequest
{
    public static function validate($data)
    {
        $validator = new Validator();
        $rules = [
            'name' => 'required',
            'airport_code' => 'required|unique:airports',
            'city' => 'required',
            'country'  => 'required|integer',
        ];
        return $validator->validate($data, $rules);
    }
}
