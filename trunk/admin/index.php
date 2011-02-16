<?
// Include common functions and declarations
require_once "../include/common.php";

// Check if user is logged in
if(!$login->isUser()) {
	$login->printLoginForm();
	exit();
}

// Get get values
$message = getGetValue("message");

// Add navigation link
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);

// Print header
$site->printHeader();

// Print page text
if ($login->isAdmin()) {		
	echo "<p>".$lAdminIndex["HeaderText"]."</p>";

	// Print message if any
	if (!empty($message)) {
		echo "<ul><li><b>".$message."</b></li></ul>";
	}
	
	// Print subsection header
	printSubsectionHeader($lAdminIndex["MyProfile"],"",1,1,"profile");
}
else {
	echo "<p>".$lAdminIndex["MyProfileText"]."</p>";

	// Print message if any
	if (!empty($message)) {
		echo "<ul><li><b>".$message."</b></li></ul>";
	}
}

// Print items
echo "<div id=\"profile\">";
$site->printSectionItem($lAdminIndex["MyProfileEdit"], iconUrl."/profile.jpg", scriptUrl."/".folderUsers."/".fileUserProfileEdit."?userId=".$login->id, 0, $lAdminIndex["MyProfileEditText"]);
$site->printSectionItem($lAdminIndex["ChangePassword"], iconUrl."/password.jpg", scriptUrl."/".folderUsers."/".fileUserChangePassword."?userId=".$login->id, 0, $lAdminIndex["ChangePasswordText"]);
$site->printSectionItem($lAdminIndex["Logout"], iconUrl."/logout.jpg", scriptUrl."/".fileLogout, 0, $lAdminIndex["LogoutText"]);
echo "</div>";

// Comments
$comment = new Comment();
if ($login->isAdmin()) $site->registerAdminIndexSection($lAdminIndex["Comments"], $lAdminIndex["CommentsText"], scriptUrl."/".folderComment, iconUrl."/comments.jpg"); 

// Categories
if ($category->hasEditPermission()) $site->registerAdminIndexSection($lAdminIndex["Categories"], $lAdminIndex["CategoriesText"], scriptUrl."/".folderCategory, iconUrl."/log.jpg"); 

// Files
if ($login->isAdmin()) $site->registerAdminIndexSection($lAdminIndex["SharedFiles"], $lAdminIndex["SharedFilesText"], scriptUrl."/".folderFilesAdmin, iconUrl."/files.jpg");		

// Pages
$page = new Page();
if ($page->hasEditPermission()) $site->registerAdminIndexSection($lAdminIndex["Pages"], $lAdminIndex["PagesText"], scriptUrl."/".folderPage, iconUrl."/pages.jpg");	

// Sort by name
sort($site->adminIndexSections);

if (sizeof($site->adminIndexSections)!=0) {
	// Print subsection header
	$site->printSubsectionHeader($lAdminIndex["Content"],"",1,1,"adminContent");
	
	echo '<div id="adminContent">';
	foreach ($site->adminIndexSections as $content) {
		$site->printSectionItem($content["title"], $content["image"], $content["url"], 0, $content["description"]);
	}
	echo "</div>";
}

// Create admin settings array
$adminSettings = array();

// Groups
$group = new Group();
if ($group->hasEditPermission()) {
	$adminSettings[1]["title"] = $lAdminIndex["Groups"];
	$adminSettings[1]["description"] = $lAdminIndex["GroupsText"];
	$adminSettings[1]["url"] = scriptUrl."/".folderGroups;
	$adminSettings[1]["image"] = iconUrl."/groups.jpg";
}

// Log
if ($login->isWebmaster()) {
	$adminSettings[2]["title"] = $lAdminIndex["Log"];
	$adminSettings[2]["description"] = $lAdminIndex["LogText"];
	$adminSettings[2]["url"] = scriptUrl."/".folderLog;
	$adminSettings[2]["image"] = iconUrl."/log.jpg";
}
	
// Users
$user = new User();
if ($user->hasEditPermission()) {
	$adminSettings[3]["title"] = $lAdminIndex["Users"];
	$adminSettings[3]["description"] = $lAdminIndex["UsersText"];
	$adminSettings[3]["url"] = scriptUrl."/".folderUsers;
	$adminSettings[3]["image"] = iconUrl."/users.jpg";		
}

// Sort by name
sort($adminSettings);

if ($login->isWebmaster() || !empty($adminSettings)) {
	// Print subsection header
	$site->printSubsectionHeader($lAdminIndex["Administrator"],"",1,1,"administrator");

	echo '<div id="administrator">';
	if ($login->isWebmaster()) $site->printSectionItem($lAdminIndex["PageSettings"], iconUrl."/settings.jpg", scriptUrl."/".folderSettings, 0, $lAdminIndex["PageSettingsText"]);

	foreach ($adminSettings as $setting) {
		$site->printSectionItem($setting["title"], $setting["image"], $setting["url"], 0, $setting["description"]);
	}
	echo "</div>";
}
	
// Print footer
$site->printFooter();
?>