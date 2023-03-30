<?php
require_once __DIR__ . "/vendor/autoload.php";

use Od\Utils\LoggerFactory;

$loggerFactory = new LoggerFactory();
$logger = $loggerFactory->getLogger('stdout');
$logger->info('test');
