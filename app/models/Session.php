<?php
require_once '../core/Model.php';

class Session extends Model
{
    private $table_name = "airport_sessions";

    public function getUserActionLimit($userID, $action)
    {

        $conditions = [
            'user_id' => $userID,
            'action' => $action
        ];
        $data = $this->QueryBuilder->find($this->table_name, $conditions);
        return $data;
    }

    public function getLastActivityLimit($ip, $action)
    {
        $conditions = [
            'ip' => $ip,
            'action' => $action
        ];
        $data = $this->QueryBuilder->find($this->table_name, $conditions);
        return $data;
    }

    public function updateSession($count, $id, $last_action)
    {
        $fields = [
            'last_action' => $last_action,
            'count' => $count,
        ];
        $conditions = [
            'id' => $id
        ];
        $this->QueryBuilder->updateFields($this->table_name, $fields, $conditions);
    }

    public function addSession($ip,$action,$userId = null)
    {
        $now = new DateTime();
        $this->QueryBuilder->insert($this->table_name, [
            'ip' => $ip,
            'user_id' => $userId,
            'action' => $action,
            'last_action' => $now->format('Y-m-d H:i:s'),
        ]);
    }
}
