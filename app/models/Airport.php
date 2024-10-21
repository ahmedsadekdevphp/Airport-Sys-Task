<?php

namespace App\Models;

use Core\Model;
use App\Services\Response;

class Airport extends Model
{
    private $table_name = "airports";


    /**
     * Searches for airports in the database by a specified column and value.
     *
     * @param string $column The column to search in (e.g., 'airport_name','airport_code').
     * @param mixed $value The value to search for.
     * @return mixed The result of the search query (typically an array of airport records).
     */
    public function searchInAirports($column, $value)
    {
        $columns = 'id, airport_name, airport_code, airport_city,country_id,created_at';
        return $this->QueryBuilder->searchByKey($columns, $this->table_name, $column, $value);
    }


    /**
     * Retrieves a paginated list of airports from the database with optional filters and sorting.
     *
     * @param int $page The current page number for pagination.
     * @param array $filters An associative array of filters to apply to the query.
     * @param string $sort_by The column to sort the results by.
     * @return array The paginated list of airports.
     */
    public function getAirPorts($page, $filters, $sort_by)
    {
        $columns = 'id, airport_name, airport_code, airport_city,country_id,created_at';
        return $this->QueryBuilder->paginate($this->table_name, $page, config('PAGINATE_NUM'), $columns, $filters, $sort_by);
    }

    /**
     * Checks if an airport exists by its ID and retrieves its details.
     *
     * @param int $id The ID of the airport to check.
     * @return mixed The airport details if found, or a JSON response if not.
     */
    public function checkAirport($id)
    {
        $airport = $this->QueryBuilder->find($this->table_name, ['id' => $id], ['id,airport_name']);
        if (!$airport) {
            Response::jsonResponse(["status" => HTTP_BAD_REQUEST, "message" => trans('airport_not_exist')]);
        }
        return $airport;
    }

    public function deleteAirport($id)
    {
        $result = $this->QueryBuilder->deleteRecord($this->table_name, $id);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('Airport_deleted')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }

    public function updateAirport($data, $id)
    {
        $this->checkAirport($id);

        $fields = [
            'airport_name' => $data['name'],
            'airport_code' => $data['airport_code'],
            'airport_city' => $data['city'],
        ];
        $conditions = [
            'id' => $id
        ];
        $result = $this->QueryBuilder->updateFields($this->table_name, $fields, $conditions);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('Airport_updated')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }
    public function createAirport($data)
    {
        $result = $this->QueryBuilder->insert($this->table_name, [
            'airport_name' => $data['name'],
            'airport_code' => $data['airport_code'],
            'airport_city' => $data['city'],
            'country_id' => $data['country'],
            'user_id' => auth('id'),
        ]);
        if ($result) {
            $response = ["status" => HTTP_OK, "message" => trans('Airport_Added')];
        } else {
            $response = ["status" => HTTP_INTERNAL_SERVER_ERROR, "message" => trans('server_error')];
        }
        return $response;
    }
}
