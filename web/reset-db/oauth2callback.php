<?php

use Google_Api\UserTasks;
use Google_Api\GoogleDbAuth;
use Logging\ErrorLog;

$init_php = dirname(dirname(__DIR__)) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(dirname(__DIR__)) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

$user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
$user_tasks->googleCallBack(GoogleDbAuth::GOOGLE_LOGOUT);








