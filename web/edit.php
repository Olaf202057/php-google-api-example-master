<?php

use common\CommonRoutines;
use Google_Api\UserMenu;
use Google_Api\UserTasks;
use Google_Api\GoogleHtml;
use Logging\ErrorLog;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

CommonRoutines::homeIfNotLoggedIn();

if (isset($_GET['update'])) {
    if ($_GET['update'] === 'error') {
        echo ErrorLog::errorText("Cannot save to user database");
    }
}

$user_menu = new UserMenu($CONF_user_spreadsheet_document, $_SESSION['USER_GMAIL'], $CONF_google_client_id_json, $CONF_logger);
$page_html = $user_menu->normalMenu('/edit.php', "$CONF_template_path/menu.html");

try {
    $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
    $edit_values = $user_tasks->editUser($CONF_user_spreadsheet_document, $_SESSION['USER_GMAIL']);
    $edit_form = GoogleHtml::editForm($edit_values, '/update.php', "$CONF_template_path/edit.html");
    $page_html .= $edit_form;
    $page_html .= "<br><br>$CONF_user_spreadsheet_document<br><br>";
    $page_html .= "<iframe src='$CONF_user_spreadsheet_document' width='800' height='400'></iframe>";

} catch (Exception $e) {
    $page_html .= ErrorLog::errorText("Cannot read user database");
}
$entire_page = CommonRoutines::pageTemplate($page_html, "$CONF_template_path/page.html");
echo $entire_page;
