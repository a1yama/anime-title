<?php
/**
 * Pimpleのコンテナをstaticアクセスするためだけに存在
 * IDE-friendly を目的に、頻繁に使うオブジェクトへのgetterも備える
 */
class Container {
    /** @var \Pimple\Container */
    static private $container;

    /**
     * @return \Pimple\Container
     */
    static public function getContainer() {
        if(self::$container === null) {
            self::$container = new \Pimple\Container();
        }
        return self::$container;
    }


    // 以下、頻繁に使うオブジェクトへのショートカット
    /**
     * @return \Db\Dbal
     */
    static public function getDbal() {
        return self::$container['db.default'];
    }
    /**
     * @return \Time\Clock
     */
    static public function getClock() {
        return self::$container['clock'];
    }
    /**
     * @return \Psr\Log\LoggerInterface
     */
    static public function getLogger() {
        return self::$container['logger.app'];
    }
}