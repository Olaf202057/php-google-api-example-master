<?php




require_once dirname(__DIR__) . "/common/autoload_gapi.php";

use Google_Api\UsersSheet;

 
session_start();
$_SESSION['USER_GMAIL']=UsersSheet::GUEST_ACCOUNT;
header('Location: /list.php');
exit;
