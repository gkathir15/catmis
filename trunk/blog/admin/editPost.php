<?
// Include common functions and declarations
require_once "../../include/common.php";
require_once "../include/config.php";

// Create post object
$post = new Post(!empty($_GET["postId"])?$_GET["postId"]:"");

// Determine if user has permission to edit blog
if (!$post->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Include language
include scriptPath."/".folderBlog."/include/language/".(!empty($post->blog->id)?$post->blog->language:pageLanguage)."/general.php";

// Insert, update or delete blog
if (!empty($_GET["save"])) {
	if(!empty($_POST["deletePost"])) {
		// Delete post
		$post->deletePost();

		// Redirect to blog index
		redirect(!empty($_POST["referer"])?$_POST["referer"]:scriptUrl."/".fileBlog."/".fileBlog."?blogId=".$blog->id);
	}
	else {
		// Save post data
		$errors = $post->savePost();

		// Redirect to blog index
		if (!$errors->hasErrors()) redirect(!empty($_POST["referer"])?$_POST["referer"]:$post->blog->getBlogLink());
	}
}

// Generate navigation info
$navigation["0"]["0"] = scriptUrl."/".folderBlog;
$navigation["0"]["1"] = $lBlogIndex["Header"];
$navigation["1"]["0"] = scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$post->blog->id;
$navigation["1"]["1"] = $post->blog->title;
if (!empty($post->id)) {
	$navigation["2"]["0"] = scriptUrl."/".folderBlog."/".fileBlogPost."?postId=".$post->id;
	$navigation["2"]["1"] = $post->subject;
	$navigation["3"]["0"] = scriptUrl."/".folderBlog."/".fileBlogPostEdit."?postId=".$post->id;
	$navigation["3"]["1"] = $lBlogEditPost["EditPost"];
}
else {
	$navigation["2"]["0"] = scriptUrl."/".folderBlog."/".fileBlogPostEdit;
	$navigation["2"]["1"] = $lBlogEditPost["NewPost"];		
}

// Print common header
printHeader((!empty($post->id)?$lBlogEditPost["EditPost"]:$lBlogEditPost["NewPost"]),folderBlog,$navigation, true);

// Print description
if(empty($post->id)) echo "<p>".$lBlogEditPost["NewPostText"]."</p>";
else printf("<p>".$lBlogEditPost["EditPostText"]."</p>",$post->subject);

// Print errors
if($errors->hasErrors()) $errors->printErrorMessages();
		
// Set current path for text fields
define("currentPath", "../");
			
// Include post form
include scriptPath."/".folderBlog."/include/form/postForm.php";

// Print transactions
if(!empty($post->id)) $log->printTransactions(blogPostContentId,$post->id);

// Print common footer
printFooter();
?>