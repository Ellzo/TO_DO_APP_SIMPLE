<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('APP_LANG','en');

include("lib/db.php");
include("lib/utils.php");
include("lang/".APP_LANG.".php");

include("lib/func.user.php");

$dbhost = 'localhost';
$dbuser = '';
$dbpass = '';
$dbname = 'ToDo';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

$vars=get_input_vars();
$appuser=user_get_logged_user();
?>
