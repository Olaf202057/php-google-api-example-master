<?php

use Google_Api\GoogleDbAuth;
use Logging\ErrorLog;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;

$google_config_file = dirname(__DIR__) . "/" . GOOGLE_CONFIG;
init_environment($google_config_file, ErrorLog::SCREEN_FILE_LOGGER);

session_start();
session_unset();
$google_logout = GoogleDbAuth::GOOGLE_LOGOUT;
$close_google = <<< EOS
        <script>
              window.open('/', '_blank')
            window.location = '$google_logout'
        </script>


EOS;

echo $close_google;





