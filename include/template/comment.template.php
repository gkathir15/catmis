<a name="<?= $comment->id ?>"></a>
<table width="100%" cellspacing="0" cellpadding="4" class="index"<?= !$lastComment ? ' style="margin-bottom:10px"':'' ?>>
<tr>
<td class="indexHeader">
<?= $comment->subject ?>
</td>
</tr>

<tr>
<td class="small1">
<div id="commentHeader<?= $id ?>" style="margin-bottom:5px">
<span id="commentPosted<?= $id ?>">
<span class="small1" style="color:#666666"><?= printTimestamp($comment->posted,true) ?> by <?= $name.($commentAdmin?" | <a href=\"".scriptUrl."/".folderComment."/".fileCommentEdit."?commentId=".$id."\">".$lComment["EditComment"]."</a>":"") ?></span></span>
</div>
<div id="commentText<?= $id ?>"><?= nl2br($comment->message) ?></div>
</td>
</tr>
</table>