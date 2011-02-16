<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create Category object
$category = new Category(getGetValue("categoryId"));

// Check if current user has edit permissions
if (!$category->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Get referer value if any
$referer = getPostValue("referer");

// Delete category
if(!empty($_POST["deleteCategory"])) {	
	// Delete category
	$category->deleteCategory();

	// Redirect to referer
	redirect(!empty($referer)?$referer:scriptUrl."/".folderCategory."/".fileCategoryIndex);
}
// Save category
else if(!empty($_GET["save"])) {	
	$errors = $category->saveCategory();

	// Redirect to category index
	if ($errors->getNumberOfErrors()==0) {
		redirect(!empty($referer)?$referer:scriptUrl."/".folderCategory."/".fileCategoryIndex);
	}
}

// Generate navigation info
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderCategory, $lCategoryIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderCategory."/".fileCategoryEdit.(!empty($category->id)?"?categoryId=".$category->id:""), empty($category->id)?$lCategoryEdit["NewCategory"]:$lCategoryEdit["EditCategory"]);

// Print common header
$site->printHeader();

// Print text
if(empty($category->id)) echo "<p>".$lCategoryEdit["NewCategoryText"]."</p>";
else printf("<p>".$lCategoryEdit["EditCategoryText"]."</p>",$category->title);

// Print errors if any
if ($errors->getNumberOfErrors()!=0) {
	$errors->printErrorMessages();
}

// Include category form
include scriptPath."/".folderCategory."/include/form/categoryForm.php";

// Print transactions
if(!empty($category->id)) $log->printTransactions(categoryContentTypeId,$category->id);

// Print common footer
$site->printFooter();
?>