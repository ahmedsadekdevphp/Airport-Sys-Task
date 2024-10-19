<?php
class QueryBuilder
{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function updateFields($tableName, $fields, $conditions = null)
    {
        $setClause = [];
        foreach ($fields as $column => $value) {
            $setClause[] = "$column = :$column"; 
        }
        $setClause = implode(', ', $setClause);

        $whereClause = '';
        if ($conditions) {
            $whereClause = ' WHERE ';
            $conditionParts = [];
            foreach ($conditions as $column => $value) {
                $conditionParts[] = "$column = :where_$column"; 
            }
            $whereClause .= implode(' AND ', $conditionParts);
        }

        // Final SQL query
        $query = "UPDATE $tableName SET $setClause" . $whereClause;
        $stmt = $this->conn->prepare($query);

        // Bind the field values
        foreach ($fields as $column => $value) {
            $stmt->bindValue(":$column", $value, PDO::PARAM_STR); 
        }

        // Bind the condition values if any
        if ($conditions) {
            foreach ($conditions as $column => $value) {
                $stmt->bindValue(":where_$column", $value, PDO::PARAM_STR);
            }
        }
        return $stmt->execute(); 
    }


    public function countRows($table)
    {
        $query = "SELECT COUNT(*) as total FROM " . $table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->total;
    }

    public function paginate($table, $page = 1, $resultsPerPage = 10, $columns = '*')
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
