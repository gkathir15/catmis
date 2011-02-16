<?
// Include common functions and declarations
require_once "include/common.php";

// Get values posted using either post or get
$email = decodeParameter(getValue("email"));
$friendEmail = decodeParameter(getValue("friendEmail"));
$info = decodeParameter(getValue("info"));
$link = decodeParameter(getValue("url"));
if (!empty($link)) {
	// Urlencode query part of url
	$queryPos = strpos($link, "?");
	if (!empty($queryPos)) {
		$part1 = substr($link, 0, $queryPos+1);
		$part2 = urlencode(substr($link, $queryPos+1, strlen($link)));
		$link = $part1.$part2;	
	}	
}
$message = decodeParameter(getValue("message"));
$name = decodeParameter(getValue("name"));
$showInformation = decodeParameter(getValue("showInformation"));
$subject = decodeParameter(getValue("subject"));
$summary = decodeParameter(getValue("summary"));
$title = decodeParameter(getValue("title"));
$windowTitle = decodeParameter(getValue("windowTitle"));
$windowSummary = decodeParameter(getValue("windowSummary"));

// Send link
if (!empty($_GET["mode"])) {
	if ($_GET["mode"]=="send") {
		// Validate input
		if (empty($name)) $errors->addError("name", $lSendToFriend["MissingName"]);
		if (empty($email)) $errors->addError("email", $lSendToFriend["MissingMail"]);
		if (!checkEmail($email)) $errors->addError("email", $lSendToFriend["InvalidMail"]);
		if (empty($friendEmail)) $errors->addError("friendEmail", $lSendToFriend["MissingFriendMail"]);
		if (!checkEmail($friendEmail)) $errors->addError("friendEmail", $lSendToFriend["InvalidFriendMail"]);
		if ($settings->commentsRequireValidation) {
			if (!audit()) {
				$errors->addError("validation", $lComment["WrongValidation"]);	
			}
		}

		// Check if errors were found
		if (!$errors->hasErrors()) {
			$info = parseHtml($info, 0);
			$info = str_replace("&amp;","&",$info);
			
			// Prepare message
			$message = !empty($info)?$message."\n\n".$info."\n".$link:($showInformation ? sprintf($lSendToFriend["MailMessage"], $message, $link) : $message);			
			$message = parseHtml($message, 0);
			$message = str_replace("&amp;","&",$message);
				
			// Send link
			if (mail($friendEmail, !empty($subject)?$subject:$lSendToFriend["MailSubject"], $message, "From: ".$name." <".$email.">\n")) {		
				redirect(scriptUrl."/".fileSendToFriend."?success=1".(!empty($windowTitle)?"&windowTitle=".encodeParameter($windowTitle):"").(showPopup?"&popup=1":""));
			}
			else {
				$errors->addError("misc", $lSendToFriend["DeliveryFailed"]);
			}
		}
	}
}

// Add navigation link
$site->addNavigationLink(scriptUrl."/".fileSendToFriend, !empty($windowTitle)?$windowTitle:$lSendToFriend["Header"]);

// Print common header
$site->printHeader();

if (!empty($_GET["success"])) {
	echo "<p>".$lSendToFriend["MailSent"]."</p>";
}
else {
	// Print message
	echo "<p>".(!empty($windowSummary)?$windowSummary:$lSendToFriend["HeaderText"])."</p>";

	// Print errors messages if any
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
	}

	// Check if link is present
	if (empty($link)) {
		echo "<p><i>".$lSendToFriend["MissingLink"]."</i></p>";
	}
	else {
		if (empty($summary)) $summary = $lSendToFriend["NoSummary"];
		if (empty($title)) $title = $lSendToFriend["NoTitle"];
		if (empty($message)) $message = sprintf($lSendToFriend["DefaultMessage"], $title, $summary, $link);
		
		// Include form
		include "include/form/sendToFriendForm.php";
	}
}

// Print common footer
$site->printFooter();
?>