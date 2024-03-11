<?php
namespace vanillaphp\Services;

class DatabaseQuery {
    public function __construct(private $query) {}

    public function result($ModelClass = null) {
        $rows = [];
        while($object = $this->query->fetch_object()) {
            $rows[] = $ModelClass === null ? $object : new $ModelClass($object);
        }
        return $rows;
    }

    public function row() {
        return $this->query->fetch_object();
    }
}