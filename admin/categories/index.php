<?
// Include common functions and declarations
require_once "../../include/common.php";

// Check if current user has edit permissions
if (!$category->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete categories
$deleteCategories = getValue("delete");
if ($deleteCategories) {
	$categories = getValue("categories");
	for($i=0; $i<sizeof($categories); $i++) {
		$category = new Category($categories[$i]);
		$category->deleteCategory();
	}

	// Redirect to category index 
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderCategory."/".fileCategoryIndex);
}

// Get page number
$pageNumber = getGetValue("page");
if (!empty($pageNumber)) $pageNumber = $pageNumber-1;

// Search string
$searchString =  getValue("searchString");
$searchQuery = "";
if (!empty($searchString)) {
	$searchQuery = (!empty($searchString)?"c1.title LIKE '%".$searchString."%' OR c1.description LIKE '%".$searchString."%'":"");		
}

// Get field to sort by
$sortby = getValue("sortby");
if (!($sortby=="title" || $sortby=="title desc" || $sortby=="description" || $sortby=="description desc" || $sortby=="count" || $sortby=="count desc")) {
	$sortby = "title";
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderCategory, $lAdminIndex["Categories"]);

// Print common header
$site->printHeader();

// Print description
echo "<p>".$lCategoryIndex["HeaderText"]."</p>";

// Fetch categories with refs
$categories = array();
$result = $dbi->query("SELECT c1.id,c1.title,c1.description,COUNT(*) as count FROM ".categoryTableName." as c1,".categoryContentRefTableName." as c2 WHERE c1.id=c2.categoryId".(!empty($searchQuery)?" AND ".$searchQuery:"")." GROUP BY c2.categoryId");
if ($result->rows()) {
	for ($i=0; list($id,$title,$description,$count)=$result->fetchrow_array(); $i++) {
		$categories[$i][0] = $title;
		$categories[$i][1] = $id;
		$categories[$i][2] = $description;
		$categories[$i][3] = $count;
	}
}

// Fetch categories with no refs
$result = $dbi->query("SELECT c1.id,c1.title,c1.description FROM ".categoryTableName." as c1 WHERE c1.id NOT IN(SELECT categoryId FROM ".categoryContentRefTableName.")".(!empty($searchQuery)?" AND ".$searchQuery:""));
if ($result->rows()) {
	for ($i=0; list($id,$title,$description)=$result->fetchrow_array(); $i++) {
		$length = sizeof($categories);
		$categories[$length][0] = $title;
		$categories[$length][1] = $id;
		$categories[$length][2] = $description;
		$categories[$length][3] = 0;
	}	
}

// Get number of references
$count = sizeof($categories);

// Prepare sort function
$sortFunction = 'return ';
switch($sortby) {
	case "title":
		$sortFunction .= 'strcasecmp($a[0],$b[0])';
		break;
	case "title desc":
		$sortFunction .= 'strcasecmp($a[0],$b[0])';
		break;	
	case "description":
		$sortFunction .= 'strcasecmp($a[2],$b[2])';
		break;
	case "description desc":
		$sortFunction .= 'strcasecmp($a[2],$b[2])';
		break;
	case "count":
		$sortFunction .= '$a[3]<$b[3]';
		break;
	case "count desc":
		$sortFunction .= '$a[3]<$b[3]';
		break;
}
$sortFunction .= ';';

// Sort by title
usort($categories, create_function('$a,$b', $sortFunction));

// Reverse array
if ($sortby=="title desc" || $sortby=="description desc" || $sortby=="count desc") {
	$categories = array_reverse($categories);	
}

// Splice according to page number
$categories = array_slice($categories, $pageNumber*30, 30);

