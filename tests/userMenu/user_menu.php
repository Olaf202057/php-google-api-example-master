<?php

use Google_Api\UserMenu;


class TestUserMenu extends UnitTestCase
{


    function test_user_menu()
    {
        global $CONF_google_client_id_json, $CONF_logger, $CONF_user_spreadsheet_document, $CONF_template_path;
        
        
        
        $user_menu = new UserMenu($CONF_user_spreadsheet_document, TEST_GMAIL, $CONF_google_client_id_json, $CONF_logger);
        $actual = $user_menu->normalMenu('/list.php', "$CONF_template_path/menu.html");


        $actual_html = __DIR__ . "/actual.html";
        $expected_html = __DIR__ . "/expected.html";
        list($actual, $expected) = saveActualGetExpected($actual, $actual_html, $expected_html);
        $this->assertEqual($actual, $expected);

    }


}
