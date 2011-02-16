<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create page object
$page = new Page(getGetValue("pageId"));

// Determine if user has permission to edit pages
if (!$page->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// If not page identifier was set redirect to page index
if (empty($page->id)) redirect(scriptUrl."/".folderPage."/".folderPageIndex);

// Save page bar
if (!empty($_GET["save"])) {
	$errors = $page->savePageBar();

	// Redirect to page index if referer is empty
	if (!$errors->hasErrors()) {
		$referer = getPostValue("referer");
		redirect(!empty($referer)?$referer:$page->getPageLink());
	}
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderPage."/".filePageIndex, $lPageIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderPage."/".filePageBarEdit."?pageId=".$page->id, $page->title);

// Print common header
$site->printHeader();

// Print header text
printf("<p>".$lEditPage["EditPageText"]."</p>", $page->title);

// Print errors if any
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
}

// Include page form
include scriptPath."/".folderPage."/include/form/pageBarForm.php";

// Print transactions
if (!empty($page->id)) $log->printTransactions(pageContentTypeId, $page->id);

// Print common footer
$site->printFooter();
?>