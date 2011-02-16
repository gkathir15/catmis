<?
// Get cache content
global $cache,$dbi,$site;	
$content = $cache->getCacheFileContent("", "latestPosts", 3600*4);
if (empty($content)) {
	$postLimit = !empty($postLimit) ? $postLimit : 10;
	$result = $dbi->query("SELECT id FROM ".blogPostTableName." ORDER BY posted DESC LIMIT ".$postLimit);
	if ($result->rows()) {	
		// Include meta header template
		$metaTitle = !empty($metaTitle) ? $metaTitle : "Seneste indlæg";
		include layoutPath."/template/metaHeader.template.php";
		$content = $metaHeader;
		
		for ($i=0; list($id) = $result->fetchrow_array();$i++) {
			$post = new Post($id);
			
			$metaTitle = parseString($post->subject);
			$metaLink = $post->getPostLink();
			$metaInfo = $site->generateTimestamp($post->posted, true);
			$metaCount = -1;
			
			// Include meta body template
			include layoutPath."/template/metaBody.template.php";
			$content .= $metaBody;
		}

		// Include meta footer template
		include layoutPath."/template/metaFooter.template.php";
		$content .= $metaFooter;
	}
	
	// Save output in cache
	$cache->cacheFile("", "latestPosts", $content);
}
echo $content;
?>