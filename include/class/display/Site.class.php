<?
class Site {
	var $adminIndexSections = array();
	var $editUserSections = array();
	var $loadScript = "";
	var $metaDescription = "";
	var $metaKeywords = "";
	var $metaLinks = array();
	var $moduleContentObject = null;
	var $navigationLinks = array();
	var $path = "";
	var $rssFeeds = array();
	var $title = "";

	function addMetaLink($link, $title, $type) {
		// Get size of meta links array
		$index = sizeof($this->metaLinks);
		
		// Insert into metalinks
		$this->metaLinks[$index][0] = $link;
		$this->metaLinks[$index][1] = $title;
		$this->metaLinks[$index][2] = $type;
	}
	
	function addNavigationLink($link, $title) {
		// Get size of meta links array
		$index = sizeof($this->navigationLinks);
		
		// Insert into metalinks
		$this->navigationLinks[$index][0] = $link;
		$this->navigationLinks[$index][1] = $title;
	}
	
	/**
	  * Generate formatted size of a file or folder.
	  * @param 	$size	Size in bytes.
	  * @return Size in KB or MB depending on size.
	  */
	function generateFormattedSize($size) {
		$size = round($size/1024,1);
		if($size>1024) return round($size/1024,1)." MB";
		else return $size." KB";	
	}	
	
	/** 
	 * Generate page index 
	 * @param 	$file 	File to send page arguments to
	 * @param 	$pageNumber 	Current page number
	 * @param 	$count 	Total count of resources
	 * @param 	$limit 	Resources to display per page
	 * @param 	$mode 	Page index mode
	 * @param 	$anchor Anchor on page to jump to
	 * @return 	string with forward/back navigation and pages
	 */
	function generatePageLinks($file,$pageNumber,$count,$limit=15,$mode="",$anchor="") {
		if($file!="" && $count>0) {
			$page_counter = 0;
			$pages = ceil($count/$limit);
			$page_start = 0;
			$page_offset = 0;
			$page_links = "";
			
			if($pages<2) {
				$page_links="";
			}
			else if($pages<10) {
				for($temp=$page_start;$temp<$count;$temp+=$limit) {
					$page_counter++;
					if(($temp<=$count) && ($page_counter<=11)) {
						if($pageNumber!=($temp/$limit)) {
							$isPage = false;
						}
						else {
							$current_page = $page_counter+$page_offset;
							$isPage = true;
						}
						$page_links .= "<a href=\"".scriptUrl."/".$file."page=".($page_counter+$page_offset)."\" class=\"pageLink\">".($isPage?"<b><u>":"").($page_counter+$page_offset).($isPage?"</u></b>":"")."</a>&nbsp;&nbsp;";
					}
				}
			}
			else {
				for($temp=$page_start;$temp<$count;$temp+=$limit) {
					$page_counter++;
					if($temp<=$count) {
						$this_page = $page_counter+$page_offset;
						if($pageNumber!=($temp/$limit)) {
							$isPage = false;
						}
						else {
							$current_page = $page_counter+$page_offset;
							$isPage = true;
						}
						if($this_page==1 || $this_page==2 || $this_page==3 || $this_page==$pageNumber || $this_page==$pageNumber+1 || $this_page==$pageNumber+2 || $this_page==($pages-2) || $this_page==($pages-1) || $this_page==$pages) {
							$page_links .= "<a href=\"".scriptUrl."/".$file."page=".($page_counter+$page_offset).(!empty($anchor)?"#$anchor":"")."\" class=\"pageLink\">".($isPage?"<b><u>":"").($page_counter+$page_offset).($isPage?"</u></b>":"")."</a>&nbsp;&nbsp;";
							$set = false;
						}
						else if(!$set) {
							$page_links .= "...&nbsp;&nbsp;";
							$set = true;
						}
					}
				}
			}
	
			// Previous page
			if($pageNumber>0) $previous_page = "<a href=\"".scriptUrl."/".$file."page=$pageNumber".(!empty($anchor)?"#$anchor":"")."\" class=\"pageLink\">&lt;&lt;</a>&nbsp;";
			else $previous_page = "";
	
			// Next page
			if(($pageNumber+1)<$pages) $next_page = "<a href=\"".scriptUrl."/".$file."page=".($pageNumber+2).(!empty($anchor)?"#$anchor":"")."\" class=\"pageLink\">&gt;&gt;</a>";
			else $next_page = "";
	
			if ($mode=="previous") return $previous_page;
			if ($mode=="next") return $next_page;
			return $previous_page.$page_links.$next_page;
		}
		return "";
	}
	
