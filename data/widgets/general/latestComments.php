<?
// Get cache content
global $cache,$dbi,$site;	
$content = $cache->getCacheFileContent("", "latestComments", 3600*4);
if (empty($content) || true) {	
	$result = $dbi->query("SELECT id FROM ".commentTableName." ORDER BY posted DESC LIMIT 10");
	if ($result->rows()) {	
		// Include meta header template
		$metaTitle = "Seneste kommentarer";
		include layoutPath."/template/metaHeader.template.php";
		$content = $metaHeader;
		
		for ($i=0; list($id) = $result->fetchrow_array();$i++) {
			$comment = new Comment($id);
			$name = $comment->name;
			if (!empty($comment->userId)) {
				$user = new User($comment->userId);
				$name = $user->name;
			}
			
			$metaTitle = parseString($comment->subject);
			$metaLink = $comment->getCommentLink();
			$metaInfo = $site->generateTimestamp($comment->posted, true)." af ".$name;
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
	$cache->cacheFile("", "latestComments", $content);
}
echo $content;
?>