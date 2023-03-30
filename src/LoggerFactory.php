<?php
namespace Od\Utils;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class LoggerFactory
{
    const DEFAULT_LOG_FORMAT = "[%datetime%] %message%\n";
    const DEFAULT_DATETIME_FORMAT = "Y-m-d H:i:s";

    public function __construct()
    {
    }

    public function getLogger($logger_type = 'stdout')
    {
        switch ($logger_type) {
            case "file":
                throw new RuntimeException("{$logger_type} not yet implemented");

            case "stdout":
            default:
                $logger = $this->initStdoutLogger();
        }

        return $logger;
    }

    private function initStdoutLogger()
    {
        $formatter = new LineFormatter(self::DEFAULT_LOG_FORMAT, self::DEFAULT_DATETIME_FORMAT);

        $streamHandler = new StreamHandler('php://stdout', Logger::DEBUG);
        $streamHandler->setFormatter($formatter);

        $logger = new Logger('Logger');
        $logger->pushHandler($streamHandler);

        return $logger;
    }
}
