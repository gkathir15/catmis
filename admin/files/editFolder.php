<?
// Include common functions and declarations
require_once "../../include/common.php";

// Check if user has edit permission
if(!$login->isAdmin()) {
	$login->printLoginForm();
	exit();
}

// Create folder object
$folder = new Folder(getGetValue("folderId"));

// Check if folder exists
if (!empty($folder->id)) {
	// Update folder
	$mode = getGetValue("mode");
	if ($mode=="update") {
		// Save folder
		$errors = $folder->saveFolder();
	
		if (!$errors->hasErrors()) {
			// Redirect
			redirect(scriptUrl."/".folderFilesAdmin."/".fileFilesIndex.(!empty($folder->parent->id)?"?folderId=".$folder->parent->id:""));
		}
	}
	
	// Add navigation links
	$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderFilesAdmin, $lFileIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderFilesAdmin."/".fileFilesEditFolder."?folderId=".$folder->id, $lFileEditFolder["Header"]); 
	
	// Print common header
	$site->printHeader();
	
	// Print page text
	printf($lFileEditFolder["HeaderText"], $folder->name);
	
	// Print error messages if any
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
	}
	
	// Include form
	include scriptPath."/".folderFilesAdmin."/include/form/folderForm.php";
	
	// Print common footer
	$site->printFooter();
}
else {
	redirect(scriptUrl."/".folderFilesAdmin."/".fileFilesIndex);	
}

?>