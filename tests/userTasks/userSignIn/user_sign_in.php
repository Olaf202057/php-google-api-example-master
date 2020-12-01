<?php

use Google_Api\UserTasks;
use Google_Api\UsersSheet;


class TestUserSignIn extends UnitTestCase
{


    function test_user_sign_in()
    {
        global $CONF_google_client_id_json, $CONF_logger, $CONF_user_spreadsheet_document;
        
        $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
        $user_email = $user_tasks->userSignIn($CONF_user_spreadsheet_document, 'email_not_in_spreadsheet');
        $this->assertEqual($user_email, UsersSheet::GUEST_ACCOUNT);

        $test_email = $user_tasks->userSignIn($CONF_user_spreadsheet_document, TEST_GMAIL);
        $this->assertEqual($test_email, TEST_GMAIL);

    }


}
