<?php
require_once '../core/Model.php';

class Country extends Model
{
    private $table_name = "airport_countries";

    public  function checkCountry($country_id)
    {
        $columns = 'id,name';
        $conditions = [
            'id' => $country_id
        ];
        $exist = $this->QueryBuilder->getAll($this->table_name, $columns, $conditions);
        if (!$exist) {
            Response::jsonResponse(["status" => HTTP_BAD_REQUEST, "message" => trans('country_not_exist')]);
        }
    }
    public function getCountries()
    {
        $columns = 'id,name';
        return $this->QueryBuilder->getAll($this->table_name, $columns);
    }
}
