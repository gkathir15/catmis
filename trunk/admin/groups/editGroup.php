<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create group object
$group = new Group(!empty($_GET["groupId"])?$_GET["groupId"]:0);

// Check user permissions
if (!$group->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}
	
// Delete group
$deleteGroup = getValue("deleteGroup");
if (!empty($deleteGroup)) {
	// Delete group
	$group->deleteGroup();

	// Redirect to group index
	redirect(scriptUrl."/".folderGroups."/".fileGroupIndex);
}

// Save group
$save = getGetValue("save");
if (!empty($save)) {
	$errors = $group->saveGroup();

	// Redirect to group index if there were no errors
	if (!$errors->hasErrors()) redirect(scriptUrl."/".folderGroups);
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderGroups, $lGroupIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderGroups, !empty($group->id)?$lEditGroup["EditGroup"]:$lEditGroup["NewGroup"]);

// Print common header
$site->printHeader();

// Print page description
if (empty ($group->id)) echo "<p>".$lEditGroup["NewGroupText"]."</p>";
else printf("<p>".$lEditGroup["EditGroupText"]."</p>", $group->name);

// Print errors if any
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
}

// Include group form
include "include/form/groupForm.php";

// Print transactions
if (!empty($group->id)) $log->printTransactions(groupContentTypeId, $group->id);

// Print common footer
$site->printFooter();
?>