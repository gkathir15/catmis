<?
// Include common functions and declarations */
require_once "../../include/common.php";

// Check if user has permission to upload files
if(!$login->isAdmin()) {
	$login->printLoginForm();
	exit();
}

// Create file object
$file = new File();

// Create folder object
$folder = new Folder(getGetValue("folderId"));

// Initialize number of files
$numberOfFiles = 0;

// Upload files
$mode = getGetValue("mode");
if (!empty($mode)) {
	if ($mode=="send") {
		// Get number of files to upload
		$numberOfFiles = getPostValue("numberOfFiles");
		if (empty($numberOfFiles) || !is_numeric($numberOfFiles)) {
			$errors->addError("numberOfFiles", $lFileUploadFiles["InvalidNumberOfFiles"]);
		}
		else if ($numberOfFiles>50) {
			$numberOfFiles = 50;
			$errors->addError("numberOfFiles", $lFileUploadFiles["MaxUploadExceeded"]);	
		}
	}	
	else if($mode=="upload") {
		// Upload files
		$file->uploadFiles($folder->id);
	
		// Redirect to parent folder
		redirect(scriptUrl."/".folderFilesAdmin."/index.php".(!empty($folder->id)?"?folderId=".$folder->id:""));
	}
}
	
// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderFilesAdmin, $lFileIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderFilesAdmin."/".fileFilesUploadFiles, $lFileUploadFiles["Header"]);

// Print common header
$site->printHeader();

// Print description
echo "<p>".$lFileUploadFiles["HeaderText"].(empty($numberOfFiles) || ($errors->hasErrors() && $mode!="upload")?" ".$lFileUploadFiles["NumberOfFilesText"]:"")."</p>";

// Print error messages if any
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
}

// Include form
include scriptPath."/".folderFilesAdmin."/include/form/uploadFilesForm.php";

// Print common footer
$site->printFooter();
?>
