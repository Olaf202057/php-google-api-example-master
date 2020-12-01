<?php
namespace Google_Api;

use common\CommonRoutines;

class UserTasks
{

    function __construct($client_id_json_file, $message_logger)
    {
        $this->client_id_json_file = $client_id_json_file;
        $this->message_logger = $message_logger;
    }

    public function __toString()
    {
        $str = PHP_EOL . 'UserTasks' . PHP_EOL;
        return $str;
    }

    function allUsers($spreadsheet_url)
    {
        $user_sheet = UsersSheet::userSpreadsheet($spreadsheet_url, $this->client_id_json_file, $this->message_logger);
        return $user_sheet;
    }

    function googleCallBack($success_location)
    {
        $google_auth = GoogleDbAuth::googleDbAuthFactory($this->client_id_json_file, GoogleInfo::REDIRECT_URI_0, $this->message_logger);
        $google_auth->userAuthorization();
        if (!isset($_GET['code'])) {
            $google_auth_url = $google_auth->callGoogle();
            header("Location: $google_auth_url");
        } else {
            $auth_result = $google_auth->initialAuthorization($_GET['code']);
            if ($auth_result) {
                header("Location: $success_location?refresh_token=ok");
            } else {
                if (is_callable($this->message_logger)) {
                    call_user_func($this->message_logger, "No refresh_token in google-token.json oauth2callback.php");
                }
                header("Location: /?No_refresh_token=true&we_have_an_error_Houston=true");
            }
        }
    }

    function userSignIn($spreadsheet_url, $user_gmail)
    {
        session_start();
        session_unset();
        $user_sheet = UsersSheet::userSpreadsheet($spreadsheet_url, $this->client_id_json_file, $this->message_logger);
        $assoc_user = $user_sheet->userRowValues($user_gmail);
        if ($assoc_user === UsersSheet::GMAIL_NOT_IN_SPREADSHEET) {
            return UsersSheet::GUEST_ACCOUNT;
        } else {
            return $user_gmail;
        }
    }

    function updateUser($spreadsheet_url, $user_gmail, $user_data)
    {
        $first_name = $user_data['first_name'];
        $last_name = $user_data['last_name'];

        $user_sheet = UsersSheet::userSpreadsheet($spreadsheet_url, $this->client_id_json_file, $this->message_logger);

        $user_old = $user_sheet->userRowValues($user_gmail);
        $user_new = $user_old;

        $user_new[UsersSheet::COLUMN_FIRST_NAME] = $first_name;
        $user_new[UsersSheet::COLUMN_LAST_NAME] = $last_name;

        $google_sheet = UsersSheet::initUserSheet($this->client_id_json_file, $this->message_logger);

        $user_row_number = $user_new[UsersSheet::USER_ROW];
        $col_row_range = UsersSheet::ROW_START_AT_A . $user_row_number;
        $user_post_values = $user_sheet->userPostValues($user_new);
        $number_columns = count($user_new) - 1;
        $response = $google_sheet->writeRow($spreadsheet_url, $col_row_range, $user_post_values, $number_columns);
        if (CommonRoutines::hasErrorJson($response)) {
            if (is_callable($this->message_logger)) {
                call_user_func($this->message_logger, $response);
            }
            throw new \Exception('Cannot write to user sheet');
        }

        return $response;
    }

    function editUser($spreadsheet_url, $user_gmail)
    {
        $user_sheet = $this->allUsers($spreadsheet_url);
        $assoc_user = $user_sheet->userRowValues($user_gmail);

        $first_name = $assoc_user[UsersSheet::COLUMN_FIRST_NAME];
        $last_name = $assoc_user[UsersSheet::COLUMN_LAST_NAME];

        $edit_values = ['first_name' => $first_name
            , 'last_name' => $last_name

        ];
        return $edit_values;
    }


    function userList($spreadsheet_url, $sort_column, $user_gmail)
    {
        $user_sheet = $this->allUsers($spreadsheet_url);
        $show_columns = [UsersSheet::COLUMN_GMAIL
            , UsersSheet::COLUMN_FIRST_NAME
            , UsersSheet::COLUMN_LAST_NAME

        ];
        $html_table = $user_sheet->listTable($show_columns, $sort_column, $user_gmail);
        return $html_table;
    }


    function showDocument($spreadsheet_url, $document_url, $yes_column, $user_gmail)
    {
        $user_sheet = UsersSheet::userSpreadsheet($spreadsheet_url, $this->client_id_json_file, $this->message_logger);
        $allowed_viewing = $user_sheet->allowedViewing($yes_column, $user_gmail);
        if ($allowed_viewing) {
            $document_html = GoogleDocument::documentHtml($this->client_id_json_file, $document_url, $this->message_logger);
            return $document_html;
        } else {
            return false;
        }
    }

}
