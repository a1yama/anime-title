<?php
namespace Dao;

abstract class Base {
    /** @var \db\Dbal */
    protected $db;

    public function __construct(\Db\Dbal $dbal) {
        $this->db = $dbal;
    }

    public function getDbal() {
        return $this->db;
    }
    public function begin() {
        return $this->db->begin();
    }
    public function commit() {
        return $this->db->commit();
    }
    public function rollback() {
        return $this->db->rollback();
    }
}