if (sizeof($categories)>0) {
	echo '<center>';
	echo '<form action="'.scriptUrl.'/'.folderCategory.'/'.fileCategoryIndex.'" method="post">';
	echo '<input type="text" name="searchString" value="'.$searchString.'" class="normalInput" style="width:150px" /> <input type="submit" value="'.$lGeneral["Search"].'" />';
	echo '</form>';
	echo '</center><br />';

	echo "<form name=\"categoriesForm\" action=\"".scriptUrl."/".folderCategory."/".fileCategoryIndex."\" method=\"post\">";
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" summary=\"\" class=\"index\">";
	echo "<tr>";
	echo "<td colspan=\"2\" class=\"indexHeader\">&nbsp;</td>";
	echo "<td class=\"indexHeader\">";
	echo "<a href=\"".scriptUrl."/".folderCategory."/".fileCategoryIndex."?sortby=title".($sortby=="title"?" desc":"").(!empty($searchString)?"&amp;searchString=".$searchString:"")."\" class=\"columnHeader1\">".$lCategoryIndex["Title"]."</a>".($sortby=="title" || $sortby=="title desc"?' <img src="'.iconUrl.'/sort'.($sortby=="title desc"?"up":"down").'.gif" />':'');
	echo "</td>";
	echo "<td class=\"indexHeader\">";
	echo "<a href=\"".scriptUrl."/".folderCategory."/".fileCategoryIndex."?sortby=description".($sortby=="description"?" desc":"").(!empty($searchString)?"&amp;searchString=".$searchString:"")."\" class=\"columnHeader1\">".$lCategoryIndex["Description"]."</a>".($sortby=="description" || $sortby=="description desc"?' <img src="'.iconUrl.'/sort'.($sortby=="description desc"?"up":"down").'.gif" />':'');
	echo "</td>";
	echo "<td align=\"center\" class=\"indexHeader\">";
	echo "<a href=\"".scriptUrl."/".folderCategory."/".fileCategoryIndex."?sortby=count".($sortby=="count"?" desc":"").(!empty($searchString)?"&amp;searchString=".$searchString:"")."\" class=\"columnHeader1\">".$lCategoryIndex["References"]."</a>".($sortby=="count" || $sortby=="count desc"?' <img src="'.iconUrl.'/sort'.($sortby=="references desc"?"up":"down").'.gif" />':'');
	echo "</td>";
	echo "</tr>";

	for ($i = 0; $i<sizeof($categories); $i ++) {
		echo "<tr>";
		echo "<td height=\"30\" class=\"item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo "<input type=\"checkbox\" name=\"categories[]\" value=\"".$categories[$i][1]."\" />";
		echo "</td>";
		
		echo "<td height=\"30\" width=\"16\" class=\"item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo "<a href=\"".scriptUrl."/".folderCategory."/".fileCategoryEdit."?categoryId=".$categories[$i][1]."&amp;return=1\"><img src=\"".iconUrl."/edit.gif\" border=\"0\" title=\"".$lCategoryEdit["EditCategoryText"]."\" alt=\"".$lCategoryEdit["EditCategoryText"]."\" /></a>";
		echo "</td>";			

		echo "<td width=\"40%\" class=\"item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo "<a href=\"".scriptUrl."/".folderCategory."/".fileCategory."?categoryId=".$categories[$i][1]."\">".parseString($categories[$i][0])."</a>";
		echo "</td>";

		echo "<td width=\"40%\" class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo !empty ($categories[$i][2]) ? parseString($categories[$i][2]) : $lCategoryIndex["NoDescription"];
		echo "</td>";

		echo "<td width=\"20%\" align=\"center\" class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo $categories[$i][3];
		echo "&nbsp;";
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";

	echo "<table width=\"100%\"><tr><td><input type=\"submit\" name=\"delete\" value=\"".$lButtons["Delete"]."\" class=\"button\" onclick=\"var agree=confirm('".$lCategoryIndex["ConfirmDelete"]."');if(agree) {return true;}else {return false;}\" /> </td><td align=\"right\"><input type=\"button\" name=\"selectNoneButton\" value=\"".$lButtons["SelectNone"]."\" class=\"button\" onclick=\"selectAll(document.categoriesForm, this, false)\" /> <input type=\"button\" name=\"selectAllButton\" value=\"".$lButtons["SelectAll"]."\" class=\"button\" onclick=\"selectAll(document.categoriesForm, this, true)\" /></td></tr></table>";
	echo "</form>";

	// Print page index
	echo "<p align=\"center\">";
	echo $site->generatePageLinks(folderCategory."/".fileCategoryIndex."?", $pageNumber, $count, 30);
	echo "</p>";
} 
else {
	echo "<p><i>".$lCategoryIndex["NoCategories"]."</i></p>";
}

// Print common footer
$site->printFooter();
?>