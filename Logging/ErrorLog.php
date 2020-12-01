<?php
namespace Logging;

use Google_Api\GoogleEmail;


class ErrorLog
{
    const EMAIL_FILE_LOGGER = ['Logging\ErrorLog', 'emailLogger'];
    const SCREEN_FILE_LOGGER = ['Logging\ErrorLog', 'screenLogger'];
    const FILE_LOGGER = ['Logging\ErrorLog', 'fileLogger'];

    static function errorText($error_mess)
    {
        $styled_error = "<br><span style='color:red'>Error: $error_mess</span><br>";
        return $styled_error;
    }

    static function screenLogger($message_logger, $error_message, $google_client_id_json)
    {
        echo "<br> [[[--- ERROR - $error_message ---]]]<br>";
        ErrorLog::fileLogger($message_logger, $error_message, $google_client_id_json);
    }

    static function fileLogger($message_logger, $error_message, $google_client_id_json)
    {
        $message_logger->addError($error_message);
    }

    static function emailLogger($message_logger, $error_message, $google_client_id_json, $to_email)
    {
        ErrorLog::fileLogger($message_logger, $error_message, $google_client_id_json);
        GoogleEmail::emailAnError($to_email, $error_message, $google_client_id_json, $message_logger);
    }

}



