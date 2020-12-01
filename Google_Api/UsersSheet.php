<?php
namespace Google_Api;

class UsersSheet
{

    const ROW_START_AT_A = '!A';

    const COLUMN_GMAIL = 'Gmail Account';
    const COLUMN_FIRST_NAME = 'First Name';
    const COLUMN_LAST_NAME = 'Last Name';
    const COLUMN_MANUAL = 'Docs Manual';

    const GMAIL_NOT_IN_SPREADSHEET = 'gmail not in spreadsheet';
    const USER_ROW = 'row number';
    const GUEST_ACCOUNT = 'guest_account';

    function __construct($google_json)
    {
        $response_array = json_decode($google_json, true);
        $this->row_values = $response_array['values'];
        $this->header_keys = array_shift($this->row_values);
    }

    public function __toString()
    {
        $str = PHP_EOL . 'UsersSheet' . PHP_EOL;
        return $str;
    }

    function filterAssoc($user_assoc, $show_columns)
    {
        $filtered_user = [];
        foreach ($show_columns as $column_name) {
            if (isset($user_assoc[$column_name])) {
                $filtered_user[$column_name] = $user_assoc[$column_name];
            } else {
                $filtered_user[$column_name] = '';
            }
        }
        return $filtered_user;
    }

    function arrayCombine($long_keys, $short_values)
    {
        $combined_array = [];
        foreach ($long_keys as $column => $key) {
            $current_key = $long_keys[$column];
            if (isset($short_values[$column])) {
                $current_value = $short_values[$column];
                $combined_array[$current_key] = $current_value;
            } else {
                $combined_array[$current_key] = '';
            }
        }
        return $combined_array;
    }

    function listTable($show_columns, $sort_by, $user_gmail)
    {
        $filtered_users = [];
        foreach ($this->row_values as $user_row) {
            $user_assoc = $this->arrayCombine($this->header_keys, $user_row);
            $filter_assoc = $this->filterAssoc($user_assoc, $show_columns);
            $sort_key = $filter_assoc[$sort_by];
            $filtered_users[$sort_key] = $filter_assoc;
        }
        ksort($filtered_users);

        ///   https://v4-alpha.getbootstrap.com/layout/grid/
        $table_html = " <div class='container'><div class='row'>";
        foreach ($show_columns as $header_text) {
            $table_html .= "<div class='content-main'>$header_text</div>";
        }
        $table_html .= "</div>";
        foreach ($filtered_users as $filtered_user) {
            $table_html .= "<div class='row'>";
            $color = 1;
            foreach ($filtered_user as $column_key => $user_value) {
                $column_number = "column-$color";
                if ($column_key === UsersSheet::COLUMN_GMAIL) {
                    if ($user_value === $user_gmail) {
                        $user_value = "<b>$user_value</b>";
                    }
                }
                $table_html .= "<div class='content-secondary $column_number'>$user_value</div>";
                $color++;
            }
            $table_html .= "</div>";
        }
        $table_html .= "</div>";
        return $table_html;
    }

    function allowedViewing($work_type, $user_gmail)
    {
        $user_row = $this->userRowValues($user_gmail);
        if ($user_row[$work_type] === 'yes') {
            return true;
        }
        return false;
    }

    function userRowValues($user_gmail)
    {
        foreach ($this->row_values as $row_number => $user_row) {
            $current_row = $row_number + 2;
            $assoc_user = [self::USER_ROW => $current_row];
            foreach ($this->header_keys as $column_key => $header_value) {
                $assoc_key = $header_value;
                if (isset($user_row[$column_key])) {
                    $user_value = $user_row[$column_key];
                    $assoc_user[$assoc_key] = $user_value;
                } else {
                    $assoc_user[$assoc_key] = '';
                }
            }
            if ($assoc_user[self::COLUMN_GMAIL] === $user_gmail) {
                return $assoc_user;
            }
        }
        return self::GMAIL_NOT_IN_SPREADSHEET;
    }

    function orderedUser($user_new)
    {
        $ordered_values = [];
        foreach ($this->header_keys as $header_key) {
            $current_value = $user_new[$header_key];
            $ordered_values[] = $current_value;
        }
        return $ordered_values;
    }

    function userPostValues($user_new)
    {
        $ordered_values = $this->orderedUser($user_new);
        $jsoned_vaulues = '"' . implode('","', $ordered_values) . '"';
        $curlopt_postfields = '{"values":[[' . $jsoned_vaulues . ']]}';
        return $curlopt_postfields;
    }

    function rowRange()
    {
        $user_row = $this->user[self::USER_ROW];
        $col_row_range = UsersSheet::ROW_START_AT_A . $user_row;
        return $col_row_range;
    }

    function numberColumns()
    {
        $number_columns = count($this->user) - 1;
        return $number_columns;
    }

    static function initUserSheet($client_id_json_file, $message_logger = [])
    {
        $google_auth = GoogleDbAuth::googleDbAuthFactory($client_id_json_file, GoogleInfo::REDIRECT_URI_0, $message_logger);
        $google_client = $google_auth->clientAfterAuthorization();
        if ($google_client) {
            $google_sheet = GoogleSheet::googleSheetFactory($google_client);
            return $google_sheet;
        }
        return false;
    }

    static function userSpreadsheet($spreadsheet_url, $client_id_json_file, $message_logger = [])
    {
        $google_sheet = UsersSheet::initUserSheet($client_id_json_file, $message_logger);
        if ($google_sheet) {
            $response = $google_sheet->readSheet($spreadsheet_url);
            if (strpos($response, "UNAUTHENTICATED")) {
                $_SESSION['LOST_DATABASE'] = true;
                if (is_callable($message_logger)) {
                    $error_message = "Program has lost Google authentication credentials. UsersSheet->userSpreadsheet";
                    call_user_func($message_logger, $error_message);
                }
                return false;
            }
            $user_sheet = new UsersSheet($response);
            return $user_sheet;
        }
        return false;
    }

}

