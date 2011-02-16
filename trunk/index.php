<?
// Include common functions and declarations
require_once "include/common.php";

// Get values
$id = getGetValue("pageId");

// If id is empty attempt to fetch sectionId (for backwards compatibility)
if (empty($id)) $id	= getGetValue("sectionId");

// Get parameters for readable URLs
$parameters	= getURLParameters(filePage);

// Create Page object
$page = new Page();
if (!empty($id)) {
	$page->init($id);	
}
else if (sizeof($parameters)>0) {
	if (!empty($parameters[0])) {
		$page->init(!empty($parameters[1])?$parameters[1]:0, $parameters[0]);
		if (empty($page->id) && !empty($parameters[1])) {
			$page->init($parameters[1]);
		}
	}
}

// If page not set create default page
if (empty($page->id)) {
	if (constant("pageDefaultPage")!=0) $page->init(pageDefaultPage);
	else redirect(scriptUrl."/".folderAdmin);
}

// Print page
$page->printPage();
?>