<?
/** 
 * Class Post contains values of a post in the blog
 * and methods for adding, updating, deleting and printing posts.
 * @author	Kaspar Rosengreen Nielsen
 */
class Post extends ModuleContentType {
	var $blog = null;
	var $categories = array();
	var $disableComments = false;
	var $draft = false;
	var $lastUpdated = 0;
	var $posted = 0;
	var $showComments = true;
	var $subject = "";
	var $summary = "";
	var $text = "";
	var $user = null;

	/**
	 * Post constructor
	 * @param 	$id			Identifier of post.
	 * @param	$subject	Subject of post (not required).
	 */
	function __construct($id = 0, $subject="") {
		// Initialize values
		$this->init($id, $subject);

		// Call parent constructor
		if (!empty($this->blog->id)) parent::__construct("blogModuleId", "blogPostContentId", "blogContentId", $this->blog->id, $this->user->id);
		else parent::__construct("blogModuleId", "blogPostContentId", "", 0, $this->user->id);
	}

	/** Delete this post and all comments from database */
	function deletePost() {
		if (!empty ($this->id)) {
			if ($this->hasDeletePermission()) {
				global $dbi, $category, $log, $login;
	
				// Check if data is submitted from the form
				checkSubmitter();
	
				// Delete data and info about post
				$dbi->query("DELETE FROM ".blogPostTableName." WHERE id=".$this->id);
	
				// Delete comments made to this post
				$comment = new Comment();
				$comment->deleteComments(blogModuleId, blogPostContentId, $this->id);
	
				// Delete references to categories
				$category->deleteCategoryReferences(blogModuleId, blogPostContentId, $this->id);			

				// Delete blog post permissions
				$login->clearPermissions(blogPostContentId, $this->id);
	
				// Delete data from log table
				$log->deleteTransaction(blogPostContentId, $this->id);
			}
		}
	}
	
	/**
	 * Get time when the post was last updated.
	 * @return	Time of last update.
	 */
	function getLastUpdated() {
		if (!empty($this->id)) {
			global $log;
			return $log->getLastUpdated(blogPostContentId, $this->id);
		}
		return 0;
	}
	
	/**
	 * Get link to a given post.
	 * @param	$id	Identifier of post.
	 * @return	Link to given post.
	 */
	function getLink($id="") {
		if (!empty($id)) {
			$post = new Post($id);
			return $post->getPostLink();
		}
		return scriptUrl."/".folderBlog;
	}

	/**
	 * Get name of a given blog post.
	 * @param	$id	Identifier of blog post.
	 * @return	Name of given blog post.
	 */
	function getName($id="") {
		if (!empty($id)) {
			$post = new Post($id);
			return $post->subject;
		}
		else {
			global $lBlogPost;
			return $lBlogPost["ContentType"];
		}
	}
	
	/**
	 * Get number of search results for a given search string.
	 * @return	Number of search results.
	 */
	function getNumberOfSearchResults($searchString) {
		return 0;
	}	
	
	function getParentContentTypeId() {
		return blogContentId;
	}	
	
	function getParentId($postId) {
		$post = new Post($postId);
		return $post->blog->id;
	}
	
	/**
	 * Get link to blog post.
	 * @return	Link to blog post.
	 */
	function getPostLink() {
		global $dbi,$settings;
		
		if ($settings->linkType==1 || $settings->linkType==3) {
			// Check if post with same title exists
			$multiplePost = false;
			$result = $dbi->query("SELECT subject FROM ".blogPostTableName." WHERE subject=".$dbi->quote($this->subject)." AND id!=".$dbi->quote($this->id));
			if ($result->rows()) {
				$multiplePost = true;
			}
			$result->finish();
			return generateURL(scriptUrl."/".folderBlog."/".fileBlogPost, array($this->subject, $settings->linkType==3 || $multiplePost?$this->id:0));
		}
		return scriptUrl."/".folderBlog."/".fileBlogPost."?postId=".$this->id;
	}
	
