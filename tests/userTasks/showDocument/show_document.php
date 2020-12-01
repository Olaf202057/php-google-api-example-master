<?php

use Google_Api\UserTasks;
use Google_Api\UsersSheet;


class TestShowDocument extends UnitTestCase
{


    function test_show_document()
    {
        global $CONF_google_client_id_json, $CONF_logger, $CONF_user_spreadsheet_document, $CONF_manual_doc_document;

        $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);
        $actual = $user_tasks->showDocument($CONF_user_spreadsheet_document, $CONF_manual_doc_document, UsersSheet::COLUMN_MANUAL, TEST_GMAIL);
        $actual_html = __DIR__ . "/actual.html";
        $expected_html = __DIR__ . "/expected.html";
        list($actual, $expected) = saveActualGetExpected($actual, $actual_html, $expected_html);
        $this->assertEqual($actual, $expected);

    }


}
