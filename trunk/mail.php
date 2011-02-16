<?
// Include common functions and declarations
require_once "include/common.php";

// Get mode
$mode = getGetValue("mode");

// Send link
$success = 0;
if (!empty($mode)) {
	$name = getPostValue("name");
	$mail = getPostValue("email");
	$message = getPostValue("message");
	$subject = getPostValue("subject");
	
	// Validate input
	if (empty($name)) $errors->addError("name", $lSendMail["MissingName"]);
	if (empty($mail)) $errors->addError("mail", $lSendMail["MissingMail"]);
	if (!checkEmail($mail)) $errors->addError("mail", $lSendMail["InvalidMail"]);
	if (empty($subject)) $errors->addError("subject", $lSendMail["MissingSubject"]);

	// If there were no errors send mail
	if (!$errors->hasErrors()) {
		// Send link
		if (mail(pageAdminMail, $subject, $message, "From: ".$name." <".$mail.">\n")) {		
			$success = 1;
		}
	}
}

// Add navigation
$site->addNavigationLink(scriptUrl."/".fileSendMail, $lSendMail["Header"]); 

// Print header
$site->printHeader();

if ($success) {
	echo "<p><b>".$lSendMail["MailSent"]."</b></p>";
}
else {
	// Print message
	echo "<p>".$lSendMail["HeaderText"]."</p>";

	// Print errors messages if any
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
	}
	
	// Get data
	$message = !empty($_GET["message"])?$_GET["message"]:"";
	$subject = !empty($_GET["subject"])?$_GET["subject"]:"";

	// Include form
	include "include/form/mailForm.php";
}

// Print common footer
$site->printFooter();
?>