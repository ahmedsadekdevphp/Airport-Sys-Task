<?php
require_once  '../app/services/Helpers.php';

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $data[$field] ?? null, $rule);
            }
        }
        if ($this->errors) {
            Response::jsonResponse(["status" => HTTP_UNPROCESSABLE_ENTITY, "data" =>$this->errors]);
        }
    }

    private function applyRule($field, $value, $rule)
    {
        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->errors[$field][] = $field . ' ' . trans('required_validation');
                }
                break;
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = $field . ' ' . trans('valid_email');
                }
                break;
            case 'password':
                if (is_null($value) || strlen((string) $value) < 8) {
                    $this->errors[$field][] = $field . ' ' . trans('passsword_length');
                }

            default:
                if (strpos($rule, 'unique:') === 0) {
                    $table = str_replace('unique:', '', $rule); // Extract the table name
                    if ($this->isExists($table, $field, $value)) {
                        $this->errors[$field][] = $field . ' ' . trans('unique_field');
                    }
                    break;
                }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    private function isExists($table, $field, $value)
    {
        $db = new Database();
        $query = "SELECT COUNT(*) FROM $table WHERE $field = :value";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        // Get the count of matching records
        $count = $stmt->fetchColumn();
        return $count > 0; // Return true if the value exists, false otherwise
    }
}
