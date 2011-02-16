<?
// Include common functions and declarations
require_once "../include/common.php";
require_once "include/config.php";

// Check for arguments
$post = new Post();
if (!empty($_GET["postId"])) {
	if (is_numeric($_GET["postId"])) {
		$postId = !empty($_GET["postId"])?$_GET["postId"]:"";
		$post = new Post($postId);	
	}
}
else if (!empty($_SERVER['REQUEST_URI'])) {
	$parameters = getURLParameters(folderBlog."/".fileBlogPost);
	if(!empty($parameters[0])) {
		$post = new Post(!empty($parameters[1])?$parameters[1]:0,$parameters[0]);
		if (!empty($parameters[1])) {
			$post = new Post($parameters[1]);
		}
	}
}

// Print post
$post->printPost();
?>