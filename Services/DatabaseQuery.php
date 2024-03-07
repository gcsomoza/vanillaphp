<?php
namespace vanillaphp\Services;

class DatabaseQuery {
    public function __construct(private $query) {}

    public function result() {
        $rows = [];
        while($object = $this->query->fetch_object()) {
            $rows[] = $object;
        }
        return $rows;
    }

    public function row() {
        return $this->query->fetch_object();
    }
}