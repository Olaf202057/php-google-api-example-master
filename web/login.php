<?php

use Google_Api\GoogleHtml;
use Logging\ErrorLog;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

$meta_google_content = GoogleHtml::metaGoogleContent($CONF_google_client_id_json);
$google_signin = GoogleHtml::googleSignIn('/signInFromGoogle.php');
$login_html = <<< EOS
    $meta_google_content
    $google_signin
EOS;

echo $login_html;



