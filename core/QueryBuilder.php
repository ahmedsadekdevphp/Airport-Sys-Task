<?php
require_once '../core/Database.php';

class QueryBuilder
{

    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function find($table,$conditions,$columns = ['*'])
    {
        $sql = "SELECT " . implode(", ", $columns) . " FROM {$table}";
        $sql .= " WHERE ";
        $conditionClauses = [];
        foreach ($conditions as $column => $value) {
            $conditionClauses[] = "{$column} = :{$column}";
        }
        $sql .= implode(" AND ", $conditionClauses);

        $sql .= " LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        foreach ($conditions as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchByKey($columns, $tableName, $column, $value)
    {
        $query = "SELECT $columns FROM $tableName WHERE $column LIKE :value";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':value', '%' . $value . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteRecord($tableName, $id)
    {
        $query = "DELETE FROM " . $tableName . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO " . $table . " ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$key", $value, $paramType);
        }
        return $stmt->execute();
    }


    public function getAll($tableName, $columns = '*', $conditions = null)
    {
        $query = "SELECT $columns FROM $tableName";
        $whereClause = '';
        if ($conditions) {
            $whereClause = ' WHERE ';
            $conditionParts = [];
            foreach ($conditions as $column => $value) {
                $conditionParts[] = "$column = :where_$column";
            }
            $whereClause .= implode(' AND ', $conditionParts);
        }

        $query .= $whereClause;
        $stmt = $this->conn->prepare($query);
        if ($conditions) {
            foreach ($conditions as $column => $value) {
                $stmt->bindValue(":where_$column", $value, PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getTotalCount($table, $filters = [])
    {
        $totalQuery = "SELECT COUNT(*) as total FROM " . $table . " WHERE 1=1";
        $totalStmt = $this->conn->prepare($totalQuery);
        $params = [];

        // Append filters dynamically
        foreach ($filters as $field => $value) {
            if ($field === 'start_at' || $field === 'end_date') {
                continue;
            } else {
                $totalQuery .= " AND $field = :$field"; // Add filters
                $params[":$field"] = $value; // Store value for binding
            }
        }
        if (isset($filters['start_at'])) {
            $totalQuery  .= " AND created_at >= :start_at";
            $params[':start_at'] = $filters['start_at'];
        }
        if (isset($filters['end_date'])) {
            $totalQuery  .= " AND created_at <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }
        // Prepare the statement
        $totalStmt = $this->conn->prepare($totalQuery);

        // Bind parameters
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $totalStmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $totalStmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        $totalStmt->execute();
        $total = $totalStmt->fetch(PDO::FETCH_OBJ)->total;
        return $total;
    }

    public function paginate($table, $page = 1, $resultsPerPage = 10, $columns = '*', $filters = [], $sortBy = null)
    {
        // Calculate the offset for the query
        $offset = ($page - 1) * $resultsPerPage;

        $query = "SELECT " . $columns . " FROM " . $table . " WHERE 1=1";
        $params = [];
        foreach ($filters as $field => $value) {
            if ($field === 'start_at' || $field === 'end_date') {
                continue;
            } else {
                $query .= " AND $field = :$field";
                $params[":$field"] = $value; // Bind value
            }
        }
        if (isset($filters['start_at'])) {
            $query .= " AND created_at >= :start_at";
            $params[':start_at'] = $filters['start_at'];
        }
        if (isset($filters['end_date'])) {
            $query .= " AND created_at <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if ($sortBy) {
            $query .= " ORDER BY " . $sortBy;
        }
        $query .= " LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }
        $stmt->bindValue(':limit', $resultsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        // Fetch all results
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Get the total count of records
        $total = $this->getTotalCount($table, $filters);
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
