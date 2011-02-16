<?
// Include common functions and declarations
require_once "../../include/common.php";
require_once "../include/config.php";

// Create blog object
$blog = new Blog(!empty($_GET["blogId"])?$_GET["blogId"]:0);

if (!empty($blog->id)) {
	// Include language
	include scriptPath."/".folderBlog."/include/language/".$blog->language."/general.php";
	
	// Protect page
	if (!empty ($blog->userlevel))
		protectPage($blog->userlevel);
	
	// Get the post list
	$items = array();
	$result = $dbi->query("SELECT id FROM ".blogPostTableName." WHERE blogId=".$blog->id." AND draft=0 ORDER BY posted DESC LIMIT 15");
	for ($i = 0;(list ($id) = $result->fetchrow_array()); $i ++) {
		$post = new Post($id);
		
		// Get categories
		$categories = array();
		for ($i=0; $i<sizeof($post->categories); $i++) {
			$categories[] = $post->categories[$i][1];
		}
		
		// Create new item
		$item = new RSSItem($post->id,
							$post->user->name, 
							$categories, 
							$post->getPostLink()."#comments",
							scriptUrl."/".folderBlog."/".fileBlogCommentRSS."?postId=".$post->id,
							$post->getPostLink(), 
							stripHtml(!empty($post->text)?$post->text:$post->summary),
							$post->printRSSPostSummary(), 
							$post->posted, 
							$post->subject);
		$items[] = $item;
	}

	// Print feed
	$rss = new RSS($blog->title, $blog->description, $blog->getBlogLink(), scriptUrl."/".folderBlog."/".fileBlogPostRSS."?blogId=".$blog->id, $items);
	$rss->printRSSFeed();
}
?>