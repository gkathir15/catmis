<?
// Include common functions and declarations 
require_once "../../include/common.php";

// Create Comment object
$comment = new Comment();

// Check if user has edit permission
if (!$comment->hasAdministerPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete comments
$deleteComments = getValue("deleteComments");
if(!empty($deleteComments)) {
	$comments = getValue("comments");
	if (!empty($comments)) {
		for($i=0; $i<sizeof($comments); $i++) {
			$comment = new Comment($comments[$i]);
			$comment->deleteComment();
		}
	}

	// Redirect
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderComment."/".folderCommentIndex);
}

// Delete spam comments
$deleteSpamComments = getPostValue("deleteSpamComments");
if(!empty($deleteSpamComments)) {
	$comment = new Comment();
	$comment->deleteSpamComments();

	// Redirect
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderComment."/".folderCommentIndex);
}

// Delete trash comments
$deleteTrashComments = getPostValue("deleteTrashComments");
if(!empty($deleteTrashComments)) {
	$comment = new Comment();
	$comment->deleteTrashComments();

	// Redirect
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderComment."/".folderCommentIndex);
}

// Mark comments as spam or not spam
$spam = getValue("spam");
$notSpam = getValue("notSpam");
if(!empty($spam) || !empty($notSpam)) {
	$comments = getValue("comments");
	if (!empty($comments)) {
		for($i=0;$i<sizeof($comments);$i++) {
			$comment = new Comment($comments[$i]);
			$comment->setSpamStatus(!empty($spam)?1:0);
		}
	}
	else {
		$commentId = getValue("commentId");
		if (!empty($commentId)) {
			$comment = new Comment($_GET["commentId"]);
			$comment->setSpamStatus($spam);
		}		
	}

	// Redirect
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderComment."/".folderCommentIndex);
}

// Get page number 
$pageNumber = getGetValue("page");;
if (!empty($pageNumber)) $pageNumber = $pageNumber-1;

// Get module content type
$moduleContentTypeId = getGetValue("moduleContentTypeId");
$moduleContentId = getGetValue("moduleContentId");

// Get comment type
$commentType = getValue("commentType");

// Search string
$searchString = getValue("searchString");
$searchQuery = "";
if (!empty($searchString)) {
	/* TODO: Support searching for names of registered users */
	$searchQuery = (!empty($searchString)?"(".
										  "name LIKE '".$searchString."%' OR ".
									      "name LIKE '%".$searchString."%' OR ".
									      "name LIKE '%".$searchString."' OR ".
										  "subject LIKE '".$searchString."%' OR ".
										  "subject LIKE '%".$searchString."%' OR ".
										  "subject LIKE '%".$searchString."' OR ".		
										  "message LIKE '".$searchString."%' OR ".
										  "message LIKE '%".$searchString."%' OR ".
										  "message LIKE '%".$searchString."'".
										  ")"
					:"");		
}

// Get field to sort by
$sortby = getValue("sortby");
if (!($sortby=="subject" || $sortby=="subject desc" || $sortby=="name" || $sortby=="name desc" || $sortby=="posted" || $sortby=="posted desc")) {
	$sortby = "posted desc";
}

// Generate navigation info 
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderComment, $lCommentIndex["Header"]);

// Print common header 
$site->printHeader();

// Print description
$request = !empty($moduleContentTypeId) && !empty($moduleContentId) ? (!empty($request)?"&amp;":"?")."moduleContentTypeId=".$moduleContentTypeId."&amp;moduleContentId=".$moduleContentId : "";
$request .= showPopup ? (!empty($request) ? "&amp;":"?")."popup=1" : "";
$request .= !empty($sortby)? (!empty($request) ? "&amp;":"?") . "sortby=".$sortby:"";
$link = scriptUrl."/".folderComment."/".fileCommentIndex.(!empty($request)?$request."&amp;":"?").(!empty($commentType)?"&amp;commentType=".$commentType:"");
printf("<p>".$lCommentIndex["HeaderText"]."</p>", $link);

//$request .= !empty($commentType) ? (!empty($request) ? "&amp;":"?") . "commentType=".$commentType : "";

