<?php

use Google_Api\UserTasks;
use Logging\ErrorLog;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

$user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
try {
    $_SESSION['USER_GMAIL'] = $user_tasks->userSignIn($CONF_user_spreadsheet_document, $_GET['gmail']);
    header("Location: /list.php");
} catch (Exception $e) {
    print $e->getMessage();
}


