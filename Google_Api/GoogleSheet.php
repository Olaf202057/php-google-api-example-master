<?php
namespace Google_Api;

use common\CommonRoutines;

class GoogleSheet
{
    const SHEETS_API = 'https://sheets.googleapis.com/v4/spreadsheets/';

    function __construct($access_token)
    {
        $this->access_token = $access_token;
    }
   public function __toString()
    {
        $str = PHP_EOL . 'GoogleSheet' . PHP_EOL;
        return $str;
    }
    function writeRow($spreadsheet_url, $col_row_range, $user_post_values, $number_columns)
    {
        list($spreadsheet_id, $sheet_title) = $this->getIdAndSheetTitle($spreadsheet_url);
        $curl_url = self::SHEETS_API . $spreadsheet_id . '/values/' . $sheet_title . $col_row_range . '?valueInputOption=USER_ENTERED';
        $google_curl = $this->curlStart($curl_url);
        curl_setopt($google_curl, CURLOPT_POST, $number_columns);
        curl_setopt($google_curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($google_curl, CURLOPT_POSTFIELDS, $user_post_values);
        $curl_response = curl_exec($google_curl);
        return $curl_response;
    }

    function getSheetTitle($spreadsheet_id, $sheet_gid)
    {
        $curl_url = self::SHEETS_API . $spreadsheet_id . '?&fields=sheets.properties';
        $google_curl = $this->curlStart($curl_url);
        $curl_response = curl_exec($google_curl);
      //  throw new \Exception("Cannot access user sheet - $curl_response");
        if (!CommonRoutines::hasErrorJson($curl_response)) {
            $sheets_obj = json_decode($curl_response, true);
            $sheets_list = $sheets_obj['sheets'];
            foreach ($sheets_list as $a_sheet) {
                $property_list = $a_sheet['properties'];
                $sheet_id = $property_list['sheetId'];
                if ($sheet_id == $sheet_gid) {
                    $sheet_title = $property_list['title'];
                    $title_url = urlencode($sheet_title);
                    
                    return $title_url;
                }
            }

        }
        throw new \Exception("Cannot access user sheet - $curl_response");
    }

    function getIdAndSheetTitle($spreadsheet_url)
    {
        list($junk_ignore, $id_edit_gid) = explode("/d/", $spreadsheet_url);
        list($spreadsheet_id, $gid) = explode("/edit#gid=", $id_edit_gid);
        $sheet_title = $this->getSheetTitle($spreadsheet_id, $gid);
        return [$spreadsheet_id, $sheet_title];
    }

    function readSheet($spreadsheet_url)
    {
        list($spreadsheet_id, $sheet_title) = $this->getIdAndSheetTitle($spreadsheet_url);
        $curl_url = self::SHEETS_API . $spreadsheet_id . '/values/' . $sheet_title . '!A:Z';
        $google_curl = $this->curlStart($curl_url);
        $curl_response = curl_exec($google_curl);
        return $curl_response;
    }

    function curlStart($curl_url)
    {
        $auth_headers = array("Authorization: Bearer $this->access_token"
        , "cache-control: no-cache"
        , "content-type: text/html");
        $google_curl = curl_init();
        curl_setopt($google_curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($google_curl, CURLOPT_HTTPHEADER, $auth_headers);
        curl_setopt($google_curl, CURLOPT_URL, $curl_url);
        return $google_curl;
    }

    static function googleSheetFactory($google_client)
    {
        $authenticated_token = $google_client->getAccessToken();
        $access_token = $authenticated_token['access_token'];
        $google_sheet = new GoogleSheet($access_token);
        return $google_sheet;
    }
}
