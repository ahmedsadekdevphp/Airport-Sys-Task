<?php

namespace Database;

use Core\QueryBuilder;

class Filldata
{

    protected $QueryBuilder;
    public function __construct()
    {
        $this->QueryBuilder = new QueryBuilder();
    }
    public function seedCountry()
    {
        $jsonFilePath = __DIR__ . '/data/countries.json'; 

        // Read the JSON file
        $jsonData = file_get_contents($jsonFilePath);

        // Check if the file was read successfully
        if ($jsonData === false) {
            die('Error reading the JSON file');
        }

        // Decode the JSON into an associative array
        $countries = json_decode($jsonData, true);
        foreach ($countries as $country) {
           $this->QueryBuilder->insert('airport_countries', [
                'name' => $country['name'],
                'code' => $country['code'],
            ]);
        }
        echo "Inserted countries done";
    }
}
