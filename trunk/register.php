<?
// Include common functions and declarations
require_once "include/common.php";

// Check if registration is allowed in this system
if ($settings->allowUserRegistration) {
	// Get user identifier
	$id = getGetValue("userId");

	// Create user object
	$user = new User($id);
	
	// Add navigation link
	$site->addNavigationLink(scriptUrl."/".fileRegister, $lRegister["Header"]);

	// Check if user should be registered
	if (!empty($_GET["save"])) {
		// Register user profile
		$errors = $user->saveUser();

		// Check if errors occured during registration
		if (!$errors->hasErrors()) {
			// Print header
			$site->printHeader(false);
	
			// Print success message
			$site->printSectionHeader($lRegister["Success"]);
			printf("<p>".$lRegister["SuccessText"]."</p>", $user->username);
			
			// Print common footer
			$site->printFooter();
			exit();
		}
	}
	
	// Print header
	$site->printHeader();
	
	// Print registration text
	echo "<p>".$lRegister["HeaderText"]."</p>";
	
	// Check for errors
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
		echo "<br />";
	}

	// Include user form
	$register = true;
	include scriptPath."/".folderUsers."/include/form/userForm.php";
	
	// Print user footer
	$site->printFooter();
}
else {
	redirect(scriptUrl);
}
?>