	/** 
	 * Generate html to display popup window
	 * @param 	$url 	Url to display in popup window
	 * @param 	$title 	Title of popup window
	 * @param 	$height Height of popup window
	 * @param 	$width 	Width of popup window
	 * @return 	string with popup html/javascript code.
	 */
	function generatePopupLink($url,$title,$style="",$height=500,$width=400) {
		$url = str_replace("%27","\%27",addslashes($url));
		return "<a href=\"javascript:popup('".$url."','popup',$height,$width,'scrollbars,resizable')\"".(!empty($style)?" class=\"$style\"":"").">$title</a>";
	}
	
	/** 
	 * Protect email from automatic harvesting 
	 * @param $mail Mail address to protect
	 * @param $title Mail link title
	 * @param $class CSS style to apply to link
	 */
	function generateProtectMailLink($mail,$title="",$class="") {
		return "<a href=\"javascript:protectMail('".str_replace("@","','",$mail)."')\"".(!empty($class)?" class=\"$class\"":"").">$title</a>";
	}
	
	/**
	  * Generate formatted timestamp using the default time format.
	  * @param 	$timestamp 	Timestamp to generate.
	  * @param 	$shorttime 	Display short time without hours and minutes.
	  * @return Formatted timestamp.
	  */
	function generateTimestamp($timestamp, $shorttime=false, $custom="") {
		if ($custom) return date($custom, $timestamp);
		else if ($shorttime) return date(shortTimeFormat, $timestamp);
		return date(timeFormat, $timestamp);
	}
	
	/** 
	  * Convert an integer to a day of the week.
	  * @param	$day	Day of week.
	  * @param	$month	Month of year.
	  * @param	$year	Year.
	  * @return textual representation of the day of the week.
	  */
	function getDayOfWeek($day,$month,$year) {
		global $lDays;
	
		// Look up textual representation of day from language files
		switch(date("w",mktime(0,0,0,$month,$day,$year))) {
			case 0: return $lDays["Sunday"]; 
			case 1: return $lDays["Monday"];
			case 2: return $lDays["Tuesday"];
			case 3: return $lDays["Wednesday"];
			case 4: return $lDays["Thursday"];
			case 5: return $lDays["Friday"];
			case 6: return $lDays["Saturday"];
		}
	}
	
	function getDirectLink() {
		if (!empty($this->moduleContentTypeObject)) {
			if (method_exists($this->moduleContentTypeObject, "getLink")) {
				return $this->moduleContentTypeObject->getLink();	
			}
		}
	}
	
	function getEditPermissionsLink() {
	}
		
	function getMetaDescription() {
		if (!empty($this->metaDescription)) return $this->metaDescription;
		return pageDescription;
	}

	function getMetaKeywords() {
		if (!empty($this->metaKeywords)) return $this->metaKeywords;
		return pageKeywords;
	}
		
	/**
	  * Convert an integer to a month of the year. 
	  * @param	$mnum	Month number.
	  * @return textual representation of month of the year.
	  */
	function getMonthName($mnum) {
		global $lMonths;
	
		// Look up textual representation of month from language files
		$month = Array($lMonths["January"],$lMonths["February"],$lMonths["March"],$lMonths["April"],$lMonths["May"],$lMonths["June"],$lMonths["July"],$lMonths["August"],$lMonths["September"],$lMonths["October"],$lMonths["November"],$lMonths["December"]);
		if(!empty($month[$mnum-1])) return $month[$mnum-1];
		return "";
	}	
	
