<?
// Include common functions and declarations
require_once "../include/common.php";
require_once "include/config.php";

// Check for arguments
$blog = new Blog();
$categoryId = -1;
if (!empty($_GET["blogId"])) {
	if (is_numeric($_GET["blogId"])) {
		$blogId = !empty($_GET["blogId"])?$_GET["blogId"]:"";
		$blog = new Blog($blogId);	
	}
}
else if (!empty($_SERVER['REQUEST_URI'])) {
	$parameters = getURLParameters(folderBlog."/".fileBlog);
	if (!empty($parameters[0])) {
		// Get blog object
		$blog = new Blog(!empty($parameters[1])?$parameters[1]:0, $parameters[0]);
		if (empty($blog->id) && !empty($parameters[1])) {
			if ($parameters[1]!="_") {
				$blog = new Blog($parameters[1]);
			}
		}

		// Get category identifier
		if (!empty($blog->id)) {
			if (!empty($parameters[2])) {
				// Include language
				include scriptPath."/".folderBlog."/include/language/".$blog->language."/general.php";

				// Get category identifier
				if ($parameters[2]==urldecode($lBlogPost["Uncategorized"])) {
					$categoryId = 0;
				}
				else {
					$category = new Category(0, $parameters[2]);
					if ($category->id!="") $categoryId = $category->id;
				}
			}
		}
	}
}

// Print blog index
$blog->printBlog($categoryId);
?>