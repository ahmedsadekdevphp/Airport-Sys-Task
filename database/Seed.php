<?php

require_once(__DIR__ . '/../core/QueryBuilder.php');
class Seed
{
    protected $QueryBuilder;
    public function __construct()
    {
        $this->QueryBuilder = new QueryBuilder();
    }
    public function handel()
    {
        $jsonFilePath = '/database/data/countries.json';

        // Read the JSON file
        $jsonData = file_get_contents($jsonFilePath);

        // Check if the file was read successfully
        if ($jsonData === false) {
            die('Error reading the JSON file');
        }

        // Decode the JSON into an associative array
        $countries = json_decode($jsonData, true);
        foreach ($countries as $country) {
            $this->insertCountry($country['name'], $country['code']);
        }
        echo "Inserted countries done";
    }

    public function insertCountry($name, $code)
    {

        $result = $this->QueryBuilder->insert('airport_countries', [
            'name' => $name,
            'code' => $code,
        ]);
        return $result;
    }
}

$seed=new Seed();
$seed->handel();