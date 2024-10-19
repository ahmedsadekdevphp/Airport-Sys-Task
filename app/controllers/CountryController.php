<?php
require_once '../app/models/Country.php';
require_once '../core/Controller.php';
require_once '../app/requests/ResetPasswordRequest.php';

class CountryController extends Controller
{
    private $country;

    public function __construct()
    {
        parent::__construct();
        $this->country = new Country();
    }
    public function index()
    {
        $countries = $this->country->getCountries();
        Response::jsonResponse(['status' => HTTP_OK, 'data' => $countries]);
    }
}
