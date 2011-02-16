<?
// Include common functions and declarations
require_once "../../include/common.php";
require_once "../include/config.php";

// Create blog object
$blog = new Blog(!empty($_GET["blogId"])?$_GET["blogId"]:"");

// Determine if user has permission to edit blog
if (!$blog->hasAdministerPermission()) {
	$login->printLoginForm();
	exit();
}	

// Include language
include scriptPath."/".folderBlog."/include/language/".(!empty($blog->id)?$blog->language:pageLanguage)."/general.php";

// Insert, update or delete blog
if (!empty($_GET["save"])) {
	if(!empty($_POST["deleteBlog"])) {
		// Delete blog
		$blog->deleteBlog();

		// Redirect to blog index
		redirect(scriptUrl."/".folderBlog."/".fileBlogIndex);
	}
	else  {	
		// Save blog data
		$errors = $blog->saveBlog();
			
		// Redirect
		if (!$errors->hasErrors()) redirect(!empty($_POST["referer"])?trim($_POST["referer"]):scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$blog->id);
	}
}

// Generate navigation info */
$navigation[0][0] = scriptUrl."/".folderBlog;
$navigation[0][1] = "Blogs";
if (!empty($blog->id)) {
	$navigation[1][0] = scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$blog->id;
	$navigation[1][1] = $blog->title;
	$navigation[2][0] = scriptUrl."/".folderBlog."/".fileBlogEdit."?blogId=".$blog->id;
	$navigation[2][1] = $lBlogEdit["EditBlog"];
}
else {
	$navigation[1][0] = scriptUrl."/".folderBlog."/".fileBlogEdit;
	$navigation[1][1] = $lBlogEdit["NewBlog"];
}
	
// Print common header */
printHeader((!empty($blog->id)?$lBlogEdit["EditBlog"]:$lBlogEdit["NewBlog"]),folderBlog,$navigation, true);

// Print subsection header */
if(empty($blog->id)) {
	echo "<p>".$lBlogEdit["NewBlogText"]."</p>";
}
else {
	printf("<p>".$lBlogEdit["EditBlogText"]."</p>",$blog->title);
}

// Print errors */
if($errors->hasErrors()) $errors->printErrorMessages();

// Set current path for text fields
define("currentPath", "../");

// Include blog form */
include scriptPath."/".folderBlog."/include/form/blogForm.php";

// Print transactions */
if(!empty($blog->id)) $log->printTransactions(blogContentId,$blog->id);

// Print common footer
printFooter();
?>