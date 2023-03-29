<?php
namespace Od\Utils;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class LoggerFactory
{
    const DEFAULT_LOG_FORMAT = "[%datetime%] %message%\n";

    public function __construct($logger_type = 'stdout')
    {
        $this->initLogger();

        switch ($logger_type) {
            case "file":
                throw new RuntimeException("logger type {$logger_type} not yet implemented");

            case "stdout":
            default:
                $logger = $this->initStdoutLogger;
        }

        return $logger;
    }

    private function initStdoutLogger()
    {
        $formatter = new LineFormatter(DEFAULT_LOG_FORMAT);

        $streamHandler = new StreamHandler('php://stdout', Logger::DEBUG);
        $streamHandler->setFormatter($formatter);

        $logger = new Logger('Logger');
        $logger->pushHandler($streamHandler);

        return $logger;
    }
}
