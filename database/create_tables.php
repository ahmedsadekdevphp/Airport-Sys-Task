<?php

class Database
{
    private $conn;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function executeSql($sql)
    {
        try {
            $this->conn->exec($sql);
            return "Table creation successful";
        } catch (PDOException $e) {
            return "Table creation failed: " . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}

// Configuration parameters
require 'config.php';

$db = new Database($host, $dbname, $username, $password);

// path of table files
$tableFiles = [
    'country' => 'database/create_country_table.sql',
    'airport' => 'database/create_airport_table.sql',
    'user' => 'database/create_user_table.sql',
    'seessions' => 'database/create_airport_sessions.sql',

];

// Iterate through each  file and execute SQL
foreach ($tableFiles as $table => $filePath) {
    $sql = file_get_contents($filePath);
    $result = $db->executeSql($sql);
    echo "Result for $table table: $result\n";
}
