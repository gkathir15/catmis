<?
global $settings;

// Do we have a cached file?
$cache = cachePath."/menu.txt";
$mtime = 0;
if (file_exists($cache)) $mtime = filemtime($cache);
$age = time()-$mtime;

// Cache for 4 hours
if ($age>(3600*4) || !$settings->enableCaching) {
	$metaTitle = $lGeneral["Pages"];
	include layoutPath."/template/metaHeader.template.php";
	$output = $metaHeader;

	global $page;
	$result = $dbi->query("SELECT id FROM ".pageTableName." WHERE showInMenu=1 AND parentId=0 ORDER BY position");
	if ($result->rows()) {
		$previousSelected = false;
		for($i=0;(list($pageId)=$result->fetchrow_array());$i++) {
			$page1 = new Page($pageId);
			$isParent = !empty($page)?$page1->isParent($page->id):false;

			// Prepare template values		
			$metaCount = -1;	
			$metaTitle = $page1->title;
			$metaLink = $page1->getPageLink();		
			
			// Include template
			include layoutPath."/template/metaBody.template.php";
			$output .= $metaBody;

			$previousSelected = $isParent;
		}
	}
	
	include layoutPath."/template/metaFooter.template.php";
	$output .= $metaFooter;

	// Save output in cache
	$fp = fopen($cache, 'w');
	fwrite($fp, $output);
	fclose($fp);
}
else {
	$output = file_get_contents($cache);
}
echo $output;
?>
