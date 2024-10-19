<?php

class Model
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function countRows($table)
    {
        $query = "SELECT COUNT(*) as total FROM " . $table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function paginate($table, $page = 1, $resultsPerPage = 10,$columns = '*')
    {
        // Calculate the offset for the query
        $offset = ($page - 1) * $resultsPerPage;

        $query = "SELECT " . $columns . " FROM " . $table . " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $resultsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Get the total count of records
        $totalQuery = "SELECT COUNT(*) as total FROM " . $table;
        $totalStmt = $this->conn->prepare($totalQuery);
        $totalStmt->execute();
        $total = $totalStmt->fetch(PDO::FETCH_OBJ)->total;

        return [
            'pagination' => [
                'current_page' => $page,
                'per_page' => $resultsPerPage,
                'total' => $total,
                'total_pages' => ceil($total / $resultsPerPage)
            ],
            'result' => $results,
        ];
    }
}
