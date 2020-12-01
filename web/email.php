<?php

use common\CommonRoutines;
use Logging\ErrorLog;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

CommonRoutines::homeIfNotLoggedIn();

ErrorLog::emailLogger($CONF_logger, 'email.php test call', $CONF_google_client_id_json, $CONF_email_errors_to);

