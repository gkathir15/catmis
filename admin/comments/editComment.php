<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create comment object
$comment = new Comment(getGetValue("commentId"));

// Check if user has edit permission
if (!$comment->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete comment
$deleteComment = getValue("deleteComment");
if (!empty($deleteComment)) {
	// Delete comment
	$comment->deleteComment();

	// Redirect to page
	redirect($module->getModuleContentTypeLink($comment->moduleContentTypeId, $comment->moduleContentId)."#comments");
}
else if (!empty($_GET["save"])) {
	$moduleId = getPostValue("moduleId");
	$moduleContentTypeId = getPostValue("moduleContentTypeId");
	$moduleContentId = getPostValue("moduleContentId");
	
	if (!empty($moduleId) && !empty($moduleContentTypeId) && !empty($moduleContentId)) {
		$errors = $comment->saveComment($moduleId, $moduleContentTypeId, $moduleContentId);

		// Redirect to page
		if (!$errors->hasErrors()) {
			$referer = getValue("referer");
			if (empty($referer)) $referer = $module->getModuleContentTypeLink($comment->moduleContentTypeId, $comment->moduleContentId)."#comments";;
			redirect($referer);
		}
	}
	else {
		redirect(scriptUrl."/".folderComment."/".fileCommentIndex);
	}
}

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderComment, $lCommentIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderComment."/".fileCommentEdit."?commentId=".$comment->id, $lEditComment["Header"]);

// Print common header
$site->printHeader();

// Print description
if(empty($comment->id)) echo "<p>".$lEditComment["NewCommentDescription"]."</p>";
else printf("<p>".$lEditComment["HeaderText"]."</p>", !empty($comment->subject)?$comment->subject:$lEditComment["NoSubject"]);

// Print errors if any
if ($errors->hasErrors()) {
	$errors->printErrorMessages();
}

// Include comment form
include scriptPath."/".folderComment."/include/form/commentForm.php";

// Print common footer
$site->printFooter();
?>
