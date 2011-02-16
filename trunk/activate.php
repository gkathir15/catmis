<?
// Include common functions and declarations
require_once "include/common.php";

// Check if activation with email is enabled on this site
if ($settings->activateWithEmail) {
	// Get values
	$activate =	getGetValue("activate");
	$activationKey = getGetValue("activationKey");
	$id = getGetValue("id");

	// Add navigation link
	$site->addNavigationLink(scriptUrl."/".fileProfileActivate, $lActivate["Header"]);

	// Print header
	$site->printHeader();

	// Try to activate profil
	if (!empty($activate)) $login->activateUser($id, $activationKey);
	
	// Print common footer
	$site->printFooter();
}
?>