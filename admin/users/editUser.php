<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create user object
$user = new User(getGetValue("userId"));

// Check if user is webmaster
if (!$user->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete user
$deleteUser = getValue("deleteUser");
if (!empty($deleteUser)) {
	// Delete user
	$user->deleteUser();

	// Redirect to user index
	redirect(scriptUrl."/".folderUsers);
}
else if (!empty($_GET["save"])) {
	// Save user data
	$errors = $user->saveUser();

	// Redirect to user index
	if (!$errors->hasErrors()) {
		redirect(scriptUrl."/".folderUsers);
	}
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderUsers, $lUserIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderUsers, !empty($user->id)?$lEditUser["EditUser"]:$lEditUser["NewUser"]);

// Print common header
$site->printHeader();

// Print page description
if (empty ($user->id)) {
	echo "<p>".$lEditUser["NewUserText"]."</p>";
} 
else {
	printf("<p>".$lEditUser["EditUserText"]."</p>", $user->username, scriptUrl."/".folderUsers."/".fileUserChangePassword."?userId=".$user->id);
}

// Check for errors
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
	echo "<br />";
}

// Include edit user form
$profile = 0;
include "include/form/userForm.php";

// Print transactions
if (!empty ($user->id)) {
	$log->printTransactions(userModuleId, $user->id);
}

// Print user footer
$site->printFooter();
?>



