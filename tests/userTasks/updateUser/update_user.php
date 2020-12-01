<?php

use Google_Api\UserTasks;
use Google_Api\UsersSheet;


class TestUpdateUser extends UnitTestCase
{


//    function testCanBeNegated()
    function test_update_user()
    {
        global $CONF_google_client_id_json, $CONF_logger, $CONF_user_spreadsheet_document;

        $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
        $user_data = array('first_name' => 'first_17', 'last_name' => 'last_17');
        $user_tasks->updateUser($CONF_user_spreadsheet_document, TEST_GMAIL, $user_data);

        $actual_17 = $user_tasks->userList($CONF_user_spreadsheet_document, UsersSheet::COLUMN_GMAIL);
        $actual_17_html = __DIR__ . "/actual_17.html";
        $expected_17_html = __DIR__ . "/expected_17.html";
        list($actual, $expected) = saveActualGetExpected($actual_17, $actual_17_html, $expected_17_html);
        $this->assertEqual($actual, $expected);

        $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
        $user_data = array('first_name' => 'first', 'last_name' => 'last');
        $user_tasks->updateUser($CONF_user_spreadsheet_document, TEST_GMAIL, $user_data);

        $actual = $user_tasks->userList($CONF_user_spreadsheet_document, UsersSheet::COLUMN_GMAIL);
        $actual_html = __DIR__ . "/actual.html";
        $expected_html = __DIR__ . "/expected.html";
        list($actual, $expected) = saveActualGetExpected($actual, $actual_html, $expected_html);
        $this->assertEqual($actual, $expected);

    }


}
