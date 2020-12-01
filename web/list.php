<?php 

use common\CommonRoutines;
use Google_Api\UserMenu;
use Google_Api\UserTasks;
use Google_Api\UsersSheet;
use Logging\ErrorLog;


$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_CONFIG;                              
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');


CommonRoutines::homeIfNotLoggedIn();


$user_menu = new UserMenu($CONF_user_spreadsheet_document, $_SESSION['USER_GMAIL'], $CONF_google_client_id_json, $CONF_logger);


$page_html = $user_menu->normalMenu('/list.php', "$CONF_template_path/menu.html");
try {
    $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
    $user_list_html = $user_tasks->userList($CONF_user_spreadsheet_document, UsersSheet::COLUMN_LAST_NAME, $_SESSION['USER_GMAIL']);
    $page_html .= $user_list_html;
} catch (Exception $e) {
    $page_html .= CommonRoutines::errorText("list.php Cannot read user database - " . $e->getMessage());
}
$entire_page = CommonRoutines::pageTemplate($page_html, "$CONF_template_path/page.html");
echo $entire_page;