	function getPrinterLink() {		
	}

	/** Get available themes on the website sorted by title. */
	function getThemes() {
		$folders = array();
		if (is_dir(scriptPath."/theme/layout")) {
			if ($handle = opendir(scriptPath."/theme/layout")) {
				for ($i=0;($file = readdir($handle))!==false;$i++) {
					if($file != "." && $file != "..") {
						if (is_dir(scriptPath."/theme/layout/".$file)) {
							$folders[] = $file;
						}
					}
				}
				closedir($handle);
			}
		}
		sort($folders);
	
		$themes = array();
		for ($i=0; $i<sizeof($folders); $i++) {	
			if (file_exists(scriptPath."/theme/layout/".$folders[$i]."/about.php")) {
				$themes[] = new Theme($folders[$i]);
			}
		}
		return $themes;
	}

	function isIPad() {
		$ipad = 0;
		if (ereg('iPad',$_SERVER['HTTP_USER_AGENT'])) {
			$ipad = 1;
		} 
		return $ipad;	
	}
	
	function isIPhone() {
		$iphone = 0;
		if (ereg('iPhone',$_SERVER['HTTP_USER_AGENT'])) {
			$iphone = 1;
		} 
		elseif (ereg('iPod',$_SERVER['HTTP_USER_AGENT'])) {
			$iphone = 1;
		} 
		elseif (ereg('Android',$_SERVER['HTTP_USER_AGENT'])) {
			$iphone = 1;
		}
		return $iphone;	
	}

	/**
	 * Print meta link.
	 * @param	$title		Title of link.
	 * @param	$link		Link to insert.
	 * @param	$iconUrl	Link to icon.
	 */
	function printMetaLink($link, $title, $iconUrl, $popup=0) {
		if (!empty($title) && !empty($link)) {
			if (!empty($iconUrl)) {
				echo "\n<div><div style=\"float:left;width:16px;\">\n";
				if ($popup) $this->printPopupLink($link, "<img src=\"".$iconUrl."\" height=\"16\" width=\"16\" border=\"0\" alt=\"$title\" title=\"$title\" />");
				else echo "<a href=\"".$link."\"><img src=\"".$iconUrl."\" height=\"16\" width=\"16\" border=\"0\" alt=\"$title\" title=\"$title\" /></a>\n";
				echo "</div>";
			}
			echo "<div style=\"margin-bottom:5px\">\n";
			echo "&nbsp;";
			if ($popup) $this->printPopupLink($link, $title, "small1");
			else echo "<a href=\"".$link."\" class=\"small1\">".$title."</a>\n";
			echo "</div></div>\n";
		}		
	}

