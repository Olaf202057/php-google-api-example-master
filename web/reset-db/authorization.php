<?php

//    https://google-api-example.herokuapp.com/reset-db/authorization.php

use Google_Api\GoogleInfo;
use Google_Api\GoogleDbAuth;
use Logging\ErrorLog;

$init_php = dirname(dirname(__DIR__)) . "/initEnvironment.php";
require_once $init_php;

$google_config_dir = dirname(dirname(__DIR__)) . "/" . GOOGLE_CONFIG;
$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');

$google_info = new GoogleInfo($CONF_google_client_id_json);
$auth_gmail_account = $google_info->databaseGmailAccount();

$oauth2callback = $google_info->redirectUri(GoogleInfo::REDIRECT_URI_0);
$google_logout = GoogleDbAuth::GOOGLE_LOGOUT;
$close_google = <<< EOS

    <div id='first_instruction'>
        Instructions to re-connect the Google-Api-Example Google Database. A new window will open where the steps below must be followed.<br>
        You will have to flip back and forth between this and the new window.<br>
        <button onclick=" document.getElementById('second_instruction').style.display='block'
                          google_window= window.open('$google_logout','_blank')

                         console.log(google_window)

                         ">Step A, open new window</button><br>
        <img src="step_1_reset_log_in.jpg">
    </div>

    <div id='second_instruction' style="display: none;">
        <button onclick=" document.getElementById('third_instruction').style.display='block'

                         ">Show Step B</button><br>
  </div>

<div id='third_instruction' style="  display: none;">
 <img src="step_2_manage_apps.jpg"><br>
      <button onclick=" document.getElementById('fourth_instruction').style.display='block'

                         ">Show Step C</button>

  </div>

<div id='fourth_instruction' style="  display: none;">
 <img src="step_3_remove.jpg"><br>
      <button onclick=" document.getElementById('fifth_instruction').style.display='block'

                         ">Show Step D</button>

  </div>


<div id='fifth_instruction' style="  display: none;">
 <img src="step_4_confirm.jpg"><br>
      <button onclick=" document.getElementById('sixth_instruction').style.display='block'

                         ">Show Step E</button>

  </div>





<div id='sixth_instruction' style="  display: none;">
 <img src="step_5_allow.jpg"><br>
      <button onclick="
       document.getElementById('seventh_instruction').style.display='block'
      google_window.location ='https://google-api-example.herokuapp.com/reset-db/oauth2callback.php'

                         ">Step F, open new window for step 7</button>

  </div>




<div id='seventh_instruction' style="  display: none;">
      <button onclick="
         window.location ='/reset-db/show_token.php'
                         ">Finish and Show new Token for Git</button>

  </div>

EOS;


echo $close_google;