	/**
	 * Initialize Post object.
	 * @param	$id			Post identifier.
	 * @param	$subject	Post subject.
	 */
	function init($id=0, $subject="") {
		// Get blogId parameter
		$blogId = getValue("blogId");
		
		// Create Blog object
		$this->blog = new Blog();
		
		// Create User object
		$this->user = new User();
		
		if (!empty($id) || !empty($subject)) {
			global $dbi;

			// Get post data
			$result = $dbi->query("SELECT id,blogId,categoryId,categoryId2,userId,subject,summary,text,UNIX_TIMESTAMP(posted),UNIX_TIMESTAMP(lastUpdated),disableComments,showComments,draft FROM ".blogPostTableName." WHERE ".(!empty($id)?"id=".$dbi->quote($id):"").(!empty($subject)?(!empty($id)?" OR ":"")."subject=".$dbi->quote($subject):"")." ORDER BY posted DESC");
			if ($result->rows()) {
				list ($this->id, $blogId, $categoryId1, $categoryId2, $userId, $this->subject, $this->summary, $this->text, $this->posted, $this->lastUpdated, $this->disableComments, $this->showComments, $this->draft) = $result->fetchrow_array();
				$this->subject = parseString($this->subject);
				$this->summary = parseString($this->summary);
				$this->text = parseString($this->text);
	
				// Create blog object
				$this->blog->init($blogId);

				// Create user object
				$this->user->init($userId);
	
				// Update old categories (<0.5) to new format
				if (!empty($categoryId1)) {
					$category = new Category($categoryId1);
					$category->addCategoryReference(blogModuleId, blogPostContentId, $this->id, $categoryId1, sizeof($this->categories));
					$dbi->query("UPDATE ".blogPostTableName." SET categoryId=0,posted=posted,lastUpdated=lastUpdated WHERE id=".$dbi->quote($this->id));
				}
				if (!empty($categoryId2)) {
					$category = new Category($categoryId2);
					$category->addCategoryReference(blogModuleId, blogPostContentId, $this->id, $categoryId2, sizeof($this->categories));
					$dbi->query("UPDATE ".blogPostTableName." SET categoryId2=0,posted=posted,lastUpdated=lastUpdated WHERE id=".$dbi->quote($this->id));
				}
	
				// Get categories for this post
				$numberOfCategories = 0;
				$result2 = $dbi->query("SELECT c1.title,c2.categoryId,c2.position FROM ".categoryTableName." as c1,".categoryContentRefTableName." as c2 WHERE c1.id=c2.categoryId AND c2.moduleId=".$dbi->quote(blogModuleId)." AND c2.moduleContentTypeId=".$dbi->quote(blogPostContentId)." AND c2.moduleContentId=".$dbi->quote($this->id));
				if ($result2->rows()) {
					for ($i=0; list($categoryTitle,$categoryId,$categoryPosition)=$result2->fetchrow_array(); $i++) {
						$this->categories[$i][0] = $categoryPosition;
						$this->categories[$i][1] = stripslashes($categoryTitle);
						$this->categories[$i][2] = $categoryId;
					}
					$numberOfCategories = $result2->rows();
				}
				
				// Sort by position
				sort($this->categories);
	
				// Convert old <img> tags for summary (CMIS 0.1-0.2)
				for ($i = 1; $i <= 4; $i ++) {
					// Replace img-tags with html-tags
					if (file_exists(scriptPath."/".folderUploadedFiles."/blog/img_".$this->blog->id."_".$this->id."_$i.jpg")) {
						$size = GetImageSize(scriptPath."/".folderUploadedFiles."/blog/img_".$this->blog->id."_".$this->id."_$i.jpg");
						$this->summary = preg_replace("/\<img$i\>(.*?)\<\/img$i\>/si", "<img name=\"img$i\" src=\"".scriptUrl."/".folderUploadedFiles."/blog/img_".$this->blog->id."_".$this->id."_$i.jpg\" $size[3] border=\"0\" alt=\"$1\" title=\"$1\" />", $this->summary);
						$this->text = preg_replace("/\<img$i\>(.*?)\<\/img$i\>/si", "<img name=\"img$i\" src=\"".scriptUrl."/".folderUploadedFiles."/blog/img_".$this->blog->id."_".$this->id."_$i.jpg\" $size[3] border=\"0\" alt=\"$1\" title=\"$1\" />", $this->text);
					}
				}
	
				// Free resultset
				$result->finish();
				$result2->finish();
			}
		}
		else if (!empty($blogId)) {
			global $login;
			
			// Create blog object
			$this->blog->init($blogId);
			
			// Set posted to now
			$this->posted = mktime();
			
			// Set user to this user
			$this->user->init($login->id);
		}
	}

