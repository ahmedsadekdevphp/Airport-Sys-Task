<?php
require_once '../core/Database.php';
require_once '../core/QueryBuilder.php';

class Model
{
    protected $conn;
    protected $QueryBuilder;
    public function __construct()
    {
        $this->conn = Database::getConnection();
        $this->QueryBuilder = new QueryBuilder($this->conn);
    }
}
