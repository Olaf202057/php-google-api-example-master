<?php  //      php tests/call_tests.php  

define("GOOGLE_TEST_CONFIG", "google-test");
define("TEST_GMAIL", 'test@gmail.com');         

function saveActualGetExpected($actual, $actual_file, $expected_file)
{
    file_put_contents($actual_file, $actual);
    $expected = file_get_contents($expected_file);
    $actual_no_spaces = preg_replace('/\s+/', '', $actual);
    $expected_no_spaces = preg_replace('/\s+/', '', $expected);
    return array($actual_no_spaces, $expected_no_spaces);
}
               
use Logging\ErrorLog;

$simple_test_init = dirname(__DIR__) . '/vendor/simpletest/simpletest/autorun.php';
require_once $simple_test_init;

$init_php = dirname(__DIR__) . "/initEnvironment.php";
require_once $init_php;



$google_config_dir = dirname(__DIR__) . "/" . GOOGLE_TEST_CONFIG;


$init_vars = init_environment($google_config_dir, ErrorLog::SCREEN_FILE_LOGGER);
extract($init_vars, EXTR_PREFIX_ALL, 'CONF');


require_once __DIR__ . '/userMenu/user_menu.php';

require_once __DIR__ . '/userTasks/editUser/edit_user.php';
require_once __DIR__ . '/userTasks/showDocument/show_document.php';
require_once __DIR__ . '/userTasks/updateUser/update_user.php';
require_once __DIR__ . '/userTasks/userList/user_list.php';
require_once __DIR__ . '/userTasks/userSignIn/user_sign_in.php';

