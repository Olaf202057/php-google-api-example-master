<?php
namespace Logging;

class ErrorRoutines
{
    const LOST_DB_CON = 'Lost Database connection';

    function __construct($mono_logger, $log_to_function, $google_client_id_json, $error_to_email)
    {
        error_reporting(E_ALL);
        error_reporting(0);
        $this->message_logger = $mono_logger;
        $this->log_to_function = $log_to_function;
        $this->google_client_id_json = $google_client_id_json;
        $this->error_to_email = $error_to_email;
        $this->old_error_handler = set_error_handler([$this, 'myErrorHandler']);
        register_shutdown_function([$this, 'myFatalLogger']);
    }

    function myErrorHandler($err_no, $err_str, $err_file, $err_line)
    {
        switch ($err_no) {
            case E_USER_ERROR:
                $error_message = "ERROR [$err_no] $err_str Fatal error on line $err_line in file $err_file. Aborted";
                if (is_callable($this->log_to_function)) {
                    call_user_func_array($this->log_to_function, array($this->message_logger, $error_message, $this->google_client_id_json, $this->error_to_email));
                }
                exit(1);
                break;

            case E_USER_WARNING:
                $error_message = "WARNING [$err_no] $err_str on line $err_line in file $err_file. Aborted";
                break;

            case E_USER_NOTICE:
                $error_message = "NOTICE [$err_no] $err_str on line $err_line in file $err_file. Aborted";
                break;

            default:
                $error_message = "Unknown error type: [$err_no] $err_str on line $err_line in file $err_file. Aborted";
                break;
        }
        if (is_callable($this->log_to_function)) {
            call_user_func_array($this->log_to_function, array($this->message_logger, $error_message, $this->google_client_id_json, $this->error_to_email));
        }
        return true;   // Don't execute PHP internal error handler
    }

    function myFatalLogger()
    {
        $error_info = error_get_last();
        if ($error_info !== NULL) {
            $err_no = $error_info["type"];
            $err_str = $error_info["message"];
            $err_file = $error_info["file"];
            $err_line = $error_info["line"];
            $error_message = "FATAL ERROR [$err_no] $err_str Fatal error on line $err_line in file $err_file. Aborted";
           
       
           
            if (is_callable($this->message_logger)) {
                call_user_func_array($this->log_to_function, array($this->message_logger, $error_message, $this->google_client_id_json, $this->error_to_email));
            }
        }
    }

}
