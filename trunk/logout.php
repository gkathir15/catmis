<?
// Include common functions and declarations
require_once "include/common.php";

// Delete login cookie
$login->logout();

// Close session
session_write_close();

// Redirect to referer
redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl);
?>