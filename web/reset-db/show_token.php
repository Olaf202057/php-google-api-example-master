<?php

use Google_Api\GoogleInfo;
use Google_Api\GoogleDbAuth;
use Logging\ErrorLog;

$init_php = dirname(dirname(__DIR__)) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(dirname(__DIR__)) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

$google_auth = GoogleDbAuth::googleDbAuthFactory($CONF_google_client_id_json, GoogleInfo::REDIRECT_URI_0, $CONF_logger);
$new_token = $google_auth->getToken();

print "Since Heroku has ephemeral files we need to reset<br> google-token.json = $new_token <br>";


