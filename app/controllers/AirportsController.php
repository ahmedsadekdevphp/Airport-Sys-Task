<?php
require_once '../app/models/Airport.php';
require_once '../core/Controller.php';
require_once '../app/requests/UpdateAirportRequest.php';
require_once '../app/requests/AirportRequest.php';
require_once '../app/models/Country.php';

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

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : config('FIRST_PAGE');
        $airports=$this->airport->GetAirPorts($page);
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $airports]);
    }

    public function store()
    {
        $request = $this->data;
        $validatedData = AirportRequest::validate($request);
        $this->country->checkCountry($validatedData['country']);
        $response = $this->airport->createAirport($validatedData);
        Response::jsonResponse($response);
    }

    public function update($airport_id)
    {
        $request = $this->data;
        $validatedData = UpdateAirportRequest::validate($request,$airport_id);
        $response = $this->airport->UpdateAirport($validatedData, $airport_id);
        Response::jsonResponse($response);
    }

    public function destory($id)
    {
        $response = $this->airport->DeleteAirport($id);
        Response::jsonResponse($response);
    }
}
