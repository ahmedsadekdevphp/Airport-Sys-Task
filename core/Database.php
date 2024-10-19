<?php

class Database
{
    private static $instance = null;
    private $host = '';
    private $user = '';
    private $pass = '';
    private $dbname = '';

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        // Assign configuration values to class properties
        $this->host = config('DB_HOST');
        $this->user =  config('DB_USER');
        $this->pass = config('DB_PASS');
        $this->dbname = config('DB_NAME');

        // DSN for PDO connection
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        // Try to create a PDO instance and handle any errors
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die($this->error);
        }
    }
    public static function getConnection()
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->dbh;
    }

    // Prepare a SQL query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values to the prepared statement
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Fetch result set as an array of objects
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function prepare($sql)
    {
        return $this->dbh->prepare($sql);
    }

}
