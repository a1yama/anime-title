<?php
/**
 * 共通bootstrap
 */

// autoloader
$classLoader = require __DIR__ . '/vendor/autoload.php';
$classLoader->add(__DIR__ . '/bin');

// application config
require __DIR__ . '/config/config.php';

// container
require_once __DIR__ . '/bin/Container.php';
$container = Container::getContainer();
$container['clock'] = function($c) {
    return new \Time\Clock();
};
$container['db.default'] = function ($c) {
    return \Db\DbalFactory::mysql(DATABASE_HOST, DATABASE_NAME, DATABASE_USER, DATABASE_PASS, 'utf8');
};
// 通常アプリログ
$container['logger.app'] = function($c) {
    $logfile = LOG_DIR.'/app.log';
    $logger = new \Monolog\Logger('app');
    $logHandler = new \Monolog\Handler\RotatingFileHandler($logfile, LOG_MAXDAYS, LOG_LEVEL, true, 0777);
    $logHandler->setFilenameFormat('{filename}_{date}', 'Ymd');
    $logger->pushHandler($logHandler);
    return $logger;
};

// Fatalエラーをlogging
\Monolog\ErrorHandler::register(Container::getLogger());

// エラーハンドラ
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    $titles = [
        E_ERROR => 'Fatal error',
        E_WARNING => 'Warning',
        E_NOTICE => 'Notice',
        E_STRICT => 'Strict standards',
        E_RECOVERABLE_ERROR => 'Catchable fatal error',
        E_DEPRECATED => 'Depricated',
        E_USER_ERROR => 'Fatal error (User)',
        E_USER_WARNING => 'Warning (User)',
        E_USER_NOTICE => 'Notice (User)',
        E_USER_DEPRECATED => 'Depricated (User)',
    ];
    $msg = sprintf('%s: %s in %s on line %d',
        (isset($titles[$errno])) ? $titles[$errno] : 'Unknown error',
        $errstr, $errfile, $errline
    );
    $trace = debug_backtrace();
    $stackTrace = Framework\PhpError::formatTrace(array_slice($trace, 2, count($trace)), DEBUG);
    if (!empty($stackTrace)) {
        $msg .= sprintf("\nStack trace:\n%s", implode("\n", $stackTrace));
    }

    $logger = Container::getLogger();
    if (E_RECOVERABLE_ERROR & $errno) {
        $logger->critical($msg);
    } else if ((E_ERROR | E_USER_ERROR) & $errno) {
        $logger->error($msg);
    } else if ((E_WARNING | E_USER_WARNING) & $errno) {
        $logger->warning($msg);
    } else if ((E_NOTICE | E_USER_NOTICE) & $errno) {
        $logger->notice($msg);
    } else if ((E_STRICT | E_DEPRECATED | E_USER_DEPRECATED)) {
        $logger->notice($msg);
    } else {
        $logger->error($msg);
    }

    // デフォルトのエラーハンドラへ引継ぎ
    return false;
});

// 例外ハンドラ
set_exception_handler(function (\Exception $e) {
    $msg = sprintf("Fatal error: Uncaught exception '%s' with message '%s' in %s:%d\nStack trace:\n%s\n  thrown in %s on line %d",
        get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(),
        implode("\n", Framework\PhpError::formatTrace($e->getTrace(), DEBUG)),
        $e->getFile(), $e->getLine());
    Container::getLogger()->error($msg);
    echo $msg, "\n";
    exit(255);
});

function formatTraceAsString($arrTrace)
{
    return Framework\PhpError::formatTraceAsString($arrTrace, DEBUG);
}
