<?
// Get blog object
global $blog,$post,$settings;
if (!empty($post)) {
	$blog = $post->blog;
}

if (!empty($blog->id)) {
	// Get cache content
	global $cache;	
	$content = $cache->getCacheFileContent("blog", "blogArchive_".$blog->id, 3600*4);
	if (empty($content)) {	
		// Include language
		include scriptPath."/".folderBlog."/include/language/".$blog->language."/general.php";

		$metaTitle = $lBlogMeta["Archives"];
		include layoutPath."/template/metaHeader.template.php";
		$content .= $metaHeader;

		$run = true;
		$today = getdate();
		$metaLinks = 0;
		for ($i=0; $run; $i++) {
			// Get the day name
			$start = mktime(0, 0, 0, $today["mon"]-$i, 1, $today["year"]);
			$end = mktime(0, 0, 0, $today["mon"]-$i+1, 1, $today["year"]);
		
			$result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE draft=0 AND posted>FROM_UNIXTIME($start) AND posted<FROM_UNIXTIME($end) AND blogId=".$dbi->quote($blog->id));
			if ($result->rows()) {
				list ($metaCount) = $result->fetchrow_array();
				if ($metaCount>0) {
					$metaTitle = intToMonth(date("m",$start))." ".date("Y",$start);
					$metaLink = scriptUrl."/".folderBlog."/".fileBlog."?".(!empty($blog->id)?"blogId=".$blog->id."&amp;":"")."month=".date("m",$start)."&amp;year=".date("Y",$start);		
					$metaCount = $metaCount;
					
					include layoutPath."/template/metaBody.template.php";
					$content .= $metaBody;
				}
			}
		
			// Are there more posts beyond this time?
			$result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE draft=0 AND posted<FROM_UNIXTIME($end) AND blogId=".$dbi->quote($blog->id));
			if ($result->rows()) {
				list ($count) = $result->fetchrow_array();
				if ($count==0) {
					$run = false;
				}
			}
		}

		include layoutPath."/template/metaFooter.template.php";
		$content .= $metaFooter;
	
		// Cache file
		$cache->cacheFile("blog", "blogArchive_".$blog->id, $content);
	}
	echo $content;
}
?>
