<?php
namespace Od\Tests\Utils;

use Od\Utils\LoggerFactory;
use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TestHandler;

class LoggerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateStdoutLogger()
    {
        $loggerFactory = new LoggerFactory();
        $logger = $loggerFactory->createStdoutLogger();

        $this->assertInstanceOf('Monolog\Logger', $logger);

        $handler = $logger->popHandler();
        $this->assertInstanceOf('Monolog\Handler\StreamHandler', $handler);
        $this->assertEquals('php://stdout', $handler->getUrl());
        $this->assertEquals(Logger::DEBUG, $handler->getLevel());

        $formatter = $handler->getFormatter();
        $this->assertInstanceOf('Monolog\Formatter\LineFormatter', $formatter);
        $this->assertEquals("Y-m-d H:i:s", $formatter->getDateFormat());
    }
}
