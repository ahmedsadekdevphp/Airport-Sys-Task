<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\Country;
use App\Services\Response;

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
