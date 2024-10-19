<?php
require_once '../core/QueryBuilder.php';

class Model
{
    protected $QueryBuilder;
    public function __construct()
    {
        $this->QueryBuilder = new QueryBuilder();
    }
}
