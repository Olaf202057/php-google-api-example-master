<?php
namespace Google_Api;

class GoogleInfo
{
    const CLIENT_ID_FILE = 'client-id.json';

    const REDIRECT_URI_0 = 0;
    const REDIRECT_URI_1 = 1;
    const REDIRECT_URI_2 = 2;

    function __construct($client_id_file)
    {
        $this->token_folder = dirname($client_id_file);
        $client_id_json = basename($client_id_file);

        $client_info = $this->readJson($client_id_json);

        $this->client_id = $client_info['web']['client_id'];
        $this->project_id = $client_info['web']['project_id'];
        $this->redirect_uris = $client_info['web']['redirect_uris'];

        $api_info = $this->readJson('api-key.json');
        $this->api_key = $api_info['api_key'];

        $account_info = $this->readJson('admin-account.json');
        $this->database_gmail_account = $account_info['gmail_account'];
    }

    function readJson($file_name)
    {
        $json_file = $this->token_folder . "/$file_name";
        $info_json = file_get_contents($json_file);
        $file_info = json_decode($info_json, true);
        return $file_info;
    }

    function redirectUri($redirect_uri)
    {
        $redirect_uri = $this->redirect_uris[$redirect_uri];
        return $redirect_uri;
    }

    function clientId()
    {
        return $this->client_id;
    }

    function projectId()
    {
        return $this->project_id;
    }

    function databaseGmailAccount()
    {
        return $this->database_gmail_account;
    }

    function apiKey()
    {
        return $this->api_key;
    }

    function clientIdFile()
    {
        return self::CLIENT_ID_FILE;
    }

    function tokenFolder()
    {
        return $this->token_folder;
    }
   public function __toString()
    {
        $str = PHP_EOL . 'GoogleInfo' . PHP_EOL;
        return $str;
    }
}

