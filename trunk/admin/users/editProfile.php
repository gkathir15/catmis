<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create user object
$user = new User(getGetValue("userId"));

// Check if user id is set else redirect
if (empty($user->id)) redirect(scriptUrl."/".folderAdmin);

// Check if user owns this profile
if (!$login->isUser()) {
	$login->printLoginForm();
	exit();
}

if ($login->isWebmaster() || $login->id==$user->id) {
	if (!empty($_GET["save"])) {
		// Update user profile
		$errors = $user->saveUser();

		// Redirect to user index
		if (!$errors->hasErrors()) redirect(scriptUrl."/".folderAdmin."/?message=".$lEditUser["ProfileUpdated"]);
	}

	// Add navigation links
	$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderAdmin."/".fileUserProfileEdit, $lEditProfile["Header"]);
	
	// Print common header
	$site->printHeader();

	// Print page description
	printf("<p>".$lEditProfile["HeaderText"]."</p>", $user->username);

	// Check for errors
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
		echo "<br />";
	}

	// Include edit user form
	$profile = 1;
	include "include/form/userForm.php";

	// Print transactions
	if (!empty ($user->id)) $log->printTransactions(userModuleId, $user->id);

	// Print user footer
	$site->printFooter();
}
else {
	redirect(scriptUrl."/".folderAdmin);
}
?>