	function printMetaLinks() {
		for ($i=0; $i<sizeof($this->metaLinks); $i++) {
			if (!empty($this->metaLinks[$i][0]) && !empty($this->metaLinks[$i][2])) {	
				global $lBottom;
					
				switch($this->metaLinks[$i][2]) {
					case "direct":
						if(pageShowDirectLink) $this->printMetaLink($this->metaLinks[$i][0], !empty($this->metaLinks[$i][1])?$this->metaLinks[$i][1]:$lBottom["DirectLink"], iconUrl."/links.gif");
						break;
					case "edit":
						$this->printMetaLink($this->metaLinks[$i][0], !empty($this->metaLinks[$i][1])?$this->metaLinks[$i][1]:$lBottom["Edit"], iconUrl."/edit.gif");
						break;
					case "permission":
						$this->printMetaLink($this->metaLinks[$i][0], !empty($this->metaLinks[$i][1])?$this->metaLinks[$i][1]:$lBottom["EditPermissions"], iconUrl."/permissions.gif");
						break;
					case "print":
						if (pageShowPrinterLink) $this->printMetaLink($this->metaLinks[$i][0], !empty($this->metaLinks[$i][1])?$this->metaLinks[$i][1]:$lBottom["PrinterFriendly"], iconUrl."/print.gif");
						break;
					case "recommend":
						if (pageShowRecommendLink) $this->printMetaLink($this->metaLinks[$i][0]."&amp;popup=1", !empty($this->metaLinks[$i][1])?$this->metaLinks[$i][1]:$lBottom["RecommendLink"], iconUrl."/recommend.gif", true);
						break;
					case "revision":
						$this->printMetaLink($this->metaLinks[$i][0], !empty($this->metaLinks[$i][1])?$this->metaLinks[$i][1]:$lBottom["Revisions"], iconUrl."/revisions.png");
						break;
					default:
						break;	
				}			
			}
		}
	}

	function printFooter() {
		global $dbi,$log,$login;
	
		// Include language
		include scriptPath."/include/language/".pageLanguage."/general.php";
	
		// Print footer
		if(showPopup) {
			if (file_exists(layoutPath."/footerPopup.php")) {
				include layoutPath."/footerPopup.php";
			}
			else {
				include scriptPath."/include/template/footerPopup.php";		
			}
		}
		else if(showPrint) {
			if (file_exists(layoutPath."/footerPrint.php")) {
				include layoutPath."/footerPrint.php";
			}
			else {
				include scriptPath."/include/template/footerPrint.php";
			}
		}
		else {
			if ($this->isIPad() && file_exists(layoutPath."/footer.iPad.php")) {
				include layoutPath."/footer.iPad.php";				
			}
			else if ($this->isIPhone() && file_exists(layoutPath."/footer.iPhone.php")) {
				include layoutPath."/footer.iPhone.php";				
			}
			else if (file_exists(layoutPath."/footer.php")) {
				include layoutPath."/footer.php";
			}
			else {
				include scriptPath."/include/template/footer.php";	
			}
		}
	}

	/**
	 * Print website header.
	 * @param	$printHeader	Print header on page.
	 */
	function printHeader($printHeader=true) {	
		// If title is empty get last element of navigation list
		if (empty($this->title)) {
			if (!empty($this->navigationLinks[sizeof($this->navigationLinks)-1][1])) {
				$this->title = $this->navigationLinks[sizeof($this->navigationLinks)-1][1];
			}
		}
		
		// Create variables for backward compatiblity
		$navigation = $this->navigationLinks;
		$path = $this->path;
		$title = $this->title;
		$loadScript = $this->loadScript;

		// Print header
		if(showPopup) {
			if (file_exists(layoutPath."/headerPopup.php")) {
				include layoutPath."/headerPopup.php";
			}
			else {
				include scriptPath."/include/template/headerPopup.php";		
			}
		}
		else if(showPrint) {
			if (file_exists(layoutPath."/headerPrint.php")) {
				include layoutPath."/headerPrint.php";
			}
			else {
				include scriptPath."/include/template/headerPrint.php";
			}
		}
		else {
			if ($this->isIPad() && file_exists(layoutPath."/header.iPad.php")) {
				include layoutPath."/header.iPad.php";				
			}
			else if ($this->isIPhone() && file_exists(layoutPath."/header.iPhone.php")) {
				include layoutPath."/header.iPhone.php";				
			}
			else if (file_exists(layoutPath."/header.php")) {
				include layoutPath."/header.php";
			}
			else {
				include scriptPath."/include/template/header.php";	
			}
		}	
	}
		
	/**
	  * Print textual month.
	  * @param 	$month 	Month to print as text.
	  */
	function printMonthName($month) {
		global $lMonths;
		echo $this->getMonthName($month);
	}	
	
