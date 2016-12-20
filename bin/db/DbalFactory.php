<?php
namespace Db;

class DbalFactory {
    static public function getPdoOptions() {
        return array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_AUTOCOMMIT => false,

            \PDO::ATTR_EMULATE_PREPARES => true,
            \PDO::ATTR_CASE, \PDO::CASE_LOWER,
            //\PDO::MYSQL_ATTR_READ_DEFAULT_FILE => '/etc/my.cnf',
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        );
    }
    static public function mysql($host, $dbname, $user, $pw, $charset=null) {
        $strDsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $dbname, $charset);
        $pdo = new \PDO($strDsn, $user, $pw, self::getPdoOptions());
        return new Dbal($pdo);
    }
}