	/** 
	 * Notify subscribers about new posts per mail
	 * @param	$from		Name in from field.
	 * @param 	$subject 	Mail subject
	 * @param 	$message 	Mail message.
	 */
	function notifySubscribers($from, $subject, $message) {
		global $dbi,$login;

		// Send mail to subscribers
		$to = "";
		$subscribers = explode(",", $this->blog->subscribers);
		for ($i = 0; $i < sizeof($subscribers); $i ++) {
			$to .= ($i != 0 ? "," : "")."$subscribers[$i]";
		}
				
		// Create plain text version
		$h2t =& new html2text($message);

		// Create PHPMailer object
		$mail = new phpmailer();
		
		$user = new User($login->id);
		
		// Set mail values
		$mail->CharSet = "UTF-8";
		$mail->From     = $user->email;
		$mail->FromName = $user->name;
		$mail->Subject 	= $subject;
		$mail->Body = $message;
		$mail->AltBody = $h2t->get_text();
	    
		// Send mail to subscribers
		if (sizeof($subscribers)>0) {
			for ($i=0; $i<sizeof($subscribers); $i++) {
			    $mail->AddAddress($subscribers[$i]);						    
			    $mail->Send();
			    
			    // Clear all addresses for next loop
			    $mail->ClearAddresses();
			}
		}
	}

	/** Print post */
	function printPost() {
		if (!empty($this->id)) {
			if ($this->hasReadPermission()) {
				global $dbi, $errors, $login, $site;
	
				// If this post is a draft and user doesn't have edit permission - print login form
				if ($this->draft && !$this->hasEditPermission()) {
					$login->printLoginForm();
					exit();
				}

				// Include language
				include scriptPath."/include/language/".$this->blog->language."/general.php";
				include scriptPath."/".folderBlog."/include/language/".$this->blog->language."/general.php";
				
				// Create comment object
				$comment = new Comment();
	
				// Add comment to database
				if (!empty($_GET["addComment"])) {
					$errors = $comment->saveComment(blogModuleId, blogPostContentId, $this->id);
					if (!$errors->hasErrors()) redirect($this->getPostLink()."#comments");
				}

				// Set page title
				$site->setTitle($this->subject);

				// Set meta description
				$site->setMetaDescription(!empty($this->summary) ? $this->summary : (!empty($this->text) ? $this->text : pageDescription));
				
				// Set page path
				$site->setPath(folderBlog);
													
				// Add meta links
				if ($this->hasEditPermission()) $site->addMetaLink(scriptUrl."/".folderBlog."/".fileBlogPostEdit."?postId=".$this->id, $lBlogPost["Edit"], "edit");
				$site->addMetaLink($this->getPostLink(), "", "direct");			
				$site->addMetaLink($this->getPostLink()."&amp;print=1", "", "print");
				$site->addMetaLink(scriptUrl."/".fileSendToFriend."?url=".urlencode($this->getPostLink())."&amp;title=".urlencode($this->subject), "", "recommend");

				// Add navigation links
				$site->addNavigationLink(scriptUrl."/".folderBlog."/".fileBlogIndex, $lBlogIndex["Header"]);
				$site->addNavigationLink($this->blog->getBlogLink(), $this->blog->title);
				$site->addNavigationLink($this->getPostLink(), $this->subject);

				// Register rss feeds
				$site->registerRSSFeed(scriptUrl."/".folderBlog."/".fileBlogPostRSS."?blogId=".$this->blog->id, $this->blog->title);
				$site->registerRSSFeed(scriptUrl."/".folderBlog."/".fileBlogCommentRSS."?blogId=".$this->blog->id, $this->blog->title." - ".$lBlogPost["Comments"]);
				
				// Print header
				$site->printHeader();
				
				// Print blog header
				echo "<p>".$this->blog->description;
				if ($this->hasEditpermission()) {
					printf(" ".$lBlog["NewPost"], $this->blog->id);
				}
				echo "</p>";
	
				// Print post body
				$this->printPostBody();
				
				// Print comments
				if ($this->showComments) {
					$comment = new Comment();
					$comment->disableComments = $this->disableComments;
					$comment->errors = $errors;
					$comment->language = $this->blog->language;
					$comment->rssLink = scriptUrl."/".folderBlog."/".fileBlogCommentRSS."?postId=".$this->id;
	
					// Print comments
					$comment->printComments(blogModuleId, blogPostContentId, $this->id, $this->subject, scriptUrl."/".folderBlog."/".fileBlogPost."?postId=".$this->id, folderBlog."/".fileBlogPost."?postId=".$this->id);
				}
				
				// Print common footer
				$site->printFooter();
			}
			else {
				$login->printLoginForm();
				exit();	
			}
		}
		else {
			redirect(scriptUrl."/".folderBlog);	
		}
	}

