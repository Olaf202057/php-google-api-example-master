<?php
namespace Google_Api;

use common\CommonRoutines;

class UserMenu
{

    function __construct($spreadsheet_url, $user_gmail, $google_client_id_json, $logger)
    {
        $this->google_client_id_json = $google_client_id_json;

        $user_tasks = new UserTasks($google_client_id_json, $logger);
        $user_sheet = $user_tasks->allUsers($spreadsheet_url);
        $assoc_user = $user_sheet->userRowValues($user_gmail);
        $this->assoc_user = $assoc_user;
    }

    public function __toString()
    {
        $str = PHP_EOL . 'UserMenu' . PHP_EOL;
        return $str;
    }

    function viewManual($current_page)
    {
        if ($current_page === '/manual.php') {
            return "disabled";
        }
        $view_manual = trim(strtolower($this->assoc_user[UsersSheet::COLUMN_MANUAL]));
        if ($view_manual === 'yes') {
            return "";
        } else {
            return "disabled";
        }
    }

    function viewMembers($current_page)
    {
        if ($current_page === '/list.php') {
            return "disabled";
        } else {
            return "";
        }
    }

    function viewEdit($current_page)
    {
        if ($current_page === '/edit.php') {
            return "disabled";
        } else {
            return "";
        }
    }

    function normalMenu($current_page, $menu_template_path)
    {
        $meta_google_content = GoogleHtml::metaGoogleContent($this->google_client_id_json);
        $google_signout = GoogleHtml::googleSignOut('/logout.php');

        $edit_disabled = $this->viewEdit($current_page);
        $view_disabled = $this->viewMembers($current_page);
        $manual_disabled = $this->viewManual($current_page);
        $replace_vars = compact('meta_google_content', 'google_signout', 'edit_disabled', 'view_disabled', 'manual_disabled');
        $menu_html = CommonRoutines::replaceInTemplate($menu_template_path, $replace_vars);
        return $menu_html;
    }


}
