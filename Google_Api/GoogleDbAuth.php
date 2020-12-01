<?php
namespace Google_Api;

//     https://security.google.com/settings/security/permissions

class GoogleDbAuth
{
    const TOKEN_FILENAME = 'google-token.json';
    const AUTH_DRIVE = 'https://www.googleapis.com/auth/drive';
    const AUTH_SPREADSHEETS = 'https://www.googleapis.com/auth/spreadsheets';
    const AUTH_GMAIL = 'https://www.googleapis.com/auth/gmail.send';
    const GOOGLE_LOGOUT = 'https://accounts.google.com/logout';

    function __construct($parameter_list, $message_logger = [])
    {
        $this->api_key = $parameter_list['api_key'];
        $this->client_id_file = $parameter_list['client_id_file'];
        $this->token_folder = $parameter_list['token_folder'];
        $this->redirect_uri = $parameter_list['redirect_uri'];
        $this->config_path = $this->token_folder . '/' . $this->client_id_file;
        $this->token_path = $this->token_folder . '/' . self::TOKEN_FILENAME;
        $this->google_client = new \Google_Client();
        $this->message_logger = $message_logger;
    }

    public function __toString()
    {
        $str = PHP_EOL . 'GoogleDbAuth' . PHP_EOL;
        return $str;
    }

    function userAuthorization()
    {
        $this->google_client->setDeveloperKey($this->api_key);
        $this->google_client->setAuthConfigFile($this->config_path);
        $this->google_client->setAccessType("offline");
    }

    function clientAfterAuthorization()
    {
        $token_str = file_get_contents($this->token_path);
        try {
            $this->google_client->setAccessToken($token_str);
        } catch (Exception $e) {         //  'invalid json token' or "Invalid token format"
            $_SESSION['LOST_DATABASE'] = true;
            $error_message = $e->getMessage() . " GoogleDbAuth->clientAfterAuthorization setAccessToken()";
            if (is_callable($this->message_logger)) {
                call_user_func($this->message_logger, $error_message);
            }
            return false;
        }
        if ($this->google_client->isAccessTokenExpired()) {
            $this->google_client->setAuthConfigFile($this->config_path);
            try {
                $this->google_client->fetchAccessTokenWithRefreshToken();
            } catch (Exception $e) {
                $_SESSION['LOST_DATABASE'] = true;
                $error_message = $e->getMessage() . " GoogleDbAuth->clientAfterAuthorization fetchAccessTokenWithRefreshToken()";
                if (is_callable($this->message_logger)) {
                    call_user_func($this->message_logger, $error_message);
                }
                return false;
            }
        }
        return $this->google_client;
    }

    function initialAuthorization($get_code)
    {
        $authenticated_token = $this->google_client->authenticate($get_code);
        $token_str = json_encode($authenticated_token);
        file_put_contents($this->token_path, $token_str);
        if (strpos($token_str, 'refresh_token') === false) {
            return false;
        }
        return true;
    }

    function callGoogle()
    {
        $this->google_client->setRedirectUri($this->redirect_uri);
        $this->google_client->setScopes([self::AUTH_SPREADSHEETS
            , self::AUTH_DRIVE
            , self::AUTH_GMAIL]);
        $auth_url = $this->google_client->createAuthUrl();
        $sanitized_url = filter_var($auth_url, FILTER_SANITIZE_URL);
        return $sanitized_url;
    }

    function revokeToken()
    {
        $token_str = file_get_contents($this->token_path);
        $token_array = json_decode($token_str, true);
        if (is_array($token_array)) {
            $this->google_client->revokeToken($token_array);
        }
    }

    function getToken()
    {
        $token_str = file_get_contents($this->token_path);
        return $token_str;
    }

    static function callbackRedirect($callback_file)
    {
        $redirect_location = filter_var($callback_file, FILTER_SANITIZE_URL);
        return $redirect_location;
    }

    static function googleDbAuthFactory($client_id_json_file, $redirect_uri_number, $message_logger = [])
    {
        $google_info = new GoogleInfo($client_id_json_file);
        $api_key = $google_info->apiKey();
        $client_id_file = $google_info->clientIdFile();
        $token_folder = $google_info->tokenFolder();
        $redirect_uri = $google_info->redirectUri($redirect_uri_number);
        $auth_list = ['api_key' => $api_key, 'client_id_file' => $client_id_file, 'token_folder' => $token_folder, 'redirect_uri' => $redirect_uri];
        $google_auth = new GoogleDbAuth($auth_list, $message_logger);
        return $google_auth;
    }


}

