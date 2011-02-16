<?
// Get cache content
global $cache,$dbi,$site;	
$content = $cache->getCacheFileContent("news", "newsLatest", 3600*4);
if (empty($content)) {	
	$result = $dbi->query("SELECT id FROM ".newsTableName." WHERE draft=0 ORDER BY posted DESC LIMIT 5");
	if ($result->rows()) {	
		$content = "<table cellspacing=\"0\" cellpadding=\"0\">";
		for ($i=0; list($id) = $result->fetchrow_array();$i++) {
			$news = new News($id);			
			if ($i==0) {
				$imageUrl = $news->getNewsImage(145);
				$dimensions = getImageDimensions($imageUrl);
				$content .= "<tr><td valign=\"top\" style=\"padding-bottom:8px\" colspan=\"2\"><a href=\"".$news->getNewsLink()."\"><img src=\"".$imageUrl."\" height=\"".$dimensions[1]."\" width=\"145\" border=\"0\" style=\"border:1px #cccccc solid\" alt=\"\" title=\"\" /></a></td></tr>";
			}
			$content .= "<tr><td valign=\"top\" style=\"padding-bottom:8px\">&nbsp;»&nbsp;</td><td style=\"padding-bottom:8px\"><a href=\"";
			$content .= $news->getNewsLink();
			$content .= "\" class=\"menu1\">";
			$content .= $news->title;
			$content .= "</a><br /><span class=\"small1\" style=\"color:#666666\">";
			$content .= $site->generateTimestamp($news->posted,true);
			$content .= "</span></td></tr>";
		}
		$content .= "</table>";
	}
				
	// Save output in cache
	$cache->cacheFile("news", "newsLatest", $content);
}
echo $content;
?>