<?php


  define("GOOGLE_CONFIG", "google-account");

use Google_Api\GoogleInfo;
use Logging\ErrorRoutines;

require_once __DIR__ . "/common/autoload_gapi.php";

function init_environment($google_credentials_path, $error_log_type)
{
$google_config_file = "$google_credentials_path/config_files.php";
    require_once $google_config_file;

    $user_spreadsheet_document = USER_SPREADSHEET_DOCUMENT;
    $manual_doc_document = MANUAL_DOC_DOCUMENT;
    $email_errors_to = EMAIL_ERRORS_TO;
    $logger = require_once __DIR__ . "/Logging/mono_log.php";
    $google_client_id_json = "$google_credentials_path/" . GoogleInfo::CLIENT_ID_FILE;
    $error_routines = new ErrorRoutines($logger, $error_log_type, $google_client_id_json, EMAIL_ERRORS_TO);
    $template_path = __DIR__ . "/templates/";
    $results = compact("logger", "error_routines", "google_credentials_path", "google_client_id_json",
        "user_spreadsheet_document", "manual_doc_document", "email_errors_to", "template_path");
    return $results;
}