	/**
	 * Print blog post body.
	 * @param	$template	Template to use when printing.
	 */
	function printPostBody($template="") {
		if (!empty($this->id)) {
			global $login, $site;

			// Include language
			include scriptPath."/include/language/".$this->blog->language."/general.php";
			include scriptPath."/".folderBlog."/include/language/".$this->blog->language."/general.php";
						
			// Prepare variables
			$authorLink = $site->generatePopupLink(scriptUrl."/".fileUserProfile."?profileId=".$this->user->id."&amp;popup=1",$this->user->name);
			$categoryLinks = "";
			if (sizeof($this->categories)!=0) {
				for ($i=0; $i<sizeof($this->categories); $i++) {
					$categoryLinks .= ($i!=0?", ":"")."<a href=\"".$this->blog->getBlogCategoryLink($this->categories[$i][2], $this->categories[$i][1])."\">".$this->categories[$i][1]."</a>";
				}
			}
			else {
				$categoryLinks .= "<a href=\"".$this->blog->getBlogCategoryLink(0)."\">".$lBlogPost["Uncategorized"]."</a>";
			}
			$editLink = $this->hasEditPermission()?"<a href=\"".scriptUrl."/".folderBlog."/".fileBlogPostEdit."?postId=".$this->id."\" alt=\"".$lBlogPost["Edit"]."\" title=\"".$lBlogPost["Edit"]."\">".$lBlogPost["Edit"]."</a>":"";
			$posted = $site->generateTimestamp($this->posted, false, $shortTimeFormat);
			$readMoreLink = "";
			$subject = $this->subject;
			$text = parseBodyText(!empty($this->text)?$this->text:$this->summary);
			$draft = $this->draft;

			// Include template
			if (file_exists(layoutPath."/template/".folderBlog."/blogPost".$template.".template.php")) {
				include layoutPath."/template/".folderBlog."/blogPost".$template.".template.php";
			}
			else {
				include scriptPath."/".folderBlog."/include/template/blogPost.template.php";
			}
		}
	}

	/** Print post summary */
	function printPostSummary($template="") {
		if (!empty ($this->id)) {
			global $dbi, $login, $site;
			global $lComment;

			// Include language
			include scriptPath."/include/language/".$this->blog->language."/general.php";
			include scriptPath."/".folderBlog."/include/language/".$this->blog->language."/general.php";

			// Prepare variables
			$authorLink = $site->generatePopupLink(scriptUrl."/".fileUserProfile."?profileId=".$this->user->id."&amp;popup=1",$this->user->name);
			$categoryLinks = "";
			if (sizeof($this->categories)!=0) {
				for ($i=0; $i<sizeof($this->categories); $i++) {
					$categoryLinks .= ($i!=0?", ":"")."<a href=\"".$this->blog->getBlogCategoryLink($this->categories[$i][2],$this->categories[$i][1])."\">".$this->categories[$i][1]."</a>";
				}
			}
			else {
				$categoryLinks .= "<a href=\"".$this->blog->getBlogCategoryLink(0)."\">".$lBlogPost["Uncategorized"]."</a>";
			}
			$comment = new Comment();
			$numberOfComments = $comment->getNumberOfComments(blogModuleId, blogPostContentId, $this->id);
			$commentsLink = $this->showComments?"<a href=\"".$this->getPostLink()."#comments\" alt=\"".$lBlogPost["Comments"]."\" title=\"".$lBlogPost["Comments"]."\">".$numberOfComments." ".($numberOfComments==1?$lComment["Comment"]:$lComment["Comments"])."</a>":"";
			$editLink = $this->hasEditPermission()?"<a href=\"".scriptUrl."/".folderBlog."/".fileBlogPostEdit."?postId=".$this->id."\" title=\"".$lBlogPost["Edit"]."\">".$lBlogPost["Edit"]."</a>":"";
			$posted = $site->generateTimestamp($this->posted, false, $shortTimeFormat);
			$readMoreLink = !empty($this->summary) && !empty($this->text)?'<a href="'.$this->getPostLink().'">'.$lBlogPost["ReadMore"].'</a>':'';
			$subject = $this->subject;
			$summary = parseBodyText(!empty($this->summary)?$this->summary:$this->text);
			$draft = $this->draft;

			// Include template
			if (file_exists(layoutPath."/template/".folderBlog."/blogPostSummary.".(!empty($template)?$template.".":"")."template.php")) {
				include layoutPath."/template/".folderBlog."/blogPostSummary.".(!empty($template)?$template.".":"")."template.php";				
			}
			else {
				include scriptPath."/".folderBlog."/include/template/blogPostSummary.".(!empty($template)?$template.".":"")."template.php";
			}
		}
	}

