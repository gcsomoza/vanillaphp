<?php
namespace vanillaphp\Services;

class Database {
    private static $db;

    public function __construct($host, $user, $pass, $name) {
        self::$db = new \mysqli($host, $user, $pass, $name);

        if (self::$db->connect_errno) {
            throw new \Exception("Failed to connect to MySQL: " . self::$db->connect_error);
        }
    }

    public function query($sql, $params = []) {
        $stmt = self::$db->prepare($sql);
        if ( count($params) > 0 )
            $stmt->bind_param(implode('', array_fill(0, count($params), 's')), ...$params);
        $stmt->execute();
        return new DatabaseQuery($stmt->get_result());
    }

    public function insert($table, $data = []) {
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
        $sql = "INSERT INTO `$table` ($fields) VALUES($placeholders)";
        $this->query($sql, $params);
        return $this->insertID();
    }

    public function update($table, $data = [], $where = []) {
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
        $sql = "UPDATE `$table` SET $fields WHERE $whereClause";
        $this->query($sql, $params);
    }

    public function delete($table, $where = []) {
        $params = [];
        $whereClause = [];
        foreach ($where as $field => $value) {
            $whereClause[] = "`$field` = ?";
            $params[] = $value;
        }
        $whereClause = implode(' AND ',$whereClause);
        $sql = "DELETE FROM `$table` WHERE $whereClause";
        $this->query($sql, $params);
    }

    public function insertID() {
        return self::$db->insert_id;
    }
}