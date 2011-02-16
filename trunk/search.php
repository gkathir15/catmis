<?
// Include common functions and declarations
require_once "include/common.php";

// Get get values
$down = getGetValue("down");
$id = getGetValue("id");
$pageNumber = getGetValue("page");
$position = getGetValue("position");
$up = getGetValue("up");
$visible = getGetValue("visible");

// Get search string values
$searchString = parseString(getValue("searchString"));
$searchType = parseString(getValue("searchType"));

// Set limits for search results based on search type
$viewAll = !empty($searchType)?true:false;
$limit = $viewAll?15:5;	

// Count page number down by 1
if (!empty($pageNumber)) $pageNumber = $pageNumber-1;

// Move search section up or down
if (!empty($id) && !empty($position)) {
	if (!empty($up)) {
		$result = $dbi->query("SELECT id,position FROM ".searchTypeTableName." WHERE position<".$dbi->quote($position)." ORDER BY position DESC LIMIT 1");	
		if($result->rows()) {
			list($swapId,$swapPos)=$result->fetchrow_array();
			$dbi->query("UPDATE ".searchTypeTableName." SET position=".$dbi->quote($swapPos)." WHERE id=".$dbi->quote($id));
			$dbi->query("UPDATE ".searchTypeTableName." SET position=".$dbi->quote($position)." WHERE id=".$dbi->quote($swapId));
		}
		redirect(scriptUrl."/".fileSearch.(!empty($searchString)?"?searchString=".$searchString:""));
	}
	else if (!empty($down)) {
		$result = $dbi->query("SELECT id,position FROM ".searchTypeTableName." WHERE position>".$dbi->quote($position)." ORDER BY position LIMIT 1");	
		if($result->rows()) {
			list($swapId,$swapPos)=$result->fetchrow_array();
			$dbi->query("UPDATE ".searchTypeTableName." SET position=".$dbi->quote($swapPos)." WHERE id=".$dbi->quote($id));
			$dbi->query("UPDATE ".searchTypeTableName." SET position=".$dbi->quote($position)." WHERE id=".$dbi->quote($swapId));
		}		
		redirect(scriptUrl."/".fileSearch.(!empty($searchString)?"?searchString=".$searchString:""));
	}
}

// Hide or show search section
if (!empty($id) && ($visible==0 || $visible==1)) {
	$dbi->query("UPDATE ".searchTypeTableName." SET visible=".$dbi->quote($visible)." WHERE id=".$dbi->quote($id));
	redirect(scriptUrl."/".fileSearch.(!empty($searchString)?"?searchString=".$searchString:""));
}

// Add navigation link
$site->addNavigationLink(scriptUrl."/".fileSearch, $lSearch["Header"]);

// Print common header
$site->printHeader();

// Print search text
printf("<p>".$lSearch["HeaderText"]."</p>",$searchString);
?>

<center>
<form action="<?= scriptUrl."/".fileSearch ?>" method="get">
<input type="text" value="<?= $searchString ?>" name="searchString" class="shortInput" /> 
<?
$minPosition = 0;
$maxPosition = 0;

// Get search types from database
$result = $dbi->query("SELECT s.moduleContentTypeId,s.position,s.visible FROM ".searchTypeTableName." as s,".moduleContentTypeTableName." as m WHERE s.moduleContentTypeId=m.id".(!$login->isWebmaster() ? " AND s.visible=1":"") ." ORDER BY s.position");
if ($result->rows()) {
?>
<select name="searchType" class="normalInput">
<option value="0"><?= $lSearch["AllSearchTypes"] ?></option>
<?
	for ($i=0; list($id, $position, $visible) = $result->fetchrow_array(); $i++) {
		if ($position<$minPosition || empty($minPosition)) $minPosition = $position;
		if ($position>$maxPosition) $maxPosition = $position;
		$moduleContentType = $module->getModuleContentTypeObject($id);
		if ($moduleContentType!=null) {
			if (method_exists($moduleContentType, "getName")) {
?>
<option value="<?= $id ?>"<?= $searchType==$id?" selected=\"selected\"":"" ?>><?= $moduleContentType->getName() ?></option>
<? } ?>
<? } ?>
<? } ?>
</select>
<? } ?> <input type="submit" value="<?= $lSearch["Header"] ?>" class="button" />
</form>
</center>