	/** Print RSS blog post summary. */
	function printRSSPostSummary() {
		if (!empty ($this->id)) {
			global $dbi, $login;

			// Include language
			include scriptPath."/".folderBlog."/include/language/".$this->blog->language."/general.php";

			// Prepare variables
			$categoryLinks = "";
			if (sizeof($this->categories)!=0) {
				for ($i=0; $i<sizeof($this->categories); $i++) {
					$categoryLinks .= ($i!=0?", ":"")."<a href=\"".$this->blog->getBlogCategoryLink($this->categories[$i][2],$this->categories[$i][1])."\">".$this->categories[$i][1]."</a>";
				}
			}
			else {
				$categoryLinks .= "<a href=\"".$this->blog->getBlogCategoryLink(0)."\">".$lBlogPost["Uncategorized"]."</a>";
			}

			$comment = new Comment();
			$commentsLink = $this->showComments?"<a href=\"".$this->getPostLink()."#comments\" alt=\"".$lBlogPost["Comments"]."\" title=\"".$lBlogPost["Comments"]."\">".$comment->getNumberOfComments(blogModuleId, blogPostContentId, $this->id)." ".$lBlogPost["Comments"]."</a>":"";
			$readMoreLink = !empty($this->summary) && !empty($this->text)?'<a href="'.scriptUrl.'/'.folderBlog.'/'.fileBlogPost.'?postId='.$this->id.'">'.$lBlogPost["ReadMore"].'</a>':'';
			$subject = $this->subject;
			$text = parseBodyText(!empty($this->text)?$this->text:$this->summary);

			// Output summary
			$summary = 	"<p>".$lBlogPost["PostedIn"]." ".$categoryLinks."</p>".
						$text.
						"<p>".(!empty($readMoreLink)?$readMoreLink:"").(!empty($commentsLink)?(!empty($readMoreLink)?" | ":"").$commentsLink:"")."</p>";
			return $summary;
		}
		return "";
	}
	
	/**
	 * Print search results.
	 * @param	$searchString
	 * @param	$limit
	 * @param	$page
	 * @param	$viewAll
	 */
	function printSearchResults($searchString, $limit=0, $page=0, $viewAll=0) {}

	/** 
	  * Save blog post. 
	  * @param	$readPost	Read values from post.
 	  * @return ErrorLog object if there were errors.
	  */	
	function savePost($readPost=true) {
		global $category, $dbi, $log, $login;
		
		// Check if data is submitted from the form
		if ($readPost) checkSubmitter();
		
		// Create ErrorLog object
		$errorLog = new ErrorLog();
		
		// Get blog id and create blog object
		$this->blog = new Blog(getValue("blogId"));

		// Check if blog exists
		if(!empty($this->blog->id)) {
			if ($this->hasEditPermission()) {
				// Include language
				include scriptPath."/".folderBlog."/include/language/".$this->blog->language."/general.php";

				// Save if post was draft before
				$draftBefore = $this->draft;

				// Save blog post values
				if ($readPost) {
					$this->categories = explode(",",getPostValue("categories"));
					$this->disableComments = getPostValue("disableComments");
					$this->draft = getPostValue("draft");
					$this->showComments = getPostValue("showComments");
					$this->subject = parseHtml(getPostValue("subject"),1);
					$this->summary = parseThumbnailImages(parseHtml(getPostValue("summary"),4));
					$this->text = parseThumbnailImages(parseHtml(getPostValue("text"),4));
					$userId = getPostValue("userId");

					// Get publication time
					$day = getPostValue("day");
					$month = getPostValue("month");
					$year = getPostValue("year");
					$hour = getPostValue("hour");
					$minute = getPostValue("minute");
					
					// Process input
					if (!empty($day) && !empty($month) && !empty($year)) $this->posted = mktime($hour, $minute, 0, $month, $day, $year);
					if (!empty($userId)) $this->user = new User($userId);
				}
				
				// Validate post data
				if (empty($this->posted)) $this->posted = mktime();
				if (empty($userId)) $this->user = new User($login->id);
				if (empty($this->subject)) $errorLog->addError("subject", $lBlogEditPost["MissingSubject"]);
	
				// Check if post has been modified
				$lastUpdated = getValue("lastUpdated");
				if ($lastUpdated!=$this->getLastUpdated()) $errorLog->addError("postModified", $lBlogEditPost["PostModified"]);
				
				// Prepare values for notification
				$subject = "[".$this->blog->title."] ".$this->subject;
				$message = "<p>".$lBlogEditPost["NotifyInsert"].
						   " '".$this->blog->title."'.</p>".
						   "<p><b>".$lBlogEditPost["Name"]."</b></p><p>".$login->name."</p>".
						   "<p><b>".$lBlogEditPost["Subject"]."</b></p><p>".$this->subject."</p>".
						   "<p><b>".$lBlogEditPost["Summary"]."</b></p>".parseString((!empty($this->summary)?$this->summary:(!empty($this->text)?$this->text:""))).
						   "<p>--<br />".
						   $lBlogEditPost["ReadPost"].": ".$this->getPostLink()."<br />".
						   $lBlogEditPost["VisitBlog"].": ".$this->blog->getBlogLink()."</p>";
				$sender = $login->name;
		
				// If no errors proceed, otherwise return errors
				if (!$errorLog->hasErrors()) {
					if (empty($this->id)) {						
						// Insert into database
						$dbi->query("INSERT INTO ".blogPostTableName."(blogId,userId,subject,summary,text,posted,lastUpdated,showComments,disableComments,draft) VALUES(".$dbi->quote($this->blog->id).",".$dbi->quote($this->user->id).",".$dbi->quote($this->subject).",".$dbi->quote($this->summary).",".$dbi->quote($this->text).",FROM_UNIXTIME(".$dbi->quote($this->posted)."),NOW(),".$dbi->quote($this->showComments).",".$dbi->quote($this->disableComments).",".$dbi->quote($this->draft).")");
	
						// Get new post id
						$this->id = $dbi->getInsertId();

						// Notify subscribers about the new post
						if (!$this->draft) {
							$this->notifySubscribers($sender, $subject, $message);
						}
					}
					else {
						// Update values in database
						$dbi->query("UPDATE ".blogPostTableName." SET blogId=".$dbi->quote($this->blog->id).",userId=".$dbi->quote($this->user->id).",subject=".$dbi->quote($this->subject).",summary=".$dbi->quote($this->summary).",text=".$dbi->quote($this->text).",posted=FROM_UNIXTIME(".$dbi->quote($this->posted)."),lastUpdated=lastUpdated,showComments=".$dbi->quote($this->showComments).",disableComments=".$dbi->quote($this->disableComments).",draft=".$dbi->quote($this->draft)." WHERE id=".$dbi->quote($this->id));

						// Notify subscribers if the post was a draft previously
						if (!$this->draft && $draftBefore) {
							$this->notifySubscribers($sender, $subject, $message);
						}												
					}

					// Associate categories with this post
					$category->addCategoryReferences(blogModuleId, blogPostContentId, $this->id, $this->categories);
	
					// Log transaction
					$log->logTransaction(blogPostContentId, $this->id);
					
					// Delete cached files
					if (!empty($this->blog)) $this->blog->deleteCache();
				}
			}
		}
		return $errorLog;
	}
}
