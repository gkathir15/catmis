<?
// Include common functions and declarations
require_once "../include/common.php";
require_once "include/config.php";

// Create blog object
$blog = new Blog(getGetValue("blogId"));

// Print blog index
$blog->printArchives();
?>