echo '<center>';
echo '<form action="'.scriptUrl.'/'.folderComment.'/'.fileCommentIndex.$request.'" method="post">';
echo '<input type="text" name="searchString" value="'.$searchString.'" class="normalInput" style="width:150px" /> ';
echo '<select name="commentType"><option value="0"'.($commentType==0 ? ' selected="selected"' : '').'>'.$lCommentIndex["PublishedComments"].'</option><option value="1"'.($commentType==1 ? ' selected="selected"' : '').'>'.$lCommentIndex["DeletedComments"].'</option><option value="2"'.($commentType==2 ? ' selected="selected"' : '').'>'.$lCommentIndex["SpamComments"].'</option></select>';
echo '<input type="submit" value="'.$lGeneral["Search"].'" />';
echo '</form>';
echo '</center><br />';

// Fetch comments
$result = $dbi->query("SELECT id FROM ".commentTableName." WHERE ". (!empty($moduleContentTypeId) && !empty($moduleContentId) ? "moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId) . " AND " : "") .($commentType==1 ? "trash=1":($commentType==2 ? "spam=1" : "spam=0 AND trash=0")).(!empty($searchQuery)?" AND ".$searchQuery:"")." ORDER BY ".$sortby." LIMIT ". ($pageNumber * 30).",30");
if ($result->rows()) {
	$request .= !empty($searchString)? (!empty($request) ? "&amp;":"?")."searchString=".$searchString:"";
	
	echo "<form name=\"commentsForm\" action=\"".scriptUrl."/".folderComment."/".fileCommentIndex.$request."\" method=\"post\">";
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" border=\"0\" summary=\"\" class=\"index\">";
	echo "<tr>";
	echo "<td class=\"indexHeader\">&nbsp;</td>";
	echo "<td class=\"indexHeader\">&nbsp;</td>";
	echo "<td class=\"indexHeader\">";
	echo "<a href=\"".scriptUrl."/".folderComment."/".fileCommentIndex.(!empty($request)?$request."&amp;":"?")."sortby=subject".($sortby=="subject"?" desc":"")."\" class=\"columnHeader1\">".$lCommentIndex["Subject"]."</a>".($sortby=="subject" || $sortby=="subject desc"?' <img src="'.iconUrl.'/sort'.($sortby=="subject desc"?"up":"down").'.gif" />':'');
	echo "</td>";
	echo "<td class=\"indexHeader\">";
	echo "<a href=\"".scriptUrl."/".folderComment."/".fileCommentIndex.(!empty($request)?$request."&amp;":"?")."sortby=name".($sortby=="name"?" desc":"")."\" class=\"columnHeader1\">".$lCommentIndex["Name"]."</a>".($sortby=="name" || $sortby=="name desc"?' <img src="'.iconUrl.'/sort'.($sortby=="name desc"?"up":"down").'.gif" />':'');
	echo "</td>";
	echo "<td class=\"indexHeader\">";
	echo "<a href=\"".scriptUrl."/".folderComment."/".fileCommentIndex.(!empty($request)?$request."&amp;":"?")."sortby=posted".($sortby=="posted"?" desc":"")."\" class=\"columnHeader1\">".$lCommentIndex["Posted"]."</a>".($sortby=="posted" || $sortby=="posted desc"?' <img src="'.iconUrl.'/sort'.($sortby=="posted desc"?"up":"down").'.gif" />':'');
	echo "</td>";
	echo "<td class=\"indexHeader\" align=\"center\"><b>".$lCommentIndex["Spam"]."</b></td>";
	echo "</tr>";

	$comment = new Comment();
	for ($i = 0;(list ($id) = $result->fetchrow_array()); $i ++) {
		$comment->init($id);
		$moduleContentTypeObject = $module->getModuleContentTypeObject($comment->moduleContentTypeId);
		echo "<tr>";
		echo "<td height=\"30\" class=\"item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo "<input type=\"checkbox\" name=\"comments[]\" value=\"".$comment->id."\" />";
		echo "</td>";

		echo "<td width=\"16\" class=\"item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo "<a href=\"".scriptUrl."/".folderComment."/".fileCommentEdit."?commentId=".$comment->id."&amp;return=1&amp;".(showPopup?"popup=1":"return=1")."\"><img src=\"".iconUrl."/edit.gif\" border=\"0\" title=\"\" alt=\"\" /></a>";
		echo "</td>";
		
		echo "<td width=\"40%\" class=\"item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo "<a href=\"".$moduleContentTypeObject->getLink($comment->moduleContentId)."#".$comment->id."\"".(showPopup?" target=\"_blank\"":"").">". (!empty ($comment->subject) ? validateTextLength($comment->subject,30) : "-")."</a>";
		echo "</td>";

		echo "<td width=\"40%\" nowrap=\"nowrap\" class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\">";
		if (!empty($comment->userId)) {
			$user = new User($comment->userId);
			$name = printPopup(scriptUrl."/".fileUserProfile."?profileId=".$user->id."&amp;popup=1", $user->name);
		}
		else {
			$name = !empty ($comment->mail) ? "<a href=\"mailto:".$comment->mail."\">". (!empty ($comment->name) ? $comment->name : "-")."</a>" : (!empty ($comment->name) ? $comment->name : "-");
		}
		echo $name;
		echo "</td>";

		echo "<td width=\"20%\" nowrap=\"nowrap\" class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo $site->generateTimestamp($comment->posted, true);
		echo "</td>";

		echo "<td class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\" align=\"center\">";
		echo !$comment->spam?"<a href=\"".scriptUrl."/".folderComment."/".fileCommentIndex."?commentId=".$comment->id."&amp;spam=1\"><img src=\"".iconUrl."/spam.gif\" border=\"0\" alt=\"".$lButtons["MarkSpam"]."\" title=\"".$lButtons["MarkSpam"]."\" /></a>":"<a href=\"".scriptUrl."/".folderComment."/".fileCommentIndex."?commentId=".$comment->id."&amp;spam=0\"><img src=\"".iconUrl."/notspam.gif\" border=\"0\" alt=\"".$lButtons["MarkNotSpam"]."\" title=\"".$lButtons["MarkNotSpam"]."\" /></a>";
		echo "</td>";		
		echo "</tr>";
		
		echo "<tr>";
		echo "<td colspan=\"2\" class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\">&nbsp;</td>";
		echo "<td colspan=\"4\" class=\"small1 item". ($i % 2 == 0 ? "Alt":"")."\">";
		echo parseString($comment->message);
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";

	echo "<table width=\"100%\"><tr><td><input type=\"submit\" name=\"deleteComments\" value=\"".$lButtons["Delete"]."\" class=\"button\" onclick=\"var agree=confirm('".$lCommentIndex["ConfirmDelete"]."');if(agree) {return true;}else {return false;}\" />".($commentType==2?" <input type=\"submit\" name=\"deleteSpamComments\" value=\"".$lButtons["DeleteAll"]."\" class=\"button\" onclick=\"var agree=confirm('".$lCommentIndex["ConfirmDeleteSpam"]."');if(agree) {return true;}else {return false;}\" />":"").($commentType==1?" <input type=\"submit\" name=\"deleteTrashComments\" value=\"".$lButtons["DeleteAll"]."\" class=\"button\" onclick=\"var agree=confirm('".$lCommentIndex["ConfirmDeleteTrash"]."');if(agree) {return true;}else {return false;}\" />":"")." <input type=\"submit\" name=\"".($commentType==2?"spam":"notSpam")."\" value=\"".($commentType==2?$lButtons["Spam"]:$lButtons["NotSpam"])."\" class=\"button\" /></td><td align=\"right\"><input type=\"button\" name=\"selectNoneButton\" value=\"".$lButtons["SelectNone"]."\" class=\"button\" onclick=\"selectAll(document.commentsForm, this, false)\" /> <input type=\"button\" name=\"selectAllButton\" value=\"".$lButtons["SelectAll"]."\" class=\"button\" onclick=\"selectAll(document.commentsForm, this, true)\" /></td></tr></table>";
	echo "</form>";

	// Print page index 
	echo "<br /><center>";
	echo $site->generatePageLinks(folderComment."/".fileCommentIndex.(!empty($request)?$request."&amp;":""), $pageNumber, $commentType==1 ?$comment->getNumberOfTrashComments(0,$moduleContentTypeId,$moduleContentId,$searchQuery) : ($commentType==2?$comment->getNumberOfSpamComments(0,$moduleContentTypeId,$moduleContentId,$searchQuery):$comment->getNumberOfComments(0,$moduleContentTypeId,$moduleContentId,$searchQuery)), 30);
	echo "</center>";
} 
else {
	echo "<p><i>".$lCommentIndex["NoComments"]."</i></p>";
}

// Print common footer 
$site->printFooter();
?>