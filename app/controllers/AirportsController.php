<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Airport;
use App\Models\Country;
use App\Services\NotifyAdmin;
use App\Requests\UpdateAirportRequest;
use App\Requests\CreateAirportRequest;
use App\Requests\SearchAirportRequestCode;
use App\Requests\SearchAirportRequestName;
use App\Services\Response;

class AirportsController extends Controller
{
    private $airport;
    private $country;

    public function __construct()
    {
        parent::__construct();
        $this->airport = new Airport();
        $this->country = new Country();
    }

    /**
     * Handles the request to retrieve a list of airports.
     *
     */

    public function index()
    {
        $filters = $this->extractFilters();
        $sort_by = $this->getSortOption();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : config('FIRST_PAGE');
        $airports = $this->airport->getAirPorts($page, $filters, $sort_by);
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $airports]);
    }

    public function store()
    {
        $request = $this->data;
        $validatedData = CreateAirportRequest::validate($request);
        $this->country->checkCountry($validatedData['country']);
        $response = $this->airport->createAirport($validatedData);
        NotifyAdmin::sendActionEmail(trans('added'), $validatedData['name']);
        Response::jsonResponse($response);
    }

    public function update($airport_id)
    {
        $request = $this->data;
        $validatedData = UpdateAirportRequest::validate($request, $airport_id);
        $response = $this->airport->updateAirport($validatedData, $airport_id);
        NotifyAdmin::sendActionEmail(trans('updated'), $validatedData['name']);
        Response::jsonResponse($response);
    }

    /**
     * Retrieves airports based on the specified airport name from the request.
     * 
     */
    public function getAirportByName()
    {
        $request = $this->data;
        $validatedData = SearchAirportRequestName::validate($request);
        $airports = $this->airport->searchInAirports('airport_name', $validatedData['name']);
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $airports]);
    }
    /**
     * Retrieves airports based on the specified airport code from the request.
     * 
     */
    public function getAirportByCode()
    {
        $request = $this->data;
        $validatedData = SearchAirportRequestCode::validate($request);
        $airports = $this->airport->searchInAirports('airport_code', $validatedData['code']);
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $airports]);
    }
    public function destory($id)
    {
        $airport = $this->airport->checkAirport($id);
        $response = $this->airport->deleteAirport($id);
        NotifyAdmin::sendActionEmail(trans('deleted'), $airport['airport_name']);
        Response::jsonResponse($response);
    }
    /**
     * Extract and sanitize filter inputs from the request.
     * 
     * @return array
     */
    private function extractFilters(): array
    {
        $filters = [];

        // Sanitize city input
        $city = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($city) {
            $filters['airport_city'] = $city;
        }

        // Sanitize country (integer)
        $country = filter_input(INPUT_GET, 'country', FILTER_VALIDATE_INT);
        if ($country) {
            $filters['country_id'] = $country;
        }

        // Sanitize start date
        $start_date = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($start_date) && $this->isValidDate($start_date)) {
            $filters['start_at'] = $start_date;
        }

        // Sanitize end date
        $end_date = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($end_date) && $this->isValidDate($end_date)) {
            $filters['end_date'] = $end_date;
        }

        return $filters;
    }
    /**
     * Get the sort option from the request, ensuring it's sanitized.
     * 
     * @return string|null
     */
    private function getSortOption(): ?string
    {
        $allowed_sort_columns = [
            'name' => 'airport_name',
            'date' => 'created_at',
        ];
        $sort_by = filter_input(INPUT_GET, 'sort_by', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        return $allowed_sort_columns[$sort_by] ?? null;
    }

    /**
     * Validate if a string is in the YYYY-MM-DD date format.
     * 
     * @param string $date
     * @return bool
     */
    private function isValidDate(string $date): bool
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1;
    }
}
