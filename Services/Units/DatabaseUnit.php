<?php
namespace vanillaphp\Services\Units;

trait DatabaseUnit {
    public function _bindParamPlaceholders($params = []) {
        return implode('', array_fill(0, count($params), 's'));
    }

    public function _insert($table, $data = []) {
        $fields = [];
        $placeholders = [];
        $params = [];
        foreach ($data as $field => $value) {
            $fields[] = "`$field`";
            $placeholders[] = '?';
            $params[] = $value;
        }
        $fields = implode(',',$fields);
        $placeholders = implode(',',$placeholders);
        return "INSERT INTO `$table` ($fields) VALUES($placeholders)";
    }

    public function _update($table, $data = [], $where = []) {
        $fields = [];
        $params = [];
        foreach ($data as $field => $value) {
            $fields[] = "`$field` = ?";
            $params[] = $value;
        }
        $fields = implode(',',$fields);
        $whereClause = [];
        foreach ($where as $field => $value) {
            $whereClause[] = "`$field` = ?";
            $params[] = $value;
        }
        $whereClause = implode(' AND ',$whereClause);
        return "UPDATE `$table` SET $fields WHERE $whereClause";
    }

    public function _delete($table, $where = []) {
        $params = [];
        $whereClause = [];
        foreach ($where as $field => $value) {
            $whereClause[] = "`$field` = ?";
            $params[] = $value;
        }
        $whereClause = implode(' AND ',$whereClause);
        return "DELETE FROM `$table` WHERE $whereClause";
    }
}