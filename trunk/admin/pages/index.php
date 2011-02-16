<?
// Include common functions and declarations
require_once "../../include/common.php";

// Determine if user has permission to edit page
$pageObject = new Page();
if (!$pageObject->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete pages
if(!empty($_POST["deletePages"])) {
	$pages = getPostValue("pages");
	for($i=0; $i<sizeof($pages); $i++) {
		if (!empty($pages[$i])) {
			$pageObject->init($pages[$i]);
			$pageObject->deletePage();
		}
	}

	// Redirect to page index 
	redirect(scriptUrl."/".folderPage."/".filePageIndex);
}

// Hide pages
if(!empty($_POST["hidePages"])) {
	$pages = getValue("pages");
	for($i=0; $i<sizeof($pages); $i++) {
		if (!empty($pages[$i])) {
			$pageObject->init($pages[$i]);
			$pageObject->setVisible($pages[$i], 0);
		}
	}

	// Redirect to page index 
	redirect(scriptUrl."/".folderPage."/".filePageIndex);
}

// Show pages
if(!empty($_POST["showPages"])) {
	$pages = getValue("pages");
	for($i=0; $i<sizeof($pages); $i++) {
		if (!empty($pages[$i])) {
			$pageObject->init($pages[$i]);
			$pageObject->setVisible($pages[$i], 1);
		}
	}

	// Redirect to page index 
	redirect(scriptUrl."/".folderPage."/".filePageIndex);
}

// Move page up or down	
if (!empty($_GET["pageId"])) {
	$pageObject->init($_GET["pageId"]);
	if (!empty($pageObject->id)) {
		// Move page up or down
		if (!empty($_GET["up"])) {
			$pageObject->movePageUp($pageObject->id);
			redirect(scriptUrl."/".folderPage."/".filePageIndex);
		} 
		else if (!empty($_GET["down"])) {
			$pageObject->movePageDown($pageObject->id);
			redirect(scriptUrl."/".folderPage."/".filePageIndex);
		}
		else if (isset ($_GET["visible"])) {
			$pageObject->setVisible($pageObject->id, $_GET["visible"]);
			redirect(scriptUrl."/".folderPage."/".filePageIndex);
		}
	}
}

// Create page object for parent
$pageObject->init(!empty($_GET["parentId"])?$_GET["parentId"]:0);

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderPage, $lPageIndex["Header"]);

// Print header
$site->printHeader();

// Print page description
echo "<p>".$lPageIndex["HeaderText"]."</p>";

// Include index template header
include scriptPath."/".folderPage."/include/template/pageIndexHeader.php";

// Print page index
$pageObject->printPageIndex(0,0);

// Include index template footer
include scriptPath."/".folderPage."/include/template/pageIndexFooter.php";

// Print common footer
$site->printFooter();
?>