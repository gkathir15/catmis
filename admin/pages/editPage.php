<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create page object
$pageObject = new Page(getGetValue("pageId"));

// Determine if user has permission to edit page
if (!$pageObject->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete page
if (!empty($_POST["deletePage"])) {
	// Delete page
	$pageObject->deletePage($pageObject->id);

	// Redirect to page index
	redirect(scriptUrl."/".folderPage."/".filePageIndex);
} 
// Save page
else if (!empty($_GET["save"])) {
	$errors = $pageObject->savePage();
	
	// Redirect to page index if referer is empty
	if (!$errors->hasErrors()) {
		$referer = getPostValue("referer");
		redirect(!empty($referer) ? $referer : $pageObject->getPageLink());
	}
}

// Add navigation links 
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderPage, $lPageIndex["Header"]);
if (!empty($pageObject->id)) {
	$site->addNavigationLink(scriptUrl."/".folderPage."/".filePageEdit."?pageId=".$pageObject->id, $lEditPage["EditPage"]);
}
else {
	$site->addNavigationLink(scriptUrl."/".folderPage."/".filePageEdit, $lEditPage["NewPage"]);
}

// Print common header
$site->printHeader();

// Print header text
echo "<p>";
if (!empty ($pageObject->id)) printf($lEditPage["EditPageText"], $pageObject->title);
else echo $lEditPage["NewPageText"];
echo "</p>";

// Print errors if any
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
}

// Include page form
include "include/form/pageForm.php";

// Print transactions
if (!empty($pageObject->id)) $log->printTransactions(pageContentTypeId, $pageObject->id);

// Print common footer
$site->printFooter();
?>