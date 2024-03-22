<?php
namespace vanillaphp\Services;

use vanillaphp\Services\Units\DatabaseUnit;

class Database {
    use DatabaseUnit;

    private static $db;

    public function __construct($host, $user, $pass, $name) {
        self::$db = new \mysqli($host, $user, $pass, $name);

        if (self::$db->connect_errno) {
            throw new \Exception("Failed to connect to MySQL: " . self::$db->connect_error);
        }
    }

    public function query($sql, $params = []) {
        $stmt = self::$db->prepare($sql);
        if ( count($params) > 0 ) {
            $placeholders = $this->_bindParamPlaceholders($params);
            $values = array_values($params);
            $stmt->bind_param($placeholders, ...$values);
        }
        $stmt->execute();
        return new DatabaseQuery($stmt->get_result());
    }

    public function insert($table, $data = []) {
        $sql = $this->_insert($table, $data);
        $this->query($sql, $data);
        return $this->insertID();
    }

    public function update($table, $data = [], $where = []) {
        $sql = $this->_update($table, $data, $where);
        $this->query($sql, array_merge($data, $where));
    }

    public function delete($table, $where = []) {
        $sql = $this->_delete($table, $where);
        $this->query($sql, $params);
    }

    public function insertID() {
        return self::$db->insert_id;
    }
}