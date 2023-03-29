<?php
require_once __DIR__ . "/LoggerFactory.php";

use Od\Utils\LoggerFactory;

$loggerFactory = new LoggerFactory();
$logger = $loggerFactory->getLogger('stdout');
$logger->info(test);
