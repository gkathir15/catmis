<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create user object
$user = new User(getGetValue("userId"));

// Check if user owns this profile
$forgotPassword = getValue("forgotPassword");
if ($login->isLoggedIn() || $forgotPassword) {
	if (!empty($user->id)) {
		if ($login->isWebmaster() || $login->id==$user->id || $forgotPassword) {
			if (!empty($_GET["save"])) {				
				// Change user password
				$errors = $user->changePassword(getPostValue("password"));

				// Redirect to user index
				if (!$errors->hasErrors()) redirect($forgotPassword ? scriptUrl.'/'.fileProfileForgotPassword.'?success=1' : scriptUrl."/".folderAdmin);
			}
		
			// Add navigation links
			$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
			$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["ChangePassword"]);
			
			// Print common header
			$site->printHeader();
		
			// Print page description
			printf("<p>".($login->id==$user->id ? $lChangePassword["HeaderText"] : $lChangePassword["HeaderText2"])."</p>", $user->username);
		
			// Check for errors
			if ($errors->hasErrors()) $errors->printErrorMessages();
		
			// Include edit user form
			include "include/form/userPasswordForm.php";
		
			// Print common footer
			$site->printFooter();
		}
	}
}
?>