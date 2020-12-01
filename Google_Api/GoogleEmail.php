<?php
namespace Google_Api;

use common\CommonRoutines;

class GoogleEmail
{

    static function emailAnError($to_email, $email_message, $google_client_id_json, $message_logger = [])
    {
        $google_info = new GoogleInfo($google_client_id_json);
        $project_id = $google_info->projectId();
        $email_subject = "Error recorded in $project_id";
        GoogleEmail::sendErrorEmail($to_email, $email_subject, $email_message, $google_client_id_json, $message_logger);
    }

    static function sendErrorEmail($to_email, $email_subject, $email_message, $google_client_id_json, $message_logger = [])
    {
        if (isset($_SESSION['SENDING_ERROR_EMAIL']) && $_SESSION['SENDING_ERROR_EMAIL']) {
            return;
        }
        $_SESSION['SENDING_ERROR_EMAIL'] = true;
        $google_auth = GoogleDbAuth::googleDbAuthFactory($google_client_id_json, GoogleInfo::REDIRECT_URI_0, $message_logger);
        $google_info = new GoogleInfo($google_client_id_json);
        $from_email = $google_info->databaseGmailAccount();
        $google_client = $google_auth->clientAfterAuthorization();
        if ($google_client) {
            $google_gmail_service = new \Google_Service_Gmail($google_client);
            $mess_parts = [];
            $mess_parts [] = "From: PHP Heroku<$from_email>";
            $mess_parts [] = "To: $to_email";
            $mess_parts [] = 'Subject: =?utf-8?B?' . base64_encode($email_subject) . "?=";
            $mess_parts [] = "MIME-Version: 1.0";
            $mess_parts [] = "Content-Type: text/html; charset=utf-8";
            $mess_parts [] = "Content-Transfer-Encoding: quoted-printable\r\n";
            $mess_parts [] = $email_message;
            $raw_message = implode("\r\n", $mess_parts);
            $message_64 = CommonRoutines::base64Encode($raw_message);
            $google_message = new \Google_Service_Gmail_Message();
            $google_message->setRaw($message_64);
            try {
                $google_gmail_service->users_messages->send("me", $google_message);
            } catch (Exception $e) {
                $error_message = $e->getMessage() . " GoogleEmail->sendErrorEmail";
                if (is_callable($message_logger)) {
                    call_user_func($message_logger, $error_message);
                }
            }
        }
        $_SESSION['SENDING_ERROR_EMAIL'] = false;
    }

    public function __toString()
    {
        $str = PHP_EOL . 'GoogleEmail' . PHP_EOL;
        return $str;
    }
    
}
