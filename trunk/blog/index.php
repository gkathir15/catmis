<?
// Include common functions and declarations
require_once "../include/common.php";
require_once "include/config.php";

// Create blog object
$blog = new Blog(!empty($_GET["blogId"])?$_GET["blogId"]:"");

// Move blog up or down
if($blog->hasEditPermission()) {
	if(!empty($blog->id)) {
		if($_GET["up"]) {
			$blog->moveBlogUp($blog->id);
			redirect($_SERVER["HTTP_REFERER"]);
		}
		else if($_GET["down"]) {
			$blog->moveBlogDown($blog->id);
			redirect($_SERVER["HTTP_REFERER"]);
		}
	}
}

// Include language
include_once scriptPath."/".folderBlog."/include/language/".pageLanguage."/general.php";

// Generate navigation
$navigation[0][0] = scriptUrl."/".folderBlog;
$navigation[0][1] = $lBlogIndex["Header"];

// Print common header
printHeader($lBlogIndex["Header"],folderBlog,$navigation,true);

// Print section header
echo "<p>".$lBlogIndex["HeaderText"].($blog->hasEditPermission()?" ".$lBlogIndex["NewBlog"]."</p>":"");

// Fetch blogs the user can administer
$result = $dbi->query("SELECT category FROM ".blogTableName." GROUP BY category");

// Print categories
if($result->rows()) {
	for($i=0;(list($category) = $result->fetchrow_array());$i++) {
		$result2 = $dbi->query("SELECT id FROM ".blogTableName." WHERE (category=".$dbi->quote($category).") ORDER BY position");

		// Print blogs in category
		if($result2->rows()) {
			$headerPrinted = false;
			for($j=0;(list($blogId)=$result2->fetchrow_array());$j++) {
				$blogIndex = new Blog($blogId);

				if ($blogIndex->hasReadPermission()) {
					if (!$headerPrinted) {
						if (!empty($category)) printSubsectionHeader($category);
						$headerPrinted=true;
						echo "<table width=\"100%\">";					
					}
					echo "<tr>";
					echo "<td height=\"60\" valign=\"top\" width=\"50\">";
					if(file_exists(scriptPath."/".folderUploadedFiles."/blog_".$blogIndex->id.".jpg")) {
						echo "<a href=\"".$blogIndex->getBlogLink()."\"><img src=\"".scriptUrl."/".folderUploadedFiles."/blog_".$blogIndex->id.".jpg\" height=\"50\" width=\"50\" border=\"0\" alt=\"".$blogIndex->title."\" title=\"".$blogIndex->title."\" class=\"border\" /></a>";
					}
					else {
						echo "<a href=\"".$blogIndex->getBlogLink()."\"><img src=\"".iconUrl."/picture5050.gif\" height=\"50\" width=\"50\" border=\"0\" alt=\"".$blogIndex->title."\" title=\"".$blogIndex->title."\" class=\"border\" /></a>";
					}		
					echo "</td>";
					echo "<td valign=\"top\" height=\"35\" width=\"100%\">";
					echo "<a href=\"".$blogIndex->getBlogLink()."\"><b>".(!empty($blogIndex->title)?$blogIndex->title:"&nbsp;")."</b> (".$blogIndex->getNumberOfPosts().")</a><br />";
					echo !empty($blogIndex->description)?$blogIndex->description:$lBlogIndex["NoDescription"];
					if($blogIndex->hasEditPermission()) {
						echo " <span class=\"small1\">";
						if ($result2->rows()>1) {
							echo " <a href=\"".scriptUrl."/".folderBlog."/index.php?blogId=".$blogIndex->id."&amp;up=1\">".$lBlogIndex["MoveUp"]."</a>";
							echo " <a href=\"".scriptUrl."/".folderBlog."/index.php?blogId=".$blogIndex->id."&amp;down=1\">".$lBlogIndex["MoveDown"]."</a>";
						}
						echo " <a href=\"".scriptUrl."/".folderBlog."/".fileBlogEdit."?blogId=".$blogIndex->id."&amp;return=1\">".$lBlogIndex["EditBlog"]."</a>";
						echo "</span>";
					}
					echo "</td>";
					echo "</tr>";
				}
			}
			if ($headerPrinted) echo "</table>";			
		}
	}
}
else {
	echo "<p><i>".$lBlogIndex["NoBlogs"]."</i></p>";
}

// Print links
echo "<br /><br /><br />";
if(pageShowDirectLink) printDirectLink(scriptUrl."/".folderBlog);
if(pageShowPrinterLink) printPrinterLink(scriptUrl."/".folderBlog."/index.php?print=1");

// Print common footer
printFooter(folderBlog);		
?>
