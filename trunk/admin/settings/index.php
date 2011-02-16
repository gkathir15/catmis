<?
// Include common functions and declarations
require_once "../../include/common.php";

// Check if user is webmaster
if(!$login->isWebmaster()) {
	$login->printLoginForm();
	exit();
}

// Update settings in database
if (!empty($_GET["save"])) {
	// Update settings
	$errors = $settings->saveSettings();

	// Redirect to admin index
	if (!$errors->hasErrors()) redirect(scriptUrl."/".folderAdmin);
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderSettings, $lSettings["Header"]);

// Print header
$site->printHeader();

// Print page description
echo "<p>".$lSettings["HeaderText"]."</p>";

// Print errors
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
}

// Include settings form
include "include/form/settingsForm.php";

// Print footer
$site->printFooter();
?>