<?
if(!empty($searchString)) {
	// Make the search an AND search	
	$searchTerms = explode(" ",$searchString);

	// Get search types ordered by position
	$result = $dbi->query("SELECT s.id,s.moduleContentTypeId,s.position,s.visible FROM ".searchTypeTableName." as s,".moduleContentTypeTableName." as m WHERE s.moduleContentTypeId=m.id".(!$login->isWebmaster()?" AND s.visible=1":"")." ORDER BY s.position");
	if ($result->rows()) {
		for ($i=0; list ($searchTypeId, $id, $position, $visible) = $result->fetchrow_array(); $i++) {
			if (empty($searchType) || $searchType==$id) {
				$moduleContentType = $module->getModuleContentTypeObject($id);

				if ($moduleContentType!=null) {
					if (!method_exists($moduleContentType, "getName") || !method_exists($moduleContentType, "getModuleContentTypeId")) continue;

					// Generate up and down links
					$moveUpDown = "";
					if ($login->isWebmaster() && empty($searchType)) {
						$moveUpDown .= '<a href="'.scriptUrl.'/'.fileSearch.'?searchString='.$searchString.'&amp;id='.$searchTypeId.'&amp;visible='.($visible?0:1).'"><img src="'.iconUrl.'/'.($visible?"visible":"hidden").'.gif" height="16" width="16" border="0" alt="'.$lSearch[(!$visible?"MakeVisible":"MakeInvisible")].'" title="'.$lSearch[(!$visible?"MakeVisible":"MakeInvisible")].'" /></a> ';
						if ($position==$minPosition) {
							$moveUpDown .= '<img src="'.iconUrl.'/up_disabled.gif" height="16" width="16" border="0" alt="'.$lSearch["MoveUp"].'" title="'.$lSearch["MoveUp"].'" />';
						}
						else {
							$moveUpDown .= '<a href="'.scriptUrl.'/'.fileSearch.'?searchString='.$searchString.'&amp;id='.$searchTypeId.'&amp;position='.$position.'&amp;up=1"><img src="'.iconUrl.'/up.gif" height="16" width="16" border="0" alt="'.$lSearch["MoveUp"].'" title="'.$lSearch["MoveUp"].'" /></a>';
						}	
						if ($position==$maxPosition) {
							$moveUpDown .= '<img src="'.iconUrl.'/down_disabled.gif" height="16" width="16" border="0" alt="'.$lSearch["MoveDown"].'" title="'.$lSearch["MoveDown"].'" />';
						}
						else {
							$moveUpDown .= '<a href="'.scriptUrl.'/'.fileSearch.'?searchString='.$searchString.'&amp;id='.$searchTypeId.'&amp;position='.$position.'&amp;down=1"><img src="'.iconUrl.'/down.gif" height="16" width="16" border="0" alt="'.$lSearch["MoveDown"].'" title="'.$lSearch["MoveDown"].'" /></a>';
						}
					}
	
					// Get number of search results
					$count = $moduleContentType->getNumberOfSearchResults($searchString);
	
					// Print subsection header
					$site->printSubsectionHeader($moduleContentType->getName(), $moveUpDown, 1, 1, "searchResults".$moduleContentType->getModuleContentTypeId());

					echo '<div id="searchResults'.$moduleContentType->getModuleContentTypeId().'">';
					if (!empty($count)) {
						if ($viewAll) printf("<p>".$lSearch["DisplayingResults"]."</p>", $pageNumber*$limit+1, ($pageNumber*$limit+$limit<$count?$pageNumber*$limit+$limit:$count), $count);
						else printf("<p>".$lSearch["DisplayingResults"]."</p>", 1, $limit<$count?$limit:$count, $count);
				
						echo "<ul>";	
						if (!empty($searchType)) {
							$moduleContentType->printSearchResults($searchString, 15, $pageNumber, true);
						}
						else {
							$moduleContentType->printSearchResults($searchString, 5);
						}
						echo "</ul>";
		
						if ($count>$limit && $viewAll) {
							echo "<p align=\"center\">";
							echo $site->generatePageLinks(fileSearch."?searchString=".$searchString."&amp;searchType=".$moduleContentType->getModuleContentTypeId()."&amp;", $pageNumber, $count, $limit);
							echo "</p>";
						}
						else if ($count>$limit && !$viewAll) {
							printf('<p>'.$lSearch["ViewAllResults"].'</p>', scriptUrl."/".fileSearch."?searchString=".$searchString."&amp;searchType=".$moduleContentType->getModuleContentTypeId());
						}
					}
					else {
						echo "<p>".$lSearch["NoSearchResult"]."</p>";
					}
					echo '</div>';		
				}		
			}
		}
	}
}

// Print common footer
$site->printFooter();
?>
