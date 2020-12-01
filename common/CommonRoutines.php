<?php
namespace common;

class CommonRoutines
{

    static function testException()
    {
        throw new \Exception('Test exception');
    }

    static function replaceInTemplate($template_path, $vars)
    {
        $template = file_get_contents($template_path);
        foreach ($vars as $key => $value) {
            $template = str_replace("[[$key]]", $value, $template);
        }
        return $template;
    }

    static function isLoggedIn()
    {
        session_start();
        if (isset($_SESSION['USER_GMAIL'])) {
            return true;
        } else {
            return false;
        }
    }

    static function homeIfNotLoggedIn()
    {
        if (!CommonRoutines::isLoggedIn()) {
            header("Location: /");
        }
    }

    static function hasErrorJson($json_text)
    {
        if (strpos($json_text, '"error"') > 0) {
            return true;
        } else {
            return false;
        }
    }

    static function logOut()
    {
        session_start();
        session_unset();
        $google_logout = GoogleDbAuth::GOOGLE_LOGOUT;
        header("Location: $google_logout");
    }

    static function base64Encode($raw_message)
    {
        $base_64_message = rtrim(strtr(base64_encode($raw_message), '+/', '-_'), '=');
        return $base_64_message;
    }

    static function pageTemplate($page_html, $template_path)
    {
        $replace_vars = compact('page_html');
        $page_output = CommonRoutines::replaceInTemplate($template_path, $replace_vars);
        return $page_output;
    }

}