	/** 
	 * Print edit link
	 * @param $edit Link to edit file
	 * @param $title Title of link
	 */
	function printEditLink($edit="",$title="") {
		if(!empty($edit) && !showPrint) {
			/* Include language */
			include scriptPath."/include/language/".pageLanguage."/general.php";
	
			/* Print link */
			$title = !empty($title)?$title:$lBottom["Edit"];
			if (file_exists(iconPath."/edit.gif")) {
				echo "\n<div><div style=\"float:left;width:16px;\">\n";
				echo "<a href=\"$edit\" target=\"_blank\"><img src=\"".iconUrl."/edit.gif\" height=\"16\" width=\"16\" border=\"0\" alt=\"$title\" title=\"$title\" /></a>\n";
				echo "</div>";
			}
			echo "<div style=\"margin-bottom:5px\">\n";
			echo "&nbsp;<a href=\"$edit\" class=\"small1\">$title</a>\n";
			echo "</div></div>\n";
		}
	}
	
	/** 
	 * Print permission link
	 * @param $permission Link to permission file
	 * @param $title Title of link
	 */
	function printPermissionLink($permission="",$title="") {
		if(!empty($permission) && !showPrint) {
			global $lBottom;
	
			// Print link
			$title = !empty($title)?$title:$lBottom["EditPermissions"];
			if (file_exists(iconPath."/permissions.png")) {
				echo "\n<div><div style=\"float:left;width:16px;\">\n";
				echo "<a href=\"$permission\" target=\"_blank\"><img src=\"".iconUrl."/permissions.png\" height=\"16\" width=\"16\" border=\"0\" alt=\"$title\" title=\"$title\" /></a>\n";
				echo "</div>";
			}
			echo "<div style=\"margin-bottom:5px\">\n";
			echo "&nbsp;<a href=\"$permission\" class=\"small1\">$title</a>\n";
			echo "</div></div>\n";
		}
	}
	
	/** 
	 * Print direct link to resource (useful with frames)
	 * @param $url Link to resource file
	 * @param $title Title of link
	 */
	function printDirectLink($url="",$title="") {
		if(!empty($url) && !showPrint) {
			global $lBottom;
	
			// Print link
			$title = !empty($title)?$title:$lBottom["DirectLink"];
			echo "\n<div><div style=\"float:left;width:16px;\">\n";
			echo "<a href=\"$url\" target=\"_blank\"><img src=\"".iconUrl."/bookmark.gif\" height=\"16\" width=\"16\" border=\"0\" alt=\"$title\" title=\"$title\" /></a>\n";
			echo "</div><div style=\"margin-bottom:5px\">\n";
			echo "&nbsp;<a href=\"$url\" target=\"_blank\" class=\"small1\" title=\"$title\">$title</a>\n";
			echo "</div></div>\n";
		}
	}
	
	function printFormattedSize($size) {
		echo $this->generateFormattedSize($size);
	}
	
	function printPageLinks($file,$pageNumber,$count,$limit=15,$mode="",$anchor="") {
		echo $this->generatePageLinks($file, $pageNumber, $count, $limit, $mode, $anchor);
	}
	
	function printPopupLink($url,$title,$style="",$height=500,$width=400) {
		echo $this->generatePopupLink($url, $title, $style, $height, $width);
	}
		
	/** 
	 * Print link to printerfriendly version
	 * @param $print Link to printerfriendly version
	 * @param $title Title of link
	 */
	function printPrinterLink($print="",$title="") {
		if(!empty($print) && !showPrint) {
			global $lBottom;
	
			// Print link		
			$title = !empty($title)?$title:$lBottom["PrinterFriendly"];
			echo "\n<div><div style=\"float:left;width:16px;\">\n";
			echo "<a href=\"$print\" target=\"_blank\"><img src=\"".iconUrl."/print.gif\" height=\"16\" width=\"16\" border=\"0\" alt=\"$title\" title=\"$title\" /></a>\n";
			echo "</div><div style=\"margin-bottom:5px\">\n";
			echo "&nbsp;<a href=\"$print\" target=\"_blank\" class=\"small1\" title=\"$title\">$title</a>\n";
			echo "</div></div>";
		}
	}	
	
