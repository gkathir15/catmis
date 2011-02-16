<a name="comments"></a><? printSubsectionHeader($lComment["Header"]." (".$this->getNumberOfComments($moduleId, $moduleContentTypeId, $moduleContentId).")",!empty($rssLink)?"<a href=\"".$rssLink."\"><img src=\"".iconUrl."/rss.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"\" title=\"\" /></a>":"",1,1,"comments"); ?>
<p><? printf($lComment["CommentTextLogged"], $replySubject); ?></p>

<div id="comments" class="formIndent">