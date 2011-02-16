<?
// Include common functions and declarations
require_once "include/common.php";

// Add navigation link
$site->addNavigationLink(scriptUrl."/".fileProfileForgotPassword, $lForgotPassword["Header"]);

// Print header
$site->printHeader();
echo '<p>'.$lForgotPassword["HeaderText"].'</p>';

// Get values
$send = getGetValue("send");
$username = getPostValue("username");
$email = getPostValue("email");
$id = getGetValue("id");
$key = getGetValue("key");
$success = getGetValue("success");

if (!empty($id) && !empty($key)) {
	$user = new User($id);
	if (!empty($user->id)) {
		// Check if key matches key in database
		$result = $dbi->query("SELECT username,activationKey FROM ".userTableName." WHERE id=".$dbi->quote($user->id));
		if ($result->rows()) {
			list($username,$activationKey) = $result->fetchrow_array();
			if ($key==$activationKey) {
				// Include password form
				$forgotPassword = 1;
				include scriptPath.'/'.folderUsers.'/include/form/userPasswordForm.php';
			}
			else {
				echo '<p>'.$lForgotPassword["InvalidKey"].'</p>';
			}
		}
	}
}
else if (!empty($send) && (!empty($username) || !empty($email))){
	// Find user in database
	$user = new User();
	if (!empty($email)) {
		$result = $dbi->query("SELECT id FROM ".userDataTableName." WHERE email=".$dbi->quote($email));
		if ($result->rows()) {
			list($id) = $result->fetchrow_array();
			$user->init($id);
		}
	}
	else if (!empty($username)) {		
		$user->init(0, $username);
	}
	if (!empty($user->id)) {
		// Generate new key and insert into database
		$key = generateRandomString(32);
		$dbi->query("UPDATE ".userTableName." SET activated=1,registered=registered,lastLogged=lastLogged,lastUpdated=lastUpdated,activationKey=".$dbi->quote($key)." WHERE id=".$dbi->quote($user->id));

		$link = scriptUrl.'/'.fileProfileForgotPassword.'?id='.$user->id.'&amp;key='.$key;
		$subject = $lForgotPassword["MailSubject"];
		$message = sprintf($lForgotPassword["MailMessage"], $link, $link);

		// Create plain text version
		$h2t =& new html2text($message);

		// Create PHPMailer object
		$mail = new phpmailer();

		// Set mail values
		$mail->CharSet 		= "UTF-8";
		$mail->From     	= pageAdminMail;
		$mail->FromName 	= pageTitle;
		$mail->Subject 		= $subject;
		$mail->Body 		= $message;
		$mail->AltBody 		= $h2t->get_text();
		$mail->AddAddress($user->email);

		// Send email
	    if ($mail->Send()) {
			echo '<p>'.$lForgotPassword["MailSent"].'</p>';
		}
		else {
			echo '<p>'.$lForgotPassword["MailFailed"].'</p>';			
		}
	}
	else {
		echo '<p>'.$lForgotPassword["MailFailed"].'</p>';			
	}
}
else if ($success) { 
	echo '<p>'.$lForgotPassword["PasswordChanged"].'</p>';
}
else {
?>
<form action="<?= scriptUrl.'/'.fileProfileForgotPassword.'?send=1' ?>" method="post">
<?= $lForgotPassword["Username"] ?><br />
<input type="text" name="username" value="" class="shortInput" />

<p><i><?= $lForgotPassword["Or"] ?></i></p>

<p><?= $lForgotPassword["Email"] ?><br />
<input type="text" name="email" value="" class="shortInput" /></p>

<p><input type="submit" value="<?= $lForgotPassword["Submit"] ?>" /></p>
</form>
<?
}

// Print footer
$site->printFooter();
?>