	/** 
	 * Print section header
	 * @param 	$title 			Title of section header 
	 * @param 	$links 			Links or other information displayed to the right of the header.
	 * @param	$makeExpandable	Make it possible to expand/collapse section.
	 * @param	$isExpanded		Is the header expanded?
	 * @param	$idToExpand		Span id to expand/collapse on click.
	 */
	function printSectionHeader($title, $links="", $makeExpandable=0, $isExpanded=1, $idToExpand="") {
		if (!empty($links) || $makeExpandable) {
			global $lSection;
			$name = str_replace(" ", "", stripHtml($title));
			if (file_exists(templatePath."/sectionHeaderExpandable.template.php")) {
				include templatePath."/sectionHeaderExpandable.template.php";
			}
			else {
				echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"h1\"><tr>".($makeExpandable?"<td width=\"16\"><img id=\"icon".$name."\" src=\"".iconUrl."/".($isExpanded?"collapse":"expand").".gif\" style=\"cursor:pointer;cursor:hand\" onclick=\"expand('".$idToExpand."',this,'".$lSection["Collapse"]."','".$lSection["Collapse"]."','".iconUrl."');tinyMCE.execCommand('mceResetDesignMode');\" /></td>":"")."<td width=\"100%\"><h1 class=\"noMargin\"".($makeExpandable?" style=\"cursor:pointer;cursor:hand\" onclick=\";expand('".$idToExpand."',document.getElementById('icon".$name."'),'".$lSection["Collapse"]."','".$lSection["Expand"]."','".iconUrl."');tinyMCE.execCommand('mceResetDesignMode');\"":"").">$title</h1></td>".(!empty($links)?"<td align=\"right\" nowrap=\"nowrap\">$links</td>":"")."</tr></table>\n";	
			}
		}
		else {
			if (file_exists(templatePath."/sectionHeader.template.php")) {
				include templatePath."/sectionHeader.template.php";
			}
			else {
				echo "<h1>$title</h1>";
			}
		}
	}
	
	// Function that prints a menuitem in the various sections
	function printSectionItem($title,$picture,$link,$count,$description) {
?>
<table width="100%" align="center">
<tr>
<td width="50" valign="top">
<a href="<?= $link ?>">
<img src="<?= !empty($picture)?$picture:iconUrl."/default.jpg" ?>" width="50" height="50" alt="" border="0"  class="border" />
</a>
</td>

<td valign="top">
<a href="<?= $link ?>"><b><?= $title ?></b><?= ($count!=0?" ($count)":"") ?></a><br />
<?= $description ?>
</td>
</tr>
</table>
<?
	}	
	
