<?php

use Google_Api\UserTasks;
use Google_Api\UsersSheet;


class TestUserList extends UnitTestCase
{


    function test_user_list()
    {
        global $CONF_google_client_id_json, $CONF_logger, $CONF_user_spreadsheet_document;
        
        $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
        $actual = $user_tasks->userList($CONF_user_spreadsheet_document, UsersSheet::COLUMN_LAST_NAME);

        $actual_html = __DIR__ . "/actual.html";
        $expected_html = __DIR__ . "/expected.html";
        list($actual, $expected) = saveActualGetExpected($actual, $actual_html, $expected_html);
        $this->assertEqual($actual, $expected);


    }


}
