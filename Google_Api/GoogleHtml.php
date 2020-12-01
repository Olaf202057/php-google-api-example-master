<?php
namespace Google_Api;

use common\CommonRoutines;

class GoogleHtml
{

    static function googleSignOut($sign_out_location)
    {
        $google_sign_out = <<< EOS
            <script src="https://apis.google.com/js/platform.js?onload=onLoadGoogle" async defer></script>
            <script>
                function onLoadGoogle() {
                    gapi.load('auth2', function() { gapi.auth2.init() })
                }

                function signOutGoogle() {
                    var auth2 = gapi.auth2.getAuthInstance()
                    var q = auth2.signOut()
                    window.location = "$sign_out_location"
                }
            </script>
EOS;
        return $google_sign_out;
    }

    static function googleSignIn($sign_in)
    {
        $google_sign_in = <<< EOS
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            <style>
                div.g-signin2{
                    position: absolute;
                    left: 123px;
                    top: 123px;
                }
            </style>
            <div class="g-signin2" data-onsuccess="onSignIn"></div>
            <script>
                function onSignIn(googleUser) {
                    var profile = googleUser.getBasicProfile()
                    var user_gmail = profile.getEmail()
                    var check_location = "$sign_in?gmail=" + user_gmail
                    window.location = check_location
                }
            </script>
EOS;
        return $google_sign_in;
    }

    static function metaGoogleContent($client_id_json_file)
    {
        $google_info = new GoogleInfo($client_id_json_file);
        $client_id = $google_info->clientId();
        $meta_google_content = "<meta name='google-signin-client_id' content='$client_id'>";
        return $meta_google_content;
    }

    static function editForm($edit_values, $post_link, $template_path)
    {
        $first_name = $edit_values['first_name'];
        $last_name = $edit_values['last_name'];
        $replace_vars = compact('post_link', 'first_name', 'last_name');
        $page_output = CommonRoutines::replaceInTemplate($template_path, $replace_vars);
        return $page_output;
    }

    public function __toString()
    {
        $str = PHP_EOL . 'GoogleHtml' . PHP_EOL;
        return $str;
    }
}
