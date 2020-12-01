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
$page_html = $user_menu->normalMenu('/manual.php', "$CONF_template_path/menu.html");



try {
    print "Private Google document <a href='$CONF_manual_doc_document'>$CONF_manual_doc_document</a><br>";
    $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
    $document_html = $user_tasks->showDocument($CONF_user_spreadsheet_document, $CONF_manual_doc_document, UsersSheet::COLUMN_MANUAL, $_SESSION['USER_GMAIL']);
    if ($document_html) {
        $page_html .= $document_html;
    } else {
        $page_html .=("You ain't on the Manual List");
    }
} catch (Exception $e) {
    $page_html .= CommonRoutines::errorText("Cannot read user database");

}
$entire_page = CommonRoutines::pageTemplate($page_html, "$CONF_template_path/page.html");
echo $entire_page;
