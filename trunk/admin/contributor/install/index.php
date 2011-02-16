<?
// Skip checking if module is installed
$skipInstalledCheck = true;

// Include common functions and declarations
require_once "../../../include/common.php";
require_once "../include/config.php";

// Check if user is webmaster
if (!$login->isWebmaster()) {
	$login->printLoginForm();
	exit();	
}

if (!$module->isModuleInstalled("Contributor")) {
	if (empty($_GET["step"])) {
		// Print common header
		printHeader("Contributor Module Installation");
		
		// Print section header
		printSectionHeader("Contributor Module Installation");
		echo "<p>You're about to install the contributor module into CMIS. Click <a href=\"index.php?step=2\">here</a> to proceed.</p>";
		
		// Print footer
		printFooter();			
	}
	else {	
		// Create tables in database
		include "tables.php";
		$dbi->createTables($dbTableDefs);

		// Register content types
		$module->initialize();
		
		// Print common header
		printHeader("Contributor Installed");
		
		// Print section header
		printSectionHeader("Contributor Module Installed");
		echo "<p>Remember to remove the install folder for security reasons. Click <a href=\"".scriptUrl."/".folderContributor."\">here</a> to start using the module.</p>";
		
		// Print footer
		printFooter();
	}
}
?>