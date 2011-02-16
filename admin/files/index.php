<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create folder object
$folder = new Folder(getGetValue("folderId"));

// Check if user has permission to view page
if(!$login->isAdmin()) {
	$login->printLoginForm();
	exit();
}

// Delete files
$delete = getPostValue("delete");
if($delete) {
	// Check if data is submitted from the form
	checkSubmitter();

	// Delete folders
	$folders = getPostValue("folders");
	if (!empty($folders)) {
		for($i=0;$i<sizeof($folders);$i++) {
			if (!empty($folders[$i])) {
				$tmpFolder = new Folder($folders[$i]);
				$tmpFolder->deleteFolder();
			}
		}
	}
	
	// Delete files
	$files = getPostValue("files");
	if (!empty($files)) {
		for($i=0;$i<sizeof($files);$i++) {
			if (!empty($files[$i])) {
				$tmpFile = new File($files[$i]);
				$tmpFile->deleteFile();
			}
		}
	}
	
	// Redirect
	redirect(scriptUrl."/".folderFilesAdmin."/".fileFilesIndex.(!empty($folder->id)?"?folderId=".$folder->id:""));
}

// Move files
$move = getPostValue("move");
if($move) {
	// Check if data is submitted from the form
	checkSubmitter();
	
	// Move folders
	$folders = getPostValue("folders");
	if (!empty($folders)) {
		for($i=0;$i<sizeof($folders);$i++) {
			$moveFolderId = getPostValue("moveFolderId");
			if (!empty($folders[$i]) && !empty($moveFolderId)) {
				$tmpFolder = new Folder($folders[$i]);
				$tmpFolder->moveFolder($moveFolderId);
			}
		}
	}
	
	// Move files
	$files = getPostValue("files");
	if (!empty($files)) {
		for($i=0;$i<sizeof($files);$i++) {
			$moveFolderId = getPostValue("moveFolderId");
			if (!empty($files[$i]) && !empty($moveFolderId)) {
				$tmpFile = new File($files[$i]);
				$tmpFile->moveFile($moveFolderId);
			}
		}
	}

	// Redirect
	redirect(scriptUrl."/".folderFilesAdmin."/".fileFilesIndex.(!empty($folder->id)?"?folderId=".$folder->id:""));
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderFilesAdmin, $lFileIndex["Header"]);

// Print common header
$site->printHeader();

// Print description
echo "<p>".$lFileIndex["HeaderText"]."</p>";

// Print subsection header
echo "<table width=\"100%\"><tr>";
echo "<td width=\"50%\" class=\"small1\">";
if (!empty($folder->id)) {
	echo "<table><tr><td>";
	echo "<a href=\"".scriptUrl."/".folderFilesAdmin."\"><img src=\"".iconUrl."/go-home.png\" height=\"16\" width=\"16\" border=\"0\" title=\"".$lFileCreateFolder["Header"]."\" alt=\"".$lFileCreateFolder["Header"]."\" /></a>";
	echo "</td><td>";
	echo $folder->getLinkPath();
	echo "</td></tr></table>";
}
else {
	echo "&nbsp;";
}
echo "</td>";
echo "<td width=\"50%\" align=\"right\">";
echo "<table><tr><td>";
echo "<a href=\"".scriptUrl."/".folderFilesAdmin."/".fileFilesCreateFolder."?folderId=".$folder->id."\"><img src=\"".iconUrl."/folder-new.png\" height=\"16\" width=\"16\" border=\"0\" title=\"".$lFileCreateFolder["Header"]."\" alt=\"".$lFileCreateFolder["Header"]."\" /></a>";
echo "</td><td><a href=\"".scriptUrl."/".folderFilesAdmin."/".fileFilesCreateFolder."?folderId=".$folder->id."\" class=\"small1\">".$lFileCreateFolder["Header"]."</a>";
echo "</td><td>";
echo "&nbsp;";
echo "</td><td>";
echo "<a href=\"".scriptUrl."/".folderFilesAdmin."/".fileFilesUploadFiles."?folderId=".$folder->id."\"><img src=\"".iconUrl."/go-up.png\" height=\"16\" width=\"16\" border=\"0\" title=\"".$lFileUploadFiles["Header"]."\" alt=\"".$lFileUploadFiles["Header"]."\" /></a>";
echo "</td><td>";
echo "<a href=\"".scriptUrl."/".folderFilesAdmin."/".fileFilesUploadFiles."?folderId=".$folder->id."\" class=\"small1\">".$lFileUploadFiles["Header"]."</a>";
echo "</td></tr></table>";
echo "</td>";
echo "</tr></table>";

// Get subfolders
$subfolders = $dbi->query("SELECT id FROM ".folderTableName." WHERE parentId=".$dbi->quote($folder->id)." ORDER BY name");

// Get files
$files = $dbi->query("SELECT id FROM ".fileTableName." WHERE folderId=".$dbi->quote($folder->id)." ORDER BY name");

// Include form
include scriptPath."/".folderFilesAdmin."/include/form/fileIndexForm.php";

// Print footer
$site->printFooter();
?>