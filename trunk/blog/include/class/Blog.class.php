<?
/** 
 * Class Blog is the main blog object and contains methods for adding, deleting
 * and updating blogs. It also contains methods for printing various kinds of
 * indexes.
 * @author	Kaspar Rosengreen Nielsen
 */
class Blog extends ModuleContentType implements ModuleSearchType {
	var $category = "";
	var $commentLimit = 10;
	var $description = "";
	var $indexLimit = 20;
	var $language = "en";
	var $position = 0;
	var $postLimit = 10;
	var $showRSSLink = 0;
	var $showRSSCommentsLink = 0;
	var $subscribers = "";
	var $title = "";

	/** 
	 * Blog constructor
	 * @param 	$id 	Blog id
	 */
	function __construct($id=0, $title="") {
		parent::__construct("blogModuleId", "blogContentId");

		// Initialize values
		$this->init($id, $title);
	}

	/** Delete this blog from database. */
	function deleteBlog() {
		if (!empty ($this->id)) {
			if ($this->hasAdministerPermission()) {
				global $dbi, $log, $login;
	
				// Check if data has been submitted from the form
				checkSubmitter();
	
				// Delete blog index image
				if(file_exists(scriptPath."/".folderUploadedFiles."/blog_".$this->id.".jpg")) unlink(scriptPath."/".folderUploadedFiles."/blog_".$this->id.".jpg");
	
				// Delete posts
				$result = $dbi->query("SELECT id FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id));
				if ($result->rows()) {
					for ($i=0; list($id)=$result->fetchrow_array(); $i++) {
						$post = new Post($id);
						$post->deletePost();
					}
				}
	
				// Delete blog permissions
				$login->clearPermissions(blogContentId, $this->id);
	
				// Delete blog data
				$dbi->query("DELETE FROM ".blogTableName." WHERE id=".$this->id);
	
				// Delete log data
				$log->deleteTransaction(blogContentId, $this->id);
			}
		}
	}

	/** Delete cached blog files. */
	function deleteCache() {
		if (!empty($this->id)) {
			global $cache;
			$cache->deleteCacheFile("blog", "blogCategories_".$this->id);
			$cache->deleteCacheFile("blog", "blogArchive_".$this->id);
		}
	}
	
	/**
	 * Get link to blog.
	 * @return	Link to blog.
	 */
	function getBlogLink() {
		global $dbi,$settings;
		
		if ($settings->linkType==1 || $settings->linkType==3) {
			// Check if blog with same title exists
			$multiple = false;
			$result = $dbi->query("SELECT title FROM ".blogTableName." WHERE title=".$dbi->quote($this->title)." AND id!=".$dbi->quote($this->id));
			if ($result->rows()) {
				$multiple = true;
			}
			return generateURL(scriptUrl."/".folderBlog."/".fileBlog, array($this->title, $settings->linkType==3 || $multiple?$this->id:0));
		}
		return scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$this->id;
	}
	
	/**
	 * Get link to blog category.
	 * @return	Link to blog category.
	 */
	function getBlogCategoryLink($categoryId, $categoryTitle="") {
		global $dbi,$settings;		

		if ($settings->linkType==1 || $settings->linkType==3) {
			// Check if blog with same title exists
			$multiple = false;
			$result = $dbi->query("SELECT title FROM ".blogTableName." WHERE title=".$dbi->quote($this->title)." AND id!=".$dbi->quote($this->id));
			if ($result->rows()) {
				$multiple = true;
			}
			
			if ($categoryId!=0) {
				if (empty($categoryTitle)) {
					$result = $dbi->query("SELECT id,title FROM ".categoryTableName." WHERE id=".$dbi->quote($categoryId));
					if ($result->rows()) {
						list($categoryId,$categoryTitle) = $result->fetchrow_array();	
					}
				}
				if (!empty($categoryTitle)) {
					return generateURL(scriptUrl."/".folderBlog."/".fileBlog, array($this->title, ($multiple || $settings->linkType==3?$this->id:"_"), $categoryTitle));
				}
			}
			else {
				// Include language
				include scriptPath."/".folderBlog."/include/language/".$this->language."/general.php";								
				return generateURL(scriptUrl."/".folderBlog."/".fileBlog, array($this->title, ($multiple || $settings->linkType==3?$this->id:"_"), $lBlogPost["Uncategorized"]));
			}
			
			// Free result set
			$result->finish();
		}
		return scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$this->id."&amp;categoryId=".$categoryId;
	}

	/**
	 * Get link to given blog.
	 * @param	$id	Identifier of blog.
	 * @return	Link to given blog.
	 */
	function getLink($id="") {
		if (!empty($id)) {
			$blog = new Blog($id);
			return $blog->getBlogLink();
		}
		return scriptUrl."/".folderBlog;
	}
	
	/**
	 * Get name of given blog.
	 * @param	$id	Identifier of blog.
	 * @return	Name of given blog.
	 */
	function getName($id="") {
		if (!empty($id)) {
			$blog = new Blog($id);
			return $blog->title;
		}
		else {
			global $lBlog;
			return $lBlog["ContentType"];
		}
	}
	
	/** 
	 * Get number of blogs
	 * @return int number of blogs
	 */	
	function getNumberOfBlogs() {
		global $dbi,$login;

		$result = $dbi->query("SELECT COUNT(*) FROM ".blogTableName);
		if ($result->rows()) {
			list ($count) = $result->fetchrow_array();
			return $count;
		}
		return 0;
	}

	/** 
	 * Get number of posts in this blog
	 * @return int number of posts
	 */	
	function getNumberOfPosts() {
		if (!empty($this->id)) {
			global $dbi;
	
			$result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE blogId=".$this->id);
			if ($result->rows()) {
				list ($count) = $result->fetchrow_array();
				return $count;
			}
		}
		return 0;
	}
	
	/**
	 * Get number of search results with a given search string.
	 * @param	$searchString	Search string to get number of results for.
	 * @return	Number of search results.
	 */	
	function getNumberOfSearchResults($searchString) {
		global $dbi;
		
		// Fetch post hits
		$count = "";
		$result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE MATCH(subject, summary, text) AGAINST ('$searchString' IN BOOLEAN MODE) AND draft=0");
		if ($result->rows()) {
			list($count) = $result->fetchrow_array();
		}
		return $count;
	}

	/** 
	 * Initialize object.
	 * @param	$id		Blog identifier.
	 * @param	$title	Blog title.
	 */
	function init($id=0, $title="") {	
		if (!empty($id) || !empty($title)) {
			global $dbi;

			// Get blog data
			$result = $dbi->query("SELECT id,title,category,description,subscribers,language,postLimit,commentLimit,showRSSLink,showRSSCommentsLink,position FROM ".blogTableName." WHERE ".(!empty($id)?"id=".$dbi->quote($id):"").(!empty($title)?(!empty($id)?" OR ":"")."title=".$dbi->quote(addslashes($title)):""));
			if ($result->rows()) {
				list ($this->id, $this->title, $this->category, $this->description, $this->subscribers, $this->language, $this->postLimit, $this->commentLimit, $this->showRSSLink, $this->showRSSCommentsLink, $this->position) = $result->fetchrow_array();

				// Parse text
				$this->title = parseString($this->title);
				$this->description = parseString($this->description);
			}
			$result->finish();
		}	
	}
	
	/** Move blog down */
	function moveBlogDown() {
		global $dbi;

		$result = $dbi->query("SELECT id,position FROM ".blogTableName." WHERE position>".$this->position." AND category='".$this->category."' ORDER BY position LIMIT 1");
		if ($result->rows()) {
			list ($swapId, $swapPos) = $result->fetchrow_array();
			$dbi->query("UPDATE ".blogTableName." SET position='$swapPos' WHERE id=".$this->id);
			$dbi->query("UPDATE ".blogTableName." SET position='".$this->position."' WHERE id='$swapId'");
		}
	}

	/** Move blog up */
	function moveBlogUp() {
		global $dbi;

		$result = $dbi->query("SELECT id,position FROM ".blogTableName." WHERE position<".$this->position." AND category='".$this->category."' ORDER BY position DESC LIMIT 1");
		if ($result->rows()) {
			list ($swapId, $swapPos) = $result->fetchrow_array();
			$dbi->query("UPDATE ".blogTableName." SET position='$swapPos' WHERE id=".$this->id);
			$dbi->query("UPDATE ".blogTableName." SET position='".$this->position."' WHERE id='$swapId'");
		}
	}
	
	/** Print blog archives */
	function printArchives() {
		global $dbi, $login, $site;
		if (!empty($this->id)) {
			if ($this->hasReadPermission()) {		
				// Include language
				include scriptPath."/".folderBlog."/include/language/".$this->language."/general.php";
	
				// Add meta links
				if ($this->hasAdministerPermission()) {
					$site->addMetaLink(scriptUrl."/".folderBlog."/".fileBlogEdit."?blogId=".$this->id, $lBlog["EditBlog"], "edit"); 	
					$site->addMetaLink(scriptUrl."/".folderUsers."/".fileEditPermissions."?moduleContentTypeId=".blogContentId."&amp;moduleContentId=".$this->id, $lBlog["EditPermissions"], "permission");
				}
				$site->addMetaLink(scriptUrl."/".folderBlog."/".fileBlogArchive."?blogId=".$this->id, "direct");
				$site->addMetaLink(scriptUrl."/".folderBlog."/".fileBlogArchive."?blogId=".$this->id."&amp;print=1", "print");
		
				// Add navigation links
				$site->addNavigationLink(scriptUrl."/".folderBlog, $lBlogIndex["Header"]);
				$site->addNavigationLink($this->getBlogLink, $this->title);
				$site->addNavigationLink(scriptUrl."/".folderBlog."/".fileBlogArchive."?blogId=".$this->id, $lBlogArchive["Header"]);
	
				// Set website path
				$site->setPath(folderBlog);
	
				// Print common header
				$site->printHeader();

				// Print description
				printf($lBlogArchive["HeaderText"], $this->title);
				
				// Print subsection header
				$site->printSubsectionHeader($lBlogArchive["BrowseCategory"]);
				
				echo "<table width=\"100%\"><tr>";
				$result = $dbi->query("SELECT id,title FROM ".categoryTableName." WHERE id IN(SELECT categoryId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId)." AND moduleContentId IN(SELECT id FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id).")) ORDER BY title");
				for ($i=0;(list($categoryId,$categoryTitle)=$result->fetchrow_array());$i++) {
					if ($i%3==0) echo "</tr><tr>";
					
					// Get number of posts in category
					$numberOfPosts = 0;
					$result2 = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id)." AND id IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId)." AND categoryId=".$dbi->quote($categoryId).")");
					if ($result2->rows()) {
						list($numberOfPosts) = $result2->fetchrow_array();						
					}				
					echo "<td width=\"33%\"><a href=\"".$this->getBlogCategoryLink($categoryId)."\">".$categoryTitle."</a> <span style=\"font-size:80%;color:#666666\">(".$numberOfPosts.")</span></td>";
				}
				echo "</tr></table>";	
				
				// Print subsection header
				$site->printSubsectionHeader($lBlogArchive["BrowseMonth"]);
				
				$run = true;
				$today = getdate();
				$links = 0;
				echo "<table width=\"100%\"><tr>";
				for ($i=0; $run; $i++) {
					// Get the day name
					$start = mktime(0, 0, 0, $today["mon"]-$i, 1, $today["year"]);
					$end = mktime(0, 0, 0, $today["mon"]-$i+1, 1, $today["year"]);
				
					$result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE posted>FROM_UNIXTIME($start) AND posted<FROM_UNIXTIME($end)");
					if ($result->rows()) {
						list ($count) = $result->fetchrow_array();
						if ($count>0) {
							if ($links%3==0) echo "</tr><tr>";
							echo "<td width=\"33%\">";
							echo "<a href=\"".fileBlog."?".(!empty($this->id)?"blogId=".$this->id."&amp;":"")."month=".date("m",$start)."&amp;year=".date("Y",$start)."\">";
							echo intToMonth(date("m",$start));
							echo " ".date("Y",$start)."</a> <span style=\"font-size:80%;color:#666666\">(".$count.")</span><br />";
							echo "</td>";
							$links++;	
						}
					}
				
					// Are there more posts beyond this time?
					$result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE posted<FROM_UNIXTIME($end)");
					if ($result->rows()) {
						list ($count) = $result->fetchrow_array();
						if ($count==0) {
							$run = false;
						}				
					}
				}				
				echo "</tr></table>";
	
				// Print common footer
				$site->printFooter();
			}
		}
	}

	/** 
	 * Print blog index.
	 * @param	$categoryId	Identifier of category.
	 */
	function printBlog($categoryId=-1) {
		global $dbi, $login, $site;
		if (!empty($this->id)) {
			if ($this->hasReadPermission()) {
				// Include language
				include scriptPath."/".folderBlog."/include/language/".$this->language."/general.php";
	
				if (!empty($this->id)) {
					// Get get values
					$day = getGetValue("day");
					$month = getGetValue("month");
					$year = getGetValue("year");

					// Add meta links
					if ($this->hasAdministerPermission()) {
						$site->addMetaLink(scriptUrl."/".folderBlog."/".fileBlogEdit."?blogId=".$this->id, $lBlog["EditBlog"], "edit"); 	
						$site->addMetaLink(scriptUrl."/".folderUsers."/".fileEditPermissions."?moduleContentTypeId=".blogContentId."&amp;moduleContentId=".$this->id, $lBlog["EditPermissions"], "permission");
					}
					$site->addMetaLink($this->getBlogLink(), "", "direct");
					$site->addMetaLink($this->getBlogLink()."&amp;print=1", "", "print");
					$site->addMetaLink(scriptUrl."/".fileSendToFriend."?url=".urlencode($this->getBlogLink())."&amp;title=".urlencode($this->title)."&amp;summary=".urlencode(validateTextLength($this->description,100)), "", "recommend");

					// Add navigation links
					$site->addNavigationLink(scriptUrl."/".folderBlog."/".fileBlogIndex, $lBlogIndex["Header"]);
					$site->addNavigationLink($this->getBlogLink(), $this->title);
					if ($categoryId!=-1) {
						$category = new Category($categoryId);
						$site->addNavigationLink(scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$this->id."&amp;categoryId=".$categoryId, $categoryId==0?$lBlogPost["Uncategorized"]:$category->title);
					}
					else if (!empty($month) && !empty($year)) {
						$site->addNavigationLink(scriptUrl."/".folderBlog."/".fileBlog."?blogId=".$this->id."&amp;month=".$month."&amp;year=".$year, intToMonth($month)." ".$year);					
					}
					
					// Register rss feeds
					$site->registerRSSFeed(scriptUrl."/".folderBlog."/".fileBlogPostRSS."?blogId=".$this->id, $this->title);
					$site->registerRSSFeed(scriptUrl."/".folderBlog."/".fileBlogCommentRSS."?blogId=".$this->id, $this->title." - ".$lBlogPost["Comments"]);

					// Set website path
					$site->setPath(folderBlog);
					
					// Print common header
					$site->printHeader();

					// Print blog body
					$this->printBlogBody($categoryId);
	
					// Print common footer
					$site->printFooter();
				}
			}
			else {
				$login->printLoginForm();
				exit();	
			}			
		}
		else {
			redirect(scriptUrl."/".folderBlog."/".fileBlogIndex);	
		}
	}
	
	/**
	 * Print blog body.
	 * @param 	$categoryId	Identifier of category to display.
	 */
	function printBlogBody($categoryId="") {
		global $dbi,$login;

		// Include language
		include scriptPath."/".folderBlog."/include/language/".$this->language."/general.php";

		// Get category
		if (isset($_GET["categoryId"])) $categoryId = $_GET["categoryId"];

		// Get parameters			
		$searchString = !empty ($_POST["searchString"]) ? $_POST["searchString"] : (!empty($_GET["searchString"])?$_GET["searchString"]:"");
		$day = !empty($_GET["day"])?$_GET["day"]:0;
		$month = !empty($_GET["month"])?$_GET["month"]:0;
		$year = !empty($_GET["year"])?$_GET["year"]:0;

		// Get page number
		$page = !empty($_GET["page"])?$_GET["page"]:0;

		// Print blog header
		$post = new Post();
		$post->blog = $this;
		echo "<p>".$this->description;
		if ($post->hasEditPermission()) {
			printf(" ".$lBlog["NewPost"], $this->id);
		}
		echo "</p>";

		// Validate page
		$page = !empty ($page) ? $page -1 : 0;

		// Create search query
		$search_query = !empty ($searchString) ? " AND (subject LIKE ".$dbi->quote("%$searchString%")." OR subject LIKE ".$dbi->quote("% $searchString%")." OR summary LIKE ".$dbi->quote("%$searchString%")." OR summary LIKE ".$dbi->quote("$searchString%").")" : "";

		// Create datestamps
		$time_query = "";	
		$time_query2 = "";			
		if (!empty ($month) && !empty ($year)) {
			$tmp_date = "";
			$tmp_date2 = "";
			if (!empty ($day)) {
				$tmp_date = mktime(0, 0, 0, $month, $day, $year);
				$tmp_date2 = mktime(0, 0, 0, $month, $day +1, $year);
				$time_query2 = "day=$day&amp;month=$month&amp;year=$year&amp;";
			} else {
				$tmp_date = mktime(0, 0, 0, $month, 1, $year);
				$tmp_date2 = mktime(0, 0, 0, $month +1, 1, $year);
				$time_query2 = "month=$month&amp;year=$year&amp;";
			}
			$time_query = " AND (posted>=FROM_UNIXTIME($tmp_date) AND posted<FROM_UNIXTIME($tmp_date2))";
		}
		if (!empty ($searchString))
			$time_query2 = $time_query2. (!empty ($time_query2) ? "&amp;" : "")."search_string=$searchString";

		// Get post from a specific category
		if ($categoryId!=-1) {
			$result = $dbi->query("SELECT id FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id).(!$this->hasEditPermission()?" AND draft=0":"")." AND id ".($categoryId==0?"NOT ":"")."IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId).($categoryId!=0?" AND categoryId=".$dbi->quote($categoryId):"").")$time_query$search_query ORDER BY posted DESC LIMIT ". ($page * $this->postLimit).",".$this->postLimit);
		}
		else {
			$result = $dbi->query("SELECT id FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id).(!$this->hasEditPermission()?" AND draft=0":"")."$time_query$search_query ORDER BY posted DESC LIMIT ". ($page * $this->postLimit).",".$this->postLimit);
		}

		if ($result->rows()) {
			// Print posts
			for ($i = 0;(list ($postId) = $result->fetchrow_array()); $i ++) {
				$post = new Post($postId);
				$post->printPostSummary();
			}

			// Get number of posts in blog
			if ($categoryId!=-1) $result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id)." AND id ".($categoryId==0?"NOT ":"")."IN(SELECT moduleContentId FROM ".categoryContentRefTableName." WHERE moduleId=".$dbi->quote(blogModuleId)." AND moduleContentTypeId=".$dbi->quote(blogPostContentId).($categoryId!=0?" AND categoryId=".$dbi->quote($categoryId):"").")$time_query$search_query");
			else $result = $dbi->query("SELECT COUNT(*) FROM ".blogPostTableName." WHERE blogId=".$dbi->quote($this->id)."$time_query$search_query");
			if ($result->rows()) {
				list ($count) = $result->fetchrow_array();
				echo "<p align=\"center\">";
				echo printPageIndex(folderBlog."/".fileBlog."?blogId=".$this->id."&amp;". (!empty ($searchString) ? "searchString=$searchString&amp;" : ""). (isset($categoryId) ? "categoryId=$categoryId&amp;" : ""). (!empty ($time_query2) ? "$time_query2&amp;" : ""), $page, $count, $this->postLimit);
				echo "</p>";
			}
		} 
		else {
			echo "<p><i>".$lBlog["NoPosts"]."</i></p>";
		}		
	}
	
	/**
	 * Print search results for a given search string.
	 * @param	$searchString
	 * @param	$limit
	 * @param	$page
	 * @param	$viewAll
	 */
	function printSearchResults($searchString, $limit=0, $page=0, $viewAll=0) {
		global $dbi, $login;

		$result = $dbi->query("SELECT id,MATCH(subject,summary,text) AGAINST ('$searchString' IN BOOLEAN MODE) AS score FROM ".blogPostTableName." WHERE MATCH(subject, summary, text) AGAINST ('$searchString' IN BOOLEAN MODE) AND draft=0 ORDER BY posted DESC".(!empty($limit) && $viewAll?" LIMIT ".($limit*$page).",".$limit:(!empty($limit)?" LIMIT ".$limit:"")));
		if($result->rows()) {
			$highlight = str_replace("\"","",stripslashes($searchString));
			for($i=0;(list($id,$score)=$result->fetchrow_array());$i++) {
				$post = new Post($id);
				printSearchResultItem($searchString, $post->subject, !empty($post->text)?$post->text:$post->summary, $post->getPostLink(), $score); 
			}
		}
		$result->finish();
	}

	/** 
	  * Save blog in database. 
	  * @param	$readPost	Read values from post.
 	  * @return ErrorLog if there were errors.
	  */
	function saveBlog($readPost=true) {
		// Create ErrorLog object
		$errorLog = new ErrorLog();
		
		if ($this->hasAdministerPermission()) {
			global $dbi,$log,$login,$module;

			// Check if data is submitted from the form
			checkSubmitter();

			// Include language
			include scriptPath."/include/language/".pageLanguage."/general.php";
			include scriptPath."/".folderBlog."/include/language/".$this->language."/general.php";

			// Save values from post
			if ($readPost) {
				$this->category = parseHtml(getPostValue("category"),0);
				$this->description = parseHtml(getPostValue("description"),1);
				$this->language = getPostValue("language");
				$this->postLimit = getPostValue("postLimit");
				$this->showRSSLink = getPostValue("showRSSLink");
				$this->showRSSCommentsLink = getPostValue("showRSSCommentsLink");
				$this->subscribers = parseHtml(getPostValue("subscribers"),0);
				$this->title = parseHtml(getPostValue("title"),0);
			}

			// Validate data
			if (empty($this->language)) $this->language = pageDefaultLanguage;
			if (empty($this->title)) $errorLog->addError("title", $lBlogEdit["MissingTitle"]);
			else if (empty($this->id)) {
				$blog = new Blog("", $this->title);
				if (!empty($blog->id)) {
					$errorLog->addError("title", $lBlogEdit["BlogExists"]);
				}
			}
	
			// If there were no errors update database
			if (!$errorLog->hasErrors()) {
				if (empty($this->id)) {
					// Get max position
					$result = $dbi->query("SELECT MAX(position) FROM ".blogTableName);
					if ($result->rows()) {
						list ($position) = $result->fetchrow_array();
						$position ++;
					} 
					else {
						$position = 0;
					}
			
					// Insert blog into database
					$dbi->query("INSERT INTO ".blogTableName."(title,category,description,subscribers,language,postLimit,showRSSLink,showRSSCommentsLink,position) VALUES(".$dbi->quote($this->title).",".$dbi->quote($this->category).",".$dbi->quote($this->description).",".$dbi->quote($this->subscribers).",".$dbi->quote($this->language).",".$dbi->quote($this->postLimit).",".$dbi->quote($this->showRSSLink).",".$dbi->quote($this->showRSSCommentsLink).",".$dbi->quote($position).")");			

					// Get new blog id
					$this->id = $dbi->getInsertId();
					
					// Set default permissions
					$login->setModuleContentPermissions(blogContentId, $this->id, "Visitors", 0, 0, 1, 0, 0, 0, 0, 1);
					$login->setModuleContentPermissions(blogContentId, $this->id, "Users", 0, 0, 1, 0, 0, 0, 0, 1);									
				}
				else {
					// Update blog in database
					$dbi->query("UPDATE ".blogTableName." SET title=".$dbi->quote($this->title).",category=".$dbi->quote($this->category).",description=".$dbi->quote($this->description).",subscribers=".$dbi->quote($this->subscribers).",language=".$dbi->quote($this->language).",postLimit=".$dbi->quote($this->postLimit).",showRSSLink=".$dbi->quote($this->showRSSLink).",showRSSCommentsLink=".$dbi->quote($this->showRSSCommentsLink)." WHERE id=".$dbi->quote($this->id));
				}
				
				// Upload index picture
				if (!empty($_FILES["img_0"])) {
					uploadFile($_FILES["img_0"], "blog_".$this->id, array("image/jpeg","image/pjpeg","image/gif"), 0, 50, 50);
				}
		
				// Log transaction
				$log->logTransaction(blogContentId, $this->id);
			}
			else if (!empty($_FILES["img_0"]["tmp_name"])) {
				$errorLog->addError("upload", $lErrors["ReUploadImages"]);			
			}
		}
		return $errorLog;
	}
}
?>
