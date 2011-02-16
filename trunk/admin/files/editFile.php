<?
// Include common functions and declarations
require_once "../../include/common.php";

// Check if user has edit permissions
if(!$login->isAdmin()) {
	$login->printLoginForm();
	exit();
}

// Create file object
$file = new File(getGetValue("fileId"));

// Check if file exists
if (!empty($file->id)) {
	$mode = getGetValue("mode");
	if($mode=="update") {
		// Save file
		$errors = $file->saveFile();
	
		if (!$errors->hasErrors()) {
			// Redirect
			redirect(scriptUrl."/".folderFilesAdmin."/".fileFilesIndex.(!empty($file->parent->id)?"?folderId=".$file->parent->id:""));
		}
	}
	
	// Add navigation links
	$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderFilesAdmin, $lFileIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderFilesAdmin."/".fileFilesEditFile."?fileId=".$file->id, $lFileEditFile["Header"]); 
	
	// Print common header
	$site->printHeader();
	
	// Print page text
	printf($lFileEditFile["HeaderText"], $file->name);
	
	// Print error messages if any
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
	}
	
	// Include form
	include scriptPath."/".folderFilesAdmin."/include/form/fileForm.php";
	
	// Print common footer
	$site->printFooter();
}
else {
	redirect(scriptUrl."/".folderFilesAdmin."/".fileFilesIndex);	
}
?>