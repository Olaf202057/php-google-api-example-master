<?php
namespace Google_Api;

class GoogleDocument
{
    static function documentHtml($client_id_json_file, $document_url, $message_logger = [])
    {
        list($junk_ignore, $id_edit) = explode("/d/", $document_url);
        list($document_id, $junk_ignore) = explode("/edit", $id_edit);
        $google_auth = GoogleDbAuth::googleDbAuthFactory($client_id_json_file, GoogleInfo::REDIRECT_URI_0, $message_logger);
        $google_client = $google_auth->clientAfterAuthorization();
        if ($google_client) {
            $google_drive_service = new \Google_Service_Drive($google_client);
            $guzzle_http = $google_drive_service->files->export($document_id
                , 'text/html'
                , array('alt' => 'media'));
            $document_html = $guzzle_http->getBody();
            return $document_html;
        } else {
            return 'Database Error';
        }
    }

    public function __toString()
    {
        $str = PHP_EOL . 'GoogleDocument' . PHP_EOL;
        return $str;
    }
    
}