	/** 
	 * Print subsection header
	 * @param 	$title 			Title of subsection header 
	 * @param 	$links 			Links or other information displayed to the right of the header.
	 * @param	$makeExpandable	Make it possible to expand/collapse section.
	 * @param	$isExpanded		Is the header expanded?
	 * @param	$idToExpand		Span id to expand/collapse on click.
	 */
	function printSubsectionHeader($title, $links="", $makeExpandable=0, $isExpanded=1, $idToExpand="") {
		if (!empty($links) || $makeExpandable) {
			global $lSection;
			$name = str_replace(" ", "", stripHtml($title));
			if (file_exists(templatePath."/sectionHeaderExpandable.template.php")) {
				include templatePath."/sectionHeaderExpandable.template.php";
			}
			else {
				echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"h2\"><tr>".($makeExpandable?"<td width=\"16\"><img id=\"icon".$name."\" src=\"".iconUrl."/".($isExpanded?"collapse":"expand").".gif\" style=\"cursor:pointer;cursor:hand\" onclick=\"expand('".$idToExpand."',this,'".$lSection["Collapse"]."','".$lSection["Collapse"]."','".iconUrl."');tinyMCE.execCommand('mceResetDesignMode');\" /></td>":"")."<td width=\"100%\"><h2 class=\"noMargin\"".($makeExpandable?" style=\"cursor:pointer;cursor:hand\" onclick=\";expand('".$idToExpand."',document.getElementById('icon".$name."'),'".$lSection["Collapse"]."','".$lSection["Expand"]."','".iconUrl."');tinyMCE.execCommand('mceResetDesignMode');\"":"").">$title</h2></td>".(!empty($links)?"<td align=\"right\" nowrap=\"nowrap\">$links</td>":"")."</tr></table>\n";	
			}
		}
		else {
			if (file_exists(templatePath."/sectionHeader.template.php")) {
				include templatePath."/sectionHeader.template.php";
			}
			else {
				echo "<h2>$title</h2>";
			}
		}
	}	
	
	function printTimestamp($timestamp, $shorttime=false, $custom="") {
		echo $this->generateTimestamp($timestamp, $shorttime, $custom);
	}	

	/**
	 * Register a section to be displayed in the admin index under content.
	 * @param	$title			Section title.
	 * @param	$description	Section description.
	 * @param	$link			Section link.
	 * @param	$imageURL		Section image.
	 */
	function registerAdminIndexSection($title, $description, $link, $imageURL) {
		if (empty($title) || empty($link)) return;
		$index = sizeof($this->adminIndexSections);
		$this->adminIndexSections[$index]["title"] = $title;
		$this->adminIndexSections[$index]["description"] = $description;
		$this->adminIndexSections[$index]["url"] = $link;
		$this->adminIndexSections[$index]["image"] = $imageURL;
	}
	
	function registerRSSFeed($feed, $title) {
		if (empty($title) || empty($feed)) return;
		$index = sizeof($this->rssFeeds);
		$this->rssFeeds[$index][0] = $title;
		$this->rssFeeds[$index][1] = $feed;
	}	
	
	function registerEditUserSection($title, $description, $indexValue, $fields, $saveFunction) {
		if (empty($title) || empty($saveFunction)) return;
		if (!function_exists($saveFunction)) return;
		$index = sizeof($this->editUserSections);
		$this->editUserSections[$index]["title"] = $title;
		$this->editUserSections[$index]["description"] = $description;
		$this->editUserSections[$index]["index"] = $indexValue;
		$this->editUserSections[$index]["fields"] = $fields;
		$this->editUserSections[$index]["saveFunction"] = $saveFunction;
	}
	
	function setLoadScript($script) {
		$this->loadScript = $script;
	}
	
	function setMetaDescription($metaDescription) {
		$metaDescription = stripHtml(validateTextLength(parseString($metaDescription), 300));	
		$metaDescription = str_replace("\"","&quot;",$metaDescription);	
		$this->metaDescription = $metaDescription;
	}
	
	function setMetaKeywords($metaKeywords) {
		$metaKeywords = stripHtml(validateTextLength(parseString($metaKeywords), 300));
		$this->metaKeywords = $metaKeywords;
	}
	
	function setModuleContentObject($moduleContentObject) {
		$this->moduleContentObject = $moduleContentObject;
	}

	function setPath($path) {
		$this->path = $path;
	}
	
	function setTitle($title) {
		$this->title = $title;	
	}
	
	/**
	 * Set meta links to display related to content.
	 * @param	$metaLinks	List of links to set.
	 */
	function setMetaLinks($metaLinks) {
		$this->metaLinks = $metaLinks;
	}
	
	/**
	 * Set navigation links.
	 * @param	$navigationLinks	List of links to set.
	 */
	function setNavigationLinks($navigationLinks) {
		$this->navigationLinks = $navigationLinks;	
	}
}
?>