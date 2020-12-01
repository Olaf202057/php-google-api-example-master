<?php

use Google_Api\UserTasks;
use Google_Api\GoogleHtml;


class TestEditUser extends UnitTestCase
{


    function test_edit_user()
    {
        global $CONF_google_client_id_json, $CONF_logger, $CONF_user_spreadsheet_document, $CONF_template_path;

        $user_tasks = new UserTasks($CONF_google_client_id_json, $CONF_logger);

        $edit_values = $user_tasks->editUser($CONF_user_spreadsheet_document, TEST_GMAIL);
        
        
        
  
        
        $actual = GoogleHtml::editForm($edit_values, '/update.php', "$CONF_template_path/edit.html");
  
    
        $actual_html = __DIR__ . "/actual.html";
        $expected_html = __DIR__ . "/expected.html";
        list($actual, $expected) = saveActualGetExpected($actual, $actual_html, $expected_html);
        $this->assertEqual($actual, $expected);


    }


}
