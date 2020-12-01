<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$mono_log_file = __DIR__ . '/' . MONO_LOG_FILE;
$mono_logger = new Logger('logger');
$mono_logger->pushHandler(new StreamHandler($mono_log_file, Logger::WARNING));

return $mono_logger;
