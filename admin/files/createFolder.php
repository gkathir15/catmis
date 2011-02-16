<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create file object
$file = new File();

// Create folder object
$folder = new Folder();

// Get values
$folderId = getValue("folderId");
$name = getValue("folderName");

// Check if user has permission to create a folder
if(!$login->isAdmin()) {
	$login->printLoginForm();
	exit();
}

// Create folder
if (!empty($_GET["mode"])) {	
	// Create folder
	$errors = $folder->createFolder($name, $folderId);

	// Redirect to parent folder
	if (!$errors->hasErrors()) {
		redirect(scriptUrl."/".folderFilesAdmin."/index.php?folderId=".$folder->id);
	}
}
		
// Generate navigation
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderFilesAdmin, $lFileIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderFilesAdmin."/".fileFilesCreateFolder, $lFileCreateFolder["Header"]);

// Print common header
$site->printHeader();

// Print description
echo "<p>".$lFileCreateFolder["HeaderText"]."</p>";

// Print errors if any
if ($errors->hasErrors()) {
	$errors->printErrorMessages();	
}

// Include form template
include scriptPath."/".folderFilesAdmin."/include/form/createFolderForm.php";

// Print common footer
$site->printFooter();
?>
