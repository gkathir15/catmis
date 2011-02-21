<?
// Include common functions and declarations
require_once "include/common.php";

// Get values
$password = getPostValue("password");
$referer = getPostValue("referer");
$refererPost = getPostValue("refererPost");
$remember = getPostValue("remember");
$username = getPostValue("username");

// Get page to refer to if login was successful
if (empty($referer) && !empty($_SERVER["HTTP_REFERER"])) {
	$referer = $_SERVER["HTTP_REFERER"];
}
if ($referer == scriptUrl."/".fileLogin) {
	$referer = scriptUrl;
}
else if (strpos($referer,scriptUrl."/".fileProfileActivate) !== false) {
	$referer = scriptUrl;
}

// If user is already logged in redirect
if($login->isLoggedIn() && (empty($username) && empty($password))) {
	redirect($referer,(!empty($refererPost)?urldecode($refererPost):""));
}

// Attempt to log in
if(!empty($username) && !empty($password)) {
	// Check if data is submitted from the form
	checkSubmitter();
	
	// If user is already logged in redirect
	if($login->isLoggedIn()) {
		if ($login->username!=$username) $login->logout();
		else redirect($referer,(!empty($refererPost)?urldecode($refererPost):""));
	}

	if($login->checkLogin($username, $password, !empty($remember)?$remember:false)) {
		redirect($referer,(!empty($refererPost)?urldecode($refererPost):""));
	}
	else {
		$errors->addError("username", $lLogin["InvalidData"]); 
	}
	
	session_write_close();
}

// Print login form
$login->printLoginForm(true, $errors);
?>