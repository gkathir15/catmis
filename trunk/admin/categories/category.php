<?
// Include common functions and declarations
require_once "../../include/common.php";

// Get category id
$category = new Category(getGetValue("categoryId"));

// Check if user has edit permission
if(!$category->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete entries
$deleteReferences = getPostValue("deleteReferences");
if(!empty($deleteReferences)) {
	$references = getPostValue("references");
	for($i=0; $i<sizeof($references); $i++) {
		$dbi->query("DELETE FROM `".categoryContentRefTableName."` WHERE id=".$dbi->quote($references[$i]));
	}

	// Redirect to category index 
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderCategory."/".fileCategoryIndex);
}

// Validate page
$page = !empty($_GET["page"])?$_GET["page"]-1:0;

// Generate navigation
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderCategory, $lAdminIndex["Categories"]);
$site->addNavigationLink(scriptUrl."/".folderCategory."/".fileCategory."?categoryId=".$category->id, $category->title);

// Print common header
$site->printHeader();

// Print section header
printf("<p>".$lCategory["HeaderText"]."</p>",$category->title);

// List to keep references in
$references = array();
$count = 0;

// Select references
// Probably not the most efficient way to do things. Especially not with large datasets.
// Necessary because of the structure of the database.
$result = $dbi->query("SELECT id,moduleContentTypeId,moduleContentId FROM ".categoryContentRefTableName." WHERE categoryId=".$dbi->quote($category->id));
if ($result->rows()) {
	$index = 0;
	for ($i=0; list($id,$moduleContentTypeId,$moduleContentId) = $result->fetchrow_array(); $i++) {
		$moduleContentType = $module->getModuleContentTypeObject($moduleContentTypeId);
		if ($moduleContentType!=null) {
			$references[$index][0] = $moduleContentType->getName($moduleContentId);
			$references[$index][1] = $id;
			$references[$index][2] = $moduleContentType->getLink($moduleContentId);
			$references[$index][3] = $moduleContentType->getName();
			$index++;
		}
	}

	// Get number of references
	$count = sizeof($references);

	// Sort by title
	usort($references, create_function('$a,$b','return strcasecmp($a[0],$b[0]);'));
	
	// Splice according to page number
	$references = array_slice($references, $page*30, 30);
}

// Print references
if (sizeof($references)>0) {
?>

<form name="categoryForm" action="<?= scriptUrl."/".folderCategory."/".fileCategory ?>?categoryId=<?= $category->id ?>" method="post">
<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="" class="index">
<tr>
<td class="indexHeader" colspan="2">
&nbsp;
</td>

<td class="indexHeader">
<?= $lCategory["Title"] ?>
</td>

<td class="indexHeader">
<?= $lCategory["Type"] ?>
</td>
</tr>
<?
	// Print lines
	for($i=0;$i<sizeof($references);$i++) {
?>
<tr>
<td height="30" class="item<?= $i%2==0?"Alt":"" ?>">
<input type="checkbox" name="references[]" value="<?= $references[$i][1] ?>" />
</td>

<td width="16" class="item<?= $i%2==0?"Alt":"" ?>">
<img src="<?= iconUrl ?>/section.gif" width="16" height="16" alt="" title="" />
</td>

<td width="60%" class="item<?= $i%2==0?"Alt":"" ?>">
<?
echo '<a href="'.$references[$i][2].'">'.validateTextLength($references[$i][0], 60).'</a>';
?>
</td>

<td width="40%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<?= $references[$i][3] ?>
</td>
</tr>
<?
	}
?>
</table>

<table width="100%">
<tr>
<td>
<input type="submit" name="deleteReferences" value="<?= $lButtons["Delete"] ?>" class="button" onclick="var agree=confirm('<?= $lCategory["ConfirmDelete"] ?>');if(agree) {return true;}else {return false;}" />
</td>

<td align="right">
<input type="button" name="selectNoneButton" value="<?= $lButtons["SelectNone"] ?>" class="button" onclick="selectAll(document.categoryForm, this, false)" /> <input type="button" name="selectAllButton" value="<?= $lButtons["SelectAll"] ?>" class="button" onclick="selectAll(document.categoryForm, this, true)" />
</td>
</tr>
</table>
</form>

<?
	// Generate page links
	echo "<p align=\"center\">";
	echo $site->generatePageLinks(folderCategory."/".fileCategory."?categoryId=".$category->id."&amp;",$page,$count,30);
	echo "</p>";
}
else {		
	echo "<p><i>".$lCategory["NoReferences"]."</i></p>";
}

// Print common footer
$site->printFooter();
?>