<?
// Get blog object
global $blog,$post;
if (!empty($post)) {
	$blog = $post->blog;
}

if (!empty($blog->id)) {
	// Get cache content
	global $cache;	
	$content = $cache->getCacheFileContent("blog", "blogCategories_".$blog->id, 3600*4);
	if (empty($content)) {	
		// Include language
		include scriptPath."/".folderBlog."/include/language/".$blog->language."/general.php";
	
		// Include meta header template
		$metaTitle = $lBlogMeta["Categories"];
		include layoutPath."/template/metaHeader.template.php";
		$content = $metaHeader;

		// Initialize categories array
		$categories = array();
		$categories[0]["title"] = $lBlogPost["Uncategorized"];
		$categories[0]["id"] = 0;

		$result = $dbi->query("SELECT id,title FROM ".categoryTableName." WHERE id IN(SELECT categoryId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId)." AND moduleContentId IN(SELECT id FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($blog->id).")) ORDER BY title");
		if ($result->rows()) {
			for ($i=0;(list($id,$metaTitle)=$result->fetchrow_array());$i++) {
				$categories[$i+1]["title"] = $metaTitle;
				$categories[$i+1]["id"] = $id;				
			}
		}
		
		// Sort categories
		sort($categories);

		// Get meta body
		for ($i=0; $i<sizeof($categories); $i++) {
			// Get number of posts in category
			$numberOfPosts = 0;
			if (empty($categories[$i]["id"])) $result2 = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE draft=0 AND blogId=".$dbi->quote($blog->id)." AND id NOT IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId).")");
			else $result2 = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE draft=0 AND blogId=".$dbi->quote($blog->id)." AND id IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId)." AND categoryId=".$dbi->quote($categories[$i]["id"]).")");
			if ($result2->rows()) {
				list($numberOfPosts) = $result2->fetchrow_array();						
			}
			
			if ($numberOfPosts!=0) {
				$metaTitle = parseString($categories[$i]["title"]);
				$metaLink = $blog->getBlogCategoryLink($categories[$i]["id"], $categories[$i]["title"]);		
				$metaCount = $numberOfPosts;
				
				// Include meta body template
				include layoutPath."/template/metaBody.template.php";
				$content .= $metaBody;
			}
		}		
		
		// Include meta footer template
		include layoutPath."/template/metaFooter.template.php";
		$content .= $metaFooter;

		// Cache file
		$cache->cacheFile("blog", "blogCategories_".$blog->id, $content);
	}
	echo $content;
}
?>
