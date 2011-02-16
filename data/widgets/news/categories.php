<?
// Get cache content
global $cache;	
$content = $cache->getCacheFileContent("news", "newsCategories", 3600*4);
if (empty($content)) {	
	$news = new News();

	// Include language
	include scriptPath."/".folderNews."/include/language/".pageLanguage."/general.php";

	// Include meta header template
	$metaTitle = $lNews["Categories"];
	include layoutPath."/template/metaHeader.template.php";
	$content = $metaHeader;

	// Initialize categories array
	$categories = array();
	$categories[0]["title"] = $lNews["Uncategorized"];
	$categories[0]["id"] = 0;

	$result = $dbi->query("SELECT id,title FROM ".categoryTableName." WHERE id IN(SELECT categoryId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(newsModuleId)." AND moduleContentTypeId=".$dbi->quote(newsContentId)." AND moduleContentId IN(SELECT id FROM ".newsTableName.")) ORDER BY title");
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
		if (empty($categories[$i]["id"])) $result2 = $dbi->query("SELECT COUNT(*) FROM ".newsTableName." WHERE id NOT IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(newsModuleId)." AND moduleContentTypeId=".$dbi->quote(newsContentId).")");
		else $result2 = $dbi->query("SELECT COUNT(*) FROM ".newsTableName." WHERE id IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(newsModuleId)." AND moduleContentTypeId=".$dbi->quote(newsContentId)." AND categoryId=".$dbi->quote($categories[$i]["id"]).")");
		if ($result2->rows()) {
			list($numberOfPosts) = $result2->fetchrow_array();						
		}
		
		if ($numberOfPosts!=0) {
			$metaTitle = parseString($categories[$i]["title"]);
			$metaLink = $news->getNewsCategoryLink($categories[$i]["id"], $categories[$i]["title"]);		
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
	$cache->cacheFile("news", "newsCategories", $content);
}
echo $content;
?>