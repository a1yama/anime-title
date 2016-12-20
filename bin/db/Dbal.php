<?php
namespace Db;

/**
 * PDO をさらに抽象化したもの
 */
class Dbal {
    /** @var \PDO */
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * @param string $sql
     * @param string[]|int[] $values
     * @return array 複数行
     */
    public function query($sql, $values=array()) {
        $stmt = $this->prepare($sql, $values);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    /**
     * @param string $sql
     * @param string[]|int[] $values
     * @return array|null 単一行
     */
    public function queryOne($sql, $values=array()) {
        $stmt = $this->prepare($sql, $values);
        $stmt->execute();
        $firstRow = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($firstRow === false) {
            return null;
        }
        return $firstRow;
    }
    /**
     * @param string $sql
     * @param string[]|int[] $values
     * @return \PDOStatement
     */
    public function queryAsStmt($sql, $values=array()) {
        $stmt = $this->prepare($sql, $values);
        $stmt->execute();
        return $stmt;
    }
    /**
     * @param string $sql
     * @param string[]|int[] $values
     * @return int 更新件数
     */
    public function execute($sql, $values=array()) {
        $stmt = $this->prepare($sql, $values);
        $stmt->execute();
        return $stmt->rowCount();
    }
/**
     * @param string $sql
     * @param mixed[] $values
     * @return \PDOStatement
     */
    protected function prepare($sql, $values=array()) {
        $stmt = $this->pdo->prepare($sql);
        foreach($values as $i=>$val) {
            $pi = $i+1;
            if(is_int($val)) {
                $stmt->bindValue($pi, $val, \PDO::PARAM_INT);
            } else if(is_bool($val)) {
                $stmt->bindValue($pi, $val, \PDO::PARAM_BOOL);
            } else if(is_null($val)) {
                $stmt->bindValue($pi, $val, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue($pi, $val, \PDO::PARAM_STR);
            }
        }
        return $stmt;
    }
    /**
     * @param  string $sql
     * @param  array  $values
     * @return \PDOStatement
     */
    protected function prepareName($sql, $values=array()) {
        $stmt = $this->pdo->prepare($sql);
        foreach($values as $name=>$val) {
            if(is_int($val)) {
                $stmt->bindValue($name, $val, \PDO::PARAM_INT);
            } else if(is_bool($val)) {
                $stmt->bindValue($name, $val, \PDO::PARAM_BOOL);
            } else if(is_null($val)) {
                $stmt->bindValue($name, $val, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue($name, $val, \PDO::PARAM_STR);
            }
        }
        return $stmt;
    }    
    function begin() {
        return $this->pdo->beginTransaction();
    }
    function rollback() {
        return $this->pdo->rollBack();
    }
    function commit() {
        return $this->pdo->commit();
    }
    function close() {
        $this->pdo = null;
    }
}