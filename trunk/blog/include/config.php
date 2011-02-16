<?
// Include news declarations
require_once "declarations.php";

// Determine if blog module is installed
if (!$module->isModuleInstalled("Blog")) redirect(scriptUrl."/".folderBlog."/install/index.php");

// Include Blog classes
include_once scriptPath."/".folderBlog."/include/class/Blog.class.php";
include_once scriptPath."/".folderBlog."/include/class/Post.class.php";

// Check if module has been loaded
if (!defined("blogModuleId") || !defined("blogIndexContentId") || !defined("blogContentId")) {
	// Register modules and content types
	$module->registerModule("Blog","blogModuleId",folderBlog);
	$module->registerModuleContentType("Blog", "Blog", "", "blogContentId", new Blog());
	$module->registerModuleContentType("Blog Post", "Blog", "Blog", "blogPostContentId", new Post());
	
	// Register search type
	$module->registerSearchType("blogContentId");

	// Initialize module
	if (defined("modulesInitialized")) $module->initialize();
}

// Include language
if (file_exists(scriptPath."/".folderBlog."/include/language/".pageLanguage."/general.php")) {
	include_once scriptPath."/".folderBlog."/include/language/".pageLanguage."/general.php";
}

// Check if caching folder for blog exists
if ($settings->enableCaching && method_exists($cache, "createCacheDirectory")) {
	$cache->createCacheDirectory("blog");
}
?>