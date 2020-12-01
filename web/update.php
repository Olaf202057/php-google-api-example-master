<?php

use common\CommonRoutines;
use Google_Api\UserTasks;
use Logging\ErrorLog;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

CommonRoutines::homeIfNotLoggedIn();

$edit_location = '/edit.php';
try {
    $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);

    $first_name = filter_var($_POST['first-name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last-name'], FILTER_SANITIZE_STRING);


    $user_data = compact('first_name', 'last_name');

    $user_tasks->updateUser($CONF_user_spreadsheet_document, $_SESSION['USER_GMAIL'], $user_data);
    header("Location: $edit_location");
} catch (Exception $e) {
    header("Location: $edit_location?update=error");
}
