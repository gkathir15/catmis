<?
/** Check validation code. */
function audit() {
	$digit = !empty($_SESSION['digit'])?$_SESSION['digit']:"";
	$userdigit = !empty($_POST['userdigit'])?$_POST['userdigit']:""; 
	unset($_SESSION['digit']);  
	return (($digit == $userdigit) && ($digit > 1));
}

/** 
 * Checks a variable to see if it should be considered a boolean true or false.
 * Also takes into account some text-based representations of true of false,
 * such as 'false','N','yes','on','off', etc.
 * @author Samuel Levy <sam+nospam@samuellevy.com>
 * @param mixed $in The variable to check
 * @param bool $strict If set to false, consider everything that is not false to be true.
 * @return bool The boolean equivalent or null (if strict, and no exact equivalent)
 */
function boolval($in, $strict=false) {
    $out = null;
    $in = (is_string($in)?strtolower($in):$in);
    // if not strict, we only have to check if something is false
    if (in_array($in,array('false','no', 'n','0','off',false,0), true) || !$in) {
        $out = false;
    } else if ($strict) {
        // if strict, check the equivalent true values
        if (in_array($in,array('true','yes','y','1','on',true,1), true)) {
            $out = true;
        }
    } else {
        // not strict? let the regular php bool check figure it out (will
        //     largely default to true)
        $out = ($in?true:false);
    }
    return $out;
}

/** 
 * Validate e-mail address 
 * @param $mail Email address to validate
 */
function checkEmail($mail) {
	return(ereg('^[-!#$%&mp;\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&mp;\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&mp;\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',$mail));
}

/** 
 * If the poster is not submitting information from the $url location, they will
 * be redirected to the index.
 * @param $url Url to check for. If empty default script url.
 */
function checkSubmitter($url="") {
	if(empty($url)) {
		$url = getCurrentDomain();
		if (empty($url)) return true;
	}
	if (!empty($_SERVER["HTTP_REFERER"])) {
		if(strpos($_SERVER["HTTP_REFERER"],$url)===false) redirect($url);		
	}
	else {
		redirect($url);
	}
	return true;
}

function decodeParameter($parameter) {
	$parameter = urldecode($parameter);
	$parameter = str_replace("<NEWLINE>","\n",$parameter);
	$parameter = str_replace("<AND>","&amp;",$parameter);
	$parameter = parseString($parameter);
	return $parameter;
}

function deleteTransaction($type, $typeId) {
	global $dbi,$log_table_name;
	$dbi->query("DELETE FROM `".logTableName."` WHERE type=".$dbi->quote($type)." AND typeId=".$dbi->quote($typeId));
}

function encodeParameter($parameter) {
	$parameter = parseString($parameter);
	$parameter = str_replace("\n","<NEWLINE>",$parameter);
	$parameter = str_replace("&amp;","<AND>",$parameter);
	return urlencode($parameter);
}

function generateURL($link, $parameters=array()) {
	$url = $link;
	for ($i=0; $i<sizeof($parameters); $i++) {
		if (!empty($parameters[$i])) {
			$url .= ($i==0?"?":"/").urlencode(str_replace("&quot;","\"", $parameters[$i]));
		}
	}
	return $url;
}

/**
 * Get domain of the current page.
 * @return	Domain of current page.
 */
function getCurrentDomain() {
	$pageUrl = @ereg_replace("/(.+)", "", $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]); 
	$curdomain  = str_replace("www.", "", $pageUrl);
	if ($curdomain == "localhost") return "";
	$pos = strrpos($curdomain, '.');
	$pos = strrpos($curdomain, '.', -(strlen($curdomain)-$pos+1));
	$curdomain = substr($curdomain, $pos);
	if ($curdomain[0]==".") $curdomain = substr($curdomain,1);
	return $curdomain;
}

/**
 * Get URL of the current page.
 * @return URL of current page.
 */
function getCurrentURL() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; 
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; 
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
}

/** 
  * Convert an integer to a day of the week.
  * @param	$day	Day of week.
  * @param	$month	Month of year.
  * @param	$year	Year.
  * @return textual representation of the day of the week.
  */
function getDayOfWeek($day,$month,$year) {
	global $site;
	return $site->getDayOfWeek($day,$month,$year);
}

/**
  * Get file extension for database resource.
  * @param 	$fileId 	Id of file in database to get extension from.
  */
function getDatabaseFileExtension($fileId) {
	$file = new File();
	return $file->getDatabaseFileExtension($fileId);
}

/**
  * Get file extension of filename.
  * @param 	$filename 	Filename to get extension from.
  */
function getFileExtension($filename) {
	$file = new File();
	return $file->getFileExtension($filename);
}

/**
 * Get a formatted version of a given timestamp. TODO: Localize and document.
 * @param $timestamp
 * @param $showYear
 * @param $showTime
 * @return unknown_type
 */
function getFormattedTimestamp($timestamp, $showYear = true, $showTime = false) {
	$time = "";
	$today = getdate();
	if (date("d m y",mktime()) == date("d m y",$timestamp)) {
		$time = "I dag";
	}
	else if (date("d m y",mktime(0, 0, 0, 0, $today["mday"]-1, 0)) == date("d m y",$timestamp)) {
		$time = "I går";
	}
	else {
		$time = date("j",$timestamp).". ";
		
		$month = date("m",$timestamp);
		switch($month) {
			case 1:
				$time .= "jan";
				break;
			case 2:
				$time .= "feb";
				break;
			case 3:
				$time .= "mar";
				break;
			case 4:
				$time .= "apr";
				break;
			case 5:
				$time .= "maj";
				break;
			case 6:
				$time .= "jun";
				break;
			case 7:
				$time .= "jul";
				break;
			case 8:
				$time .= "aug";
				break;
			case 9:
				$time .= "sep";
				break;
			case 10:
				$time .= "okt";
				break;
			case 11:
				$time .= "nov";
				break;
			case 12:
				$time .= "dec";
				break;
		}
		$year = date("Y",$timestamp);
		if ($showYear && $year != $today["year"]) $time .= " ".$year;
	}
	return $time;
}

/**
  * Get formatted size of a file.
  * @param 	$size	Size in bytes.
  * @return Size in KB or MB depending on size.
  */
function getFormattedSize($size) {
	global $site;
	return $site->generateFormattedSize($size);
}

function getGetValue($name) {
	if (!empty($_GET[$name])) {
		return $_GET[$name];
	}	
}

function getImageDimensions($imageUrl) {
	return getimagesize(str_replace(scriptUrl, scriptPath, $imageUrl));
}

/** 
 * Function that returns a new number of reads of a given resource.
 * @param $reads Current number of reads.
 * @param $type Type of resource.
 * @param $typeId Identifier og resource.
 */
function getNumberOfReads($reads,$type,$typeId) {
	global $dbi, $login;

	/* Administrators doesn't generate hits */
	if(!$login->isAdmin()) {
		/* Generate time interval (default 1 day) */
		$time = getdate();
		$starttime = mktime(0,0,0,$time[mon],$time[mday],$time[year]);

		/* Clean up */
		$dbi->query("DELETE FROM `".readsTableName."` WHERE timestamp<FROM_UNIXTIME($starttime)");

		/* Get user ip */
		$ip = getenv("REMOTE_ADDR");
	
		/* Fetch last accessed information */
		$result = $dbi->query("SELECT id,ip FROM `".readsTableName."` WHERE type='$type' AND type_id='$typeId' AND ip='$ip'");
		if($result->rows())	{
			return $reads;
		}
		else {
			$dbi->query("INSERT INTO `".readsTableName."`(type,type_id,ip,timestamp) VALUES(".$dbi->quote($type).",".$dbi->quote(type_id).",".$dbi->quote($ip).",NOW())");
			return $reads+1;
		}
	}
	return $reads;
}

function getPostValue($name) {
	if (!empty($_POST[$name])) {
		return $_POST[$name];
	}
	else if (!empty($_SESSION["post"][$name])) {
		$sessionStarted = false;
		if (!isset($_SESSION)) {
			session_start();
			$sessionStarted = true;
		}
		$value = $_SESSION["post"][$name];
		$_SESSION["post"][$name] = "";
		if ($sessionStarted) session_write_close();
		return $value;
	}
	return "";
}

/**
 * Get value from post if available, else get value from session.
 * @param	$name	Name of value to get.
 * @return	value if exists, empty string otherwise.
 */
function getValue($name) {
	if (!empty($_POST[$name])) {
		return $_POST[$name];
	}
	else if (!empty($_GET[$name])) {
		return $_GET[$name];	
	}
	else if (!empty($_SESSION["post"][$name])) {
		$value = $_SESSION["post"][$name];
		$_SESSION["post"][$name] = "";
		return $value;
	}
	return "";
}

/**
 * Get parameters from URL.
 * @return	list of parameters (title, id, category)
 */
function getURLParameters($path) {
   // Get path with ? separator
    $request = ereg_replace("\/([a-zA-Z0-9\~\.\/])*\?", "", $_SERVER['REQUEST_URI']);
    $request = ereg_replace("&.*", "", $request);

    // Get path with / separator
    $prefix = str_replace(dirname(scriptUrl)."/", "", scriptUrl);
    $request = str_replace($prefix."/", "", $request);
    $request = str_replace($prefix, "", $request);
    $request = str_replace($path."/", "", $request);
    $request = str_replace($path, "", $request);
    $request = ereg_replace("/~([a-zA-Z0-9])*/", "", $request);

	$sharpPosition = strpos($request, "#");
	if ($sharpPosition !== false) {
		$request = substr($request, 0, strlen($request)-$sharpPosition);
	}
	$parameters = explode("/", $request);

	// Remove empty spaces
	for ($i=0; $i<sizeof($parameters); $i++) {
		if (empty($parameters[$i])) {
			array_splice($parameters, $i,1);
		}
	}

	// Decode characters
	for ($i=0; $i<sizeof($parameters); $i++) {
		$parameters[$i] = str_replace("%27","'",$parameters[$i]);
		$parameters[$i] = str_replace("%26amp%3B","&",$parameters[$i]);
		$parameters[$i] = str_replace('%22','&quot;',$parameters[$i]);
		$parameters[$i] = urldecode($parameters[$i]);
		$parameters[$i] = addslashes($parameters[$i]);
		$parameters[$i] = str_replace('&quot;','\\&quot;',$parameters[$i]);
		//$parameters[$i] = str_replace('&quot;','\&quot;',$parameters[$i]);
	}
	return $parameters;
}

/**
 * Get parameters from URL.
 * @return	list of parameters (title, id, category)
 */
function getURLParameters2($path) {
	$request = $_SERVER['QUERY_STRING'];
	
	$sharpPosition = strpos($request, "#");
	if ($sharpPosition !== false) {
		$request = substr($request, 0, strlen($request)-$sharpPosition);
	}
	$parameters = explode("/", $request);

	// Remove empty spaces
	for ($i=0; $i<sizeof($parameters); $i++) {
		if (empty($parameters[$i])) {
			array_splice($parameters, $i,1);
		}
	}

	// Decode characters
	for ($i=0; $i<sizeof($parameters); $i++) {
		$parameters[$i] = str_replace("%27","'",$parameters[$i]);
		$parameters[$i] = str_replace("%26amp%3B","&",$parameters[$i]);
		$parameters[$i] = urldecode($parameters[$i]);
	}
	return $parameters;
}

/** 
 * Get full username 
 * @param $id User id
 * @return string with user's full name
 */
function getUserFullname($id) {	
	global $dbi;
	
	if(!empty($id)) {		
		$result = $dbi->query("SELECT name FROM ".userDataTableName." WHERE id=$id");
		if($result->rows()) {
			list($name) = $result->fetchrow_array();
			return $name;		
		}
	}
	return false;	
}

/**
 * Convert an integer to day of week.
 * @param	$day	Day number.
 * @return textual representation of day of week.
 */
function intToDay($day) {
	global $lDays;
	switch ($day) {
		case 0: return $lDays["Sunday"]; 
		case 1: return $lDays["Monday"];
		case 2: return $lDays["Tuesday"];
		case 3: return $lDays["Wednesday"];
		case 4: return $lDays["Thursday"];
		case 5: return $lDays["Friday"];
		case 6: return $lDays["Saturday"];
		case 7: return $lDays["Sunday"]; 
	}
	return "";
}

/**
  * Convert an integer to a month of the year. 
  * @param	$mnum	Month number.
  * @return textual representation of month of the year.
  */
function intToMonth($mnum) {
	global $site;
	return $site->getMonthName($mnum);
}

/**
  * Is the given filetype supported?
  * @param	$fileType	Filetype to check. 
  */
function isFiletypeSupported($filetype, $filetypes=array()) {
	$file = new File();
	return $file->isFiletypeSupported($filetype, $filetypes);
}

/** 
 * Function that logs transaction in database
 * @param $type Type of log transaction
 * @param $typeId Id of resource to log
 */
function logTransaction($type,$typeId) {	
	global $dbi,$login;
	
	/* Log transaction */	
	$result = $dbi->query("SELECT UNIX_TIMESTAMP(uploaded) as uploaded FROM ".logTableName." WHERE type='$type' AND typeId=$typeId");	
	if($result->rows()) {	
		list($uploaded) = $result->fetchrow_array();		
		$dbi->query("UPDATE `".logTableName."` SET uploaded=FROM_UNIXTIME($uploaded),lastUpdated=NOW(),lastUpdatedBy='".$login->id."' WHERE type='$type' AND typeId=$typeId");	
	}	
	else {		
		$dbi->query("INSERT INTO `".logTableName."`(type,typeId,uploaded,uploadedBy,lastUpdated,lastUpdatedBy) VALUES('$type',$typeId,NOW(),'".$login->id."',NOW(),'".$login->id."')");	
	}
}

/**
 * Print letters for navigating. TODO: Localize and document.
 * @param $total	
 * @param $type
 * @param $filename
 * @param $letter
 */
function printLetters($total,$type=1,$filename="index.php?",$letter="") {
	global $lLetters;

	if (pageLanguage=="da") {
		if($type==1) $letters = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","Æ","Ø","Å",$lLetters["Misc"],$lLetters["News"],$lLetters["Everything"]);
		else if($type==3) $letters = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","Æ","Ø","Å",$lLetters["Misc"],$lLetters["News"],"SÃ¸g",$lLetters["Everything"]);
		else $letters = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","Æ","Ø","Å",$lLetters["Misc"],$lLetters["Search"],$lLetters["Everything"]);
	}
	else {
		if($type==1) $letters = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",$lLetters["Misc"],$lLetters["News"],$lLetters["Everything"]);
		else if($type==3) $letters = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",$lLetterss["Misc"],$lLetters["News"],"SÃ¸g",$lLetters["Everything"]);
		else $letters = Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z",$lLetters["Misc"],$lLetters["Search"],$lLetters["Everything"]);		
	}

	$letterString = "";
	for($i=0;$i<sizeof($letters);$i++) {
		$letterString .= "<a href=\"$filename"."letter=".urlencode($letters[$i])."\" class=\"black1\"".($letter==$letters[$i]?" style=\"font-weight:bold;text-decoration:underline\"":"").">";
		if($letters[$i]==$lLetters["Everything"]) $letterString .= $letters[$i]."($total)";
		else if($letters[$i]==$lLetters["Misc"]) $letterString .= $lLetters["Misc"];
		else if($letters[$i]==$lLetters["News"]) $letterString .= $lLetters["News"];
		else if($letters[$i]==$lLetters["Popular"]) $letterString .= $lLetters["Popular"];
		else $letterString .= $letters[$i];
		$letterString .= "</a> ";
	}
	return $letterString;
}

/**
 * Print rich HTML textarea
 * @param $form Name of parent form
 * @param $name Name of textfield
 * @param $value Value of textfield
 * @param $rows Rows in textfield
 * @param $cols Columns in textfield
 * @param $showSmilies Display smilies in textfield
 */
function printRichTextArea($form,$name,$value,$rows,$cols,$showSmilies=1,$tabIndex=0,$type=0,$folder="") {
	global $login,$tinyLiteIncluded,$tinyIncluded,$tinyUserIncluded;
	
	// Declare variables
	if (!isset($tinyIncluded))$tinyIncluded = false;
	if (!isset($tinyLiteIncluded)) $tinyLiteIncluded = false;
	if (!isset($tinyUserIncluded)) $tinyUserIncluded = false;
	
	// JavaScript path to textarea
	$path = $form.".".$name;
	
	// Include language
	include scriptPath."/include/language/".pageLanguage."/admin.php";

	// Include TinyMCE form
	if ($type==1) {
		include scriptPath."/include/form/tiny_mceForm.lite.php";
		$tinyLiteIncluded = true;
	}
	else if ($type==2) {
		include scriptPath."/include/form/tiny_mceForm.user.php";
		$tinyUserIncluded = true;		
	}
	else {
		include scriptPath."/include/form/tiny_mceForm.php";
		$tinyIncluded = true;
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
	global $site;
	$site->printSectionHeader($title, $links, $makeExpandable, $isExpanded, $idToExpand);
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
	global $site;
	$site->printSubsectionHeader($title, $links, $makeExpandable, $isExpanded, $idToExpand);
}

/**
 * Print search results.
 * @param	$searchString	String the search was for.
 * @param	$result			Database result object.
 * @param	$prefixUrl		URL to prefix search result links.
 */
function printSearchResults($searchString, $result, $prefixUrl) {
	global $lSearch;
	if($result->rows()) {
		$highlight = str_replace("\"","",stripslashes($searchString));
		echo "<ul>";
		for($i=0;(list($id,$title,$text,$score)=$result->fetchrow_array());$i++) {
			$text = stripslashes(strip_tags(stripBBcode($text)));
			$position = stripos($text, $searchString);
			if ($position==false) {
				$preview = substr(stripslashes(strip_tags(stripBBcode($text))),0,200).(strlen($text)>200?"...":"");
			}
			else {
				$preview = "...".substr(stripslashes(strip_tags(stripBBcode($text))),$position,200).(strlen($text)>($position+200)?"...":"");				
			}
			echo "<li><a href=\"".$prefixUrl.$id."&amp;highlight=".$highlight."\"><b>".stripslashes($title)."</b></a><br />";
			echo "\"".$preview."\"<br /><a href=\"".$prefixUrl.$id."&amp;highlight=".$highlight."\">".str_replace("http://","",$prefixUrl.$id)."</a>. Relevance: ".number_format($score,1).".<br /><br /></li>";
		}
		echo "</ul>";
	}
	else {
		echo "<p><i>".$lSearch["NoSearchResult"]."</i></p>";
	}	
}

/**
 * Print a search result item in a search for a given search string.
 * @param	$searchString	String search was for.
 * @param	$title			Title of search result item.
 * @param	$text			Text of search result item.
 * @param	$link			Link to search result item.
 * @param	$score			Relevance score of search item.
 */
function printSearchResultItem($searchString, $title, $text, $link, $score) {
	$text = stripslashes(strip_tags(stripBBcode($text)));
	$position = strpos(convertToLowercase($text), convertToLowercase($searchString));
	if ($position===false) {
		$preview = substr($text,0,200).(strlen($text)>200?"...":"");
	}
	else {
		$preview = "...".substr($text,$position,200).(strlen($text)>($position+200)?"...":"");				
	}
	echo "<li><a href=\"".$link."\"><b>".$title."</b></a><br />";
	echo "\"".$preview."\"<br /><a href=\"".$link."\">".substr($link,0,50).(strlen($link)>50?"...":"")."</a>.<br /><br /></li>";		
	// Relevance: ".number_format($score,1).".
}	

// Function that prints a menuitem in the various sections
function printSectionItem($title,$picture,$link,$count,$description) {
?>
<table width="100%" align="center">
<tr>
<td width="50" valign="top">
<a href="<?= $link ?>">
<img src="<?= $picture ?>" width="50" height="50" alt="" border="0"  class="border" />
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
 * Print an error message in Javascript.
 * @param 	$text	Error text to display in dialog.
 */
function printErrorMessage($text) {
	if (!empty($text)) {
		echo " <b class=\"small1\" style=\"color:#cc0000;\">*".$text."</b>";		
	}	
}

/**
 * Print an list of error messages
 * @param 	$errors		List of errors to print.
 */
function printErrorMessages($messages) {
	if (!empty($messages)) {
		echo "<ul>";
		for ($i=0; $i<sizeof($messages); $i++) {
			echo "<li><span style=\"color:#cc0000;font-weight:bold\">".$messages[$i]."</span></li>";
		}
		echo "</ul>";
	}
}

function printFormattedTimestamp($timestamp) {
    echo generateFormattedTimestamp($timestamp);
}

/** 
 * Print common footer.
 * @param	$path	Current path.
 */
function printFooter($path="") {
	global $site;
	
	// Print footer
	$site->printFooter();
}

/**
 * Print common header 
 * @param $title Title of page
 * @param $path Path of current page (used for inclusion of module pages)
 * @param $navigation Array containing navigation to the current page  
 * @param $printHeader Print title on top of the page.
 */
function printHeader($title, $path="", $navigation="", $printHeader=false) {
	global $site;
	
	// Set values in object
	$site->setNavigationLinks($navigation);
	$site->setPath($path);
	$site->setTitle($title);

	// Print header
	$site->printHeader($printHeader);
}

/**
  * Print textual month.
  * @param 	$month 	Month to print as text.
  */
function printMonth($month) {
	global $site;
	$site->printMonthName($month);
}

/** 
 * Print page index 
 * @param 	$file 	File to send page arguments to
 * @param 	$page 	Current page number
 * @param 	$count 	Total count of resources
 * @param 	$limit 	Resources to display per page
 * @param 	$mode 	Page index mode
 * @param 	$anchor Anchor on page to jump to
 * @return 	string with forward/back navigation and pages
 */
function printPageIndex($file,$page,$count,$limit=15,$mode="",$anchor="") {
	global $site;
	$site->printPageLinks($file, $page, $count, $limit, $mode, $anchor);
}

/** 
 * Generate html to display popup window
 * @param 	$url 	Url to display in popup window
 * @param 	$title 	Title of popup window
 * @param 	$height Height of popup window
 * @param 	$width 	Width of popup window
 * @return 	string with popup html/javascript code.
 */
function printPopup($url,$title,$style="",$height=500,$width=400) {
	global $site;
	$site->printPopupLink($url,$title,$style,$height,$width);
}

/**
  * Print timestamp using the default time format.
  * @param 	$timestamp 	Timestamp to print.
  * @param 	$shorttime 	Display short time without hours and minutes.
  * @return Formatted timestamp.
  */
function printTimestamp($timestamp, $shorttime=false, $custom="") {
	global $site;
	$site->printTimestamp($timestamp,$shorttime,$custom);
}

/** 
 * Prints transaction data for a resource 
 * @param $type Type of log transaction
 * @param $typeId Id of resource to fetch data about
 * @param $published Publishing information about resource
 */
function printTransactions($type,$typeId,$published="") {	
	global $dbi;
	
	/* Include language */
	include scriptPath."/include/language/".pageLanguage."/general.php";
	
	/* Print blog */
	$published = empty($published)?mktime():$published;	
	$result = $dbi->query("SELECT UNIX_TIMESTAMP(uploaded),uploadedBy,UNIX_TIMESTAMP(lastUpdated),lastUpdatedBy FROM `".logTableName."` WHERE type='$type' AND typeId=$typeId");	
	if($result->rows()) {
		list($uploaded,$uploadedBy,$lastUpdated,$lastUpdatedBy) = $result->fetchrow_array();
		echo "<p class=\"small1\">";
		if(!empty($uploadedBy)) echo "$lLog[CreatedBy] ".getUserFullname($uploadedBy)." ".printTimestamp($uploaded)."<br />";
		if(!empty($lastUpdatedBy)) echo "$lLog[LastUpdatedBy] ".getUserFullname($lastUpdatedBy)." ".printTimestamp($lastUpdated)."</p>";	
	}
}

/** 
 * Protect email from automatic harvesting 
 * @param $mail 	Mail address to protect.
 * @param $title	Mail link title.
 * @param $style 	CSS style to apply to link.
 */
function protectMail($mail,$title="",$style="") {
	global $site;
	return $site->generateProtectMailLink($mail,$title,$style);
}

/** TODO */
/** 
 * HTTP authentication
 * @param $minUserlevel Minimum userlevel required to pass authentication
 */
function protectPage($minUserlevel) {
	global $dbi,$userTableName;

	$auth = false;
	if(isset( $_SERVER['PHP_AUTH_USER'] ) && isset($_SERVER['PHP_AUTH_PW'])) { 
		$result = $dbi->query("SELECT password FROM $userTableName WHERE username='".mysql_real_escape_string($_SERVER['PHP_AUTH_USER'])."'");
		list($real_pass) = $result->fetchrow_array();
  		if($_SERVER['PHP_AUTH_PW']==$real_pass) {
  			$auth = true;
  		}
	}
	if(!$auth) { 
		header('WWW-Authenticate: Basic realm="RSS"'); 
		header('HTTP/1.0 401 Unauthorized'); 
		echo 'Authorization Required.'; 
		exit(); 
	}
	return true;
}

/**
 * Function that prints a link to the help section of the website.
 * @param	$id		Identifier of help item.
 */
function printSectionHelp($id) {
	printPopup(scriptUrl."/".folderHelp."/".fileHelp."?helpId=$id&amp;popup=1", "?");
}

/** 
 * Print section header link
 * @param $title Title of section header
 * @param $url Link of section header
 */
function printSectionHeaderLink($title,$url) {
	echo "<h1><a href=\"$url\" class=\"sectionHeaderLink1\">$title</a></h1>";
}

/** 
 * Print subsection header link
 * @param $title Title of subsection header 
 * @param $url Url of subsection header
 */
function printSubsectionHeaderLink($title,$url) {
	echo "<h2><a href=\"$url\" class=\"sectionHeaderLink1\">$title</a></h2>";
}

/** 
 * Redirect the user to a given url 
 * @param $url Url to redirect to
 */
function redirect($url) {
	if(empty($url)) $url = scriptUrl;
	session_write_close();
	header("Location: $url");
	exit();
}

/** 
  * resizeToFile resizes a picture and writes it to the harddisk
  * @param	$sourcefile 	The filename of the picture that is going to be resized
  * @param 	$dest_x 		X-Size of the target picture in pixels
  * @param	$dest_y			Y-Size of the target picture in pixels
  * @param	$targetfile 	The name under which the resized picture will be stored
  * @param	$jpegqual   	The Compression-Rate that is to be used
  * @param	$addBackground	Add background to make the image fit desired dimensions.
  * @param	$crop			Crop image to fit desired dimensions.
  * @param	$blackAndWhite	Black and white image.
  * @return true if resize was successful, false otherwise.
  */
function resizeToFile ($sourcefile, $dest_x, $dest_y, $targetfile, $jpegqual, $addBackground = false, $crop = false, $blackAndWhite = false) {
	$file = new File();
	return $file->resizeImage($sourcefile, $dest_x, $dest_y, $targetfile, $jpegqual, $addBackground, $crop, $blackAndWhite);
}

function strleft($s1, $s2) { 
	return substr($s1, 0, strpos($s1, $s2)); 
}

/**
  * Upload an image to the server at a given location
  * @param $img
  * @param $img_name
  * @param $img_size
  * @param $img_type
  * @param $targetFile
  * @param $widthLimit
  * @param $heightLimit
  * @param $sizeLimit
  * @return boolean determining if upload was succesfull
  */
function uploadImage($img,$img_name,$img_size,$img_type,$targetFilename,$widthLimit=0,$heightLimit=0,$sizeLimit=0) {
	// Limit size of image
	$limitSize = ($sizeLimit!=0?true:false);

	// Limit proportions of image
	$limitProportions = ($widthLimit!=0 && $heightLimit!=0?true:false);

	// Image types to upload
	$cert1 = "image/jpeg";
	$cert2 = "image/gif";
	$cert3 = "image/pjpeg";

	// Check if file exists
	if(!empty($img_name)) {
		/* Check if file is too big */
		if($limitSize && $img_size>$sizeLimit) {
			return false;
		}
		else {
			// Checks if file is an image
			if($img_type==$cert1 || $img_type==$cert2 || $img_type==$cert3) {
				if($limitProportions) {
					/* Check if image has the right proportions */
					$size = getImageDimensions($img);

					if($size[0]>$widthLimit || $size[1]>$heightLimit) {
						if (GDInstalled) {
							resizeToFile ($img, 75, 75, $img, 100);
						}
						return false;
					}
				}
			}
			else {
				return false;
			}
		}

		// If no errors upload image
		$to = scriptPath."/".folderUploadedFiles."/$targetFilename";
		if(file_exists($to)) unlink($to);
		@copy($img,$to);
		if(file_exists($to)) return true;
		return false;
	}
	return false;
}

/**
 * Save a file to the upload folder.
 * @param $filename		Name of file.
 * @param $content		Content of file.
 */
function saveFile($filename, $content) {
	if (empty($filename)) return;
	$fh = fopen(scriptPath."/".folderUploadedFiles."/".$filename, 'w');
	fwrite($fh, $content);
}

/**
 * Upload file
 * @param 	$file			Array of file values from the $_FILES array.
 * @param	$filename		Location to move uploaded file to.
 * @param 	$mimeTypes		List of mime types to allow.
 * @param 	$fileExtensions	List of file extensions to allow.
 * @param 	$sizeLimit		Maximum allowed size of file.
 * @param 	$widthLimit		Maximum allowed width of file.
 * @param 	$heightLimit	Maximum allowed height of file.
 * @return 	ErrorLog object.
 */
function uploadFile($file, $filename, $mimeTypes=array(), $fileExtensions = array(), $sizeLimit=0, $widthLimit=0, $heightLimit=0) {
	// Keep errors
	$errorLog = new ErrorLog();
	
	// Get file extension
	$fileExtension = getFileExtension($file["name"]);
	
	// Limit size of image
	$limitSize = $sizeLimit!=0 ? true : false;

	// Limit proportions of image
	$limitProportions = ($widthLimit!=0 && $heightLimit!=0?true:false);
	
	// Set allowed mime types to default if not set
	if (sizeof($mimeTypes)==0) {
		global $safeMimeTypes;
		$mimeTypes = $safeMimeTypes;
	}

	// Set allowed file extensions to default if not set
	if (sizeof($fileExtensions)==0) {
		global $safeFileExtensions;
		$fileExtensions = $safeFileExtensions;
	}
	
	// Check if file exists
	if(!empty($file["tmp_name"])) {
		// Check if file is too big
		if($limitSize && $file["size"]>$sizeLimit) {
			$errorLog->addError("", "File is too big. Maximum size allowed is ".$limitSize." KB.");
		}

		// Checks if file is an allowed mimetype
		$mimeTypeAllowed = false;
		for ($i=0; $i<sizeof($mimeTypes); $i++) {
			if ($file["type"]==$mimeTypes[$i]) {
				$mimeTypeAllowed = true;
				break;
			}
		}
		if (!$mimeTypeAllowed) $errorLog->addError("", "Mime type '".$file["type"]."' is not allowed.");

		// Checks if file has an allowed file extensions
		$fileExtensionAllowed = false;
		for ($i=0; $i<sizeof($fileExtensions); $i++) {
			if ($fileExtension==$fileExtensions[$i]) {
				$fileExtensionAllowed = true;
				break;
			}
		}
		if (!$fileExtensionAllowed) $errorLog->addError("", "File extension '".$fileExtension."' is not allowed.");

		// Check if file exceeds width and height limit
		if($limitProportions) {
			// Check if image has the right proportions
			$size = getImageDimensions($file["tmp_name"]);
			if($size[0]>$widthLimit || $size[1]>$heightLimit) {
				if (GDInstalled) {
					resizeToFile ($file["tmp_name"], $widthLimit, $heightLimit, $file["tmp_name"], 100);
				}
				else {
					$errorLog->addError("", "Image dimensions are too large. Maximum allowed image size is ".$widthLimit."x".$heightLimit.".");
				}
			}
		}

		// If no errors upload image
		if (!$errorLog->hasErrors()) {
			$to = scriptPath."/".folderUploadedFiles."/".$filename.".".$fileExtension;
			if(file_exists($to)) unlink($to);
			@copy($file["tmp_name"], $to);
		}
	}
	return $errorLog;
} 

/**
 * Clean text and convert to utf8. Useful with RSS/XML.
 * @param $text Text to clean
 */
function cleanText($text) {
	$text = str_replace("\x92","'",$text);
	$text = str_replace("\x96","-",$text);
	$text = str_replace("&","&amp;",$text);
	$text = stripHtml($text);
	$text = stripBBCode($text);
	return stripslashes($text);
}

/** 
 * Convert BBCode tags to html
 * @param $text Text to convert from
 * @param $newlines Add breaks instead of newlines
 */
function convertBBcode($text){
	$patterns = array('`\[b\](.+?)\[/b\]`is',
		'`\[i\](.+?)\[/i\]`is',
		'`\[u\](.+?)\[/u\]`is',
		'`\[h1\](.+?)\[/h1\]`is',
		'`\[h2\](.+?)\[/h2\]`is',
		'`\[strike\](.+?)\[/strike\]`is',
		'`\[color=#([0-9]{6})\](.+?)\[/color\]`is',
		'`\[email\](.+?)\[/email\]`is',
		'`\[img\](.+?)\[/img\]`is',
		'/\[img align=([a-z0-9]+)\](.*?)\[\/img\]/ise',
		'`\[img=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/img\]`si',
		'/\[img id=([0-9]+)\](.*?)\[\/img\]/ise',
		'/\[img id=([0-9]+) align=([a-z0-9]+)\](.*?)\[\/img\]/ise',
		'/\[link=(.*?)\](.*?)\[\/link\]/ise',
		'`\[link\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/link\]`si',
		'`\[link\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/link\]`si',
		'/\[link id=([0-9]+)\](.*?)\[\/link\]/ise',
 		'`\[flash=([0-9]+),([0-9]+)\](.+?)\[/flash\]`is',
  		'`\[quote\](.+?)\[/quote\]`is',
		'/\[code\](.*?)\[\/code\]/ise',
  		'`\[size=([1-6]+)\](.+?)\[/size\]`is',
		'`\[list\](.+?)\[\/list\]`is',
		'`\[list=\](.+?)\[\/list\]`is',					
		'`\[\*\](.+?)\[\/\*\]`is');

	$replaces =  array('<b>\\1</b>',
		'<i>\\1</i>',
		'<u>\\1</u>',
		'<h1>\\1</h1>',
		'<h2>\\1</h2>',
		'<strike>\\1</strike>',
		'<span style="color:#\1;">\2</span>',
		'<a href="mailto:\1">\1</a>',
		'<img src="\1" alt="" style="border:0px;" />',
		'<img src="\2" alt="" style="float:\1;border:0px;" />',
		'<img src="\1\2" alt="\6" style="border:0px;" />',
		"'<img src=\"".scriptUrl."/".folderFiles."/".fileFilesGetFile."?fileId=\\1\" alt=\"\\2\" title=\"\\2\" style=\"border:0px;\" />'",
		"'<img src=\"".scriptUrl."/".folderFiles."/".fileFilesGetFile."?fileId=\\1\" alt=\"\\3\" title=\"\\3\" style=\"float:\\2;border:0px;\" />'",
		"'<a href=\"'.getAbsoluteLink('\\1').'\"'.getLinkTarget(getAbsoluteLink('\\1')).'>'.stripslashes('\\2').'</a>'",
		'<a href="\1\2" target="_blank">\1\2</a>',
		'<a href="http://\1" target="_blank">\1</a>',
		"'<a href=\"".scriptUrl."/".folderFiles."/".fileFilesGetFile."?fileId=\\1\">\\2</a>'",                   
		'<object width="\1" height="\2"><param name="movie" value="\3" /><embed src="\3" width="\1" height="\2"></embed></object>',
		'<b>Quote:</b><div style="margin:0px 10px;padding:5px;background-color:#F7F7F7;border:1px dotted #CCCCCC;width:80%;"><em>\1</em></div>',
 		"'<table cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"code\">'.keepFormatting('\\1').'</td></tr></table>'",
		'<h\1>\2</h\1>',
		'<ul>\\1</ul>',
		'<ol>\\1</ol>',
		'<li>\\1</li>');

	return preg_replace($patterns, $replaces, $text);
}

/**
 * Create links in a given string
 * @param $text Text to linkify.
 */
function convertLinks($text) {
	$text = str_ireplace("<a href='http://","<a href='...", $text); 
	$text = str_ireplace("<a href=\"http://","<a href=\"...", $text); 
	$text = eregi_replace("(>)((f|ht)tps?:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_-]+)", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text); //http 
	$text = eregi_replace("(>)(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_-]+)", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text); // www. 
	$text = eregi_replace("(>)([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","\\1<a href=\"mailto:\\2\">\\2</a>", $text); // mail 
	$text = eregi_replace("([[:space:]])((f|ht)tps?:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_-]+)", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text); //http 
	$text = eregi_replace("([[:space:]])(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_-]+)", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text); // www. 
	$text = eregi_replace("([[:space:]])([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","\\1<a href=\"mailto:\\2\">\\2</a>", $text); // mail 
	$text = eregi_replace("^((f|ht)tp:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_-]+)", "<a href=\"\\1\" target=\"_blank\">\\1</a>", $text); //http 
	$text = eregi_replace("^(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_-]+)", "<a href=\"http://\\1\" target=\"_blank\">\\1</a>", $text); // www. 
	$text = eregi_replace("^([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})","<a href=\"mailto:\\1\">\\1</a>", $text); // mail 
	$text = eregi_replace("@","&#64;",$text);
	$text = str_ireplace("<a href=\"...","<a href=\"http://", $text); 
	$text = str_ireplace("<a href='...","<a href='http://", $text); 
	return $text; 
}

/**
 * Convert newlines in text to breaks without breaking html
 * @param $text Text to convert
 * @return string with converted text
 */
function convertNewlines($text) {
    $text = $text . "\n"; // just to make things a little easier, pad the end
    $text = preg_replace('|<br />\s*<br />|', "\n\n", $text);
    $text = preg_replace('!(<(?:table|ul|ol|li|pre|form|blockquote|h[1-6])[^>]*>)!', "\n$1", $text); // Space things out a little
    $text = preg_replace('!(</(?:table|ul|ol|li|pre|form|blockquote|h[1-6])>)!', "$1\n", $text); // Space things out a little
    $text = preg_replace("/(\r\n|\r)/", "\n", $text); // cross-platform newlines
    $text = preg_replace("/\n\n+/", "\n\n", $text); // take care of duplicates
    $text = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "\t<p>$1</p>\n", $text); // make paragraphs, including one at the end
    $text = preg_replace('|<p>\s*?</p>|', '', $text); // under certain strange conditions it could create a P of entirely whitespace
    $text = preg_replace("|<p>(<li.+?)</p>|", "$1", $text); // problem with nested lists
    $text = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $text);
    $text = str_replace('</blockquote></p>', '</p></blockquote>', $text);
    $text = preg_replace('!<p>\s*(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)!', "$1", $text);
    $text = preg_replace('!(</?(?:table|tr|td|th|div|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*</p>!', "$1", $text);
    $text = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $text); // optionally make line breaks
    $text = preg_replace('!(</?(?:table|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|p|h[1-6])[^>]*>)\s*<br />!', "$1", $text);
    $text = preg_replace('!<br />(\s*</?(?:p|li|div|th|pre|td|ul|ol)>)!', '$1', $text);
    //$text = preg_replace('/&([^#])(?![a-z]{1,8};)/', '&#038;$1', $text); // &AElig;
    return $text;
}

/** 
 * Convert smilies in text
 * @param $text Text to convert smilies in
 */
function convertSmilies($text) {
	// Open the smilies data file for parsing
	$sm = @fread(fopen(iconPath."/smilies/smiles.txt", "r"), 100000);
	$sm = explode("\n",$sm);

	for($i=0;$i<=sizeof($sm);$i++) {
		if(!empty($sm[$i])) {
			list($sml, $sgn) = explode("||", $sm[$i]);
			$text = str_replace(" ".trim($sml)." ", " <img src=\"".iconUrl."/smilies/".trim($sgn)."\" height=\"15\" width=\"15\" border=\"0\" alt=\"\" /> ", $text);
			$text = str_replace(" ".trim($sml), " <img src=\"".iconUrl."/smilies/".trim($sgn)."\" height=\"15\" width=\"15\" border=\"0\" alt=\"\" />", $text);
		}
	}
	return $text;
}

/** 
 * Return text in lowercase
 * @param $text Text to convert to lowercase
 */
function convertToLowercase($text) {
	$text = strtr($text, "ÆØÅÄÖ", "æøåäö");
	return strtolower($text);
}

/** 
 * Return text in uppercase
 * @param $text Text to convert to uppercase
 */
function convertToUppercase($text) {
	$text = strtr($text, "æøåäö", "ÆØÅÄÖ");
	return strtoupper($text);
}

/**
 * Generate formatted timestamp.
 * @param	$timestamp	Timestamp to print.
 */
function generateFormattedTimestamp($timestamp) {
    $formattedTimestamp = "";
    $today = mktime();
    $yesterday = mktime(0, 0, 0, date("m", $today), date("j", $today) - 1, date("Y", $today));
    if (date("j", $timestamp) == date("j", $today) && date("m", $timestamp) == date("m", $today) && date("Y", $timestamp) == date("Y", $today)) {
        $formattedTimestamp = "I dag";
    } else if (date("j", $timestamp) == date("j", $yesterday) && date("m", $timestamp) == date("m", $yesterday) && date("Y", $timestamp) == date("Y", $yesterday)) {
        $formattedTimestamp = "I går";
    } else {
        $formattedTimestamp = date("j", $timestamp) . '. ' . convertToLowercase(substr(intToMonth(date("m", $timestamp)), 0, 3)) . ". " . date("Y", $timestamp);
    }
    $formattedTimestamp .= " kl. " . date("H", $timestamp) . ":" . date("i", $timestamp);
    return $formattedTimestamp;
}

/**
 * Generate a random with a given number of letters.
 * @param	$length	Length of random string.
 */
function generateRandomString($length=8) {
	$r = "";
	for($len=$length, $r='';strlen($r)<$len; $r.=chr(!mt_rand(0,2)?mt_rand(48,57):(!mt_rand(0,1)?mt_rand(65,90):mt_rand(97,122))));
	return $r;
}

/** 
 * Decide whether to open link in a new window
 * @param $link Link to check
 */
function getLinkTarget($link) {
	$reg = "/".str_replace("/","\/",scriptUrl)."/i";
	return !preg_match($reg,$link)?" target=\"_blank\"":"";
}

function getAbsoluteLink($link) {
	if (!preg_match('`([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)`si', $link)) {
		$reg = "/".str_replace("/","\/",scriptUrl)."/i";
		if (!preg_match($reg,$link)) return scriptUrl."/".$link;
	}
	return $link;
}

/**
 * Highlight string in text
 * @param $string String to highlight
 * @param @text Text to search in
 */
function highlightText($string,$text) {
	$string = str_replace("/","\/",$string);
	$text = str_replace("/","\/",$text);

	$text = preg_replace("/($string)/i","<span class=\"highlight\">\\0</span>",$text);
	$text = stripslashes($text);
	return $text;
}

/**
 * Keep formatting in a given text
 * @param $text Text to keep formatted
 */
function keepFormatting($text) {
	//$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = str_replace("  ","&nbsp;&nbsp;&nbsp;",$text);	
	$text = str_replace("\t","&nbsp;&nbsp;&nbsp;",$text);
	return $text;
}

/** 
 * Evaluate body text 
 * @param $text Text to evaluate
 * @param $phpEnabled Execute php code
 * @param $convertSmilies Convert smilies to images
 */
function parseBodyText($text,$convertSmilies=1,$executePHP=0) {
	//$text = preg_replace("/\<img name=\"img$i\"(.*?)title=\"(.*?)\"(.*?)\/>/si","<img$i>$2</img$i>",stripslashes($text));
	
	
	$text = preg_replace_callback("/<pre>\\[code\\](.+?)\[\/code\]<\/pre>/is", "replace_br", $text);

	// Convert code tags
	$text = str_replace('[code]','<textarea name="code" class="c-sharp">',$text);
	$text = str_replace('[/code]','</textarea>',$text);
	
	// Parse string
	//$text = stripslashes($text);

	// Evaluate text
	/*if($executePHP) {
		ob_start();
		eval("?>" . $text . "<?php ");
		$text = ob_get_contents();
		ob_end_clean();
	}*/
	
	// Replace � with '	
	/*$text = str_replace("\x92","'",$text);

	// Convert smilies
	if($convertSmilies) $text = convertSmilies($text);*/

	/* Convert BB codes */
	//$text = convertBBcode($text);	

	// Create links
	/*$text = convertLinks($text);

	// Generate breaks instead new lines
	$text = convertNewlines($text);
	
	// Highlight text
	if (!empty($_GET["highlight"])) {
		$words = explode(" ", stripslashes($_GET["highlight"]));
		for ($i=0; $i<sizeof($words); $i++) {
			if (!empty($words[$i])) $text = highlightText($words[$i],$text);
		}
	}*/

	// Return text
	return $text;
}

/** 
 * Filter out HTML based on the level given.
 * @param $text Text to filer
 * @param $level Filter level (0=remove all html, 1=remove all but basic
 * formatting, 2=remove scripting and other 'unsafe tags')
 */
function parseHtml($text,$level=1) {
	//$text = str_replace("&lt;","<",$text);	
	//$text = str_replace("&gt;",">",$text);	
	$text = str_replace("&nbsp;", " ", $text);
	
	if($level==0) { // Strip all html
		$text = stripHtml($text);
	}
	else if($level==1) { // Strip all html except b,i,u,strong,em,a and strike tags
		$text = strip_tags($text, "<b><i><u><strong><em><a><strike><br><br />");
	}
	else if($level==2) { // Allow breaks and paragraphs
		$text = strip_tags($text, "<b><i><u><strong><em><a><strike><p><br /><br><img>");
		$text = preg_replace('/<p([^>]*)>/i', '<p>', $text); // Remove all attributes in <p>-tags
	}
	else if($level==3) { // Allow basic tags
	    $text = eregi_replace("<([^>]*)>","``\\1%%",$text);
	    $text = eregi_replace("``(/?(b|i|u|p|br|ul|ol|li|code|sub|sup|small|big|strong|center))%%","<\\1>",$text);
	    $text = eregi_replace("``([^(%%)]*)%%","",$text);
	}
	else if($level==4) { // Strip javascript, applets and other unsafe tags
	  	//$text = eregi_replace("</?(style|div|span|layer|html|bgsound|body|meta|applet|embed|iframe|script|object)([^>\n]*)>", "", $text);
	  	//$text = eregi_replace("</?(style|div|span|layer|html|bgsound|body|meta|applet|iframe|script)([^>\n]*)>", "", $text);
		$text = eregi_replace("</?(style|div|layer|html|bgsound|body|meta|applet|script)([^>\n]*)>", "", $text);
		$text = eregi_replace("(onload|onmouseout|onmouseover|onerror)=[\"']([^\n]*)['\"]", "", $text);
	}
	$text = str_replace("<p>&nbsp;</p>", "", $text);
	return trim($text);
}

function parseQuotes($text) {
	return str_replace("\"","&quot;", $text);
}

	
function replace_br($input) {
	$text = str_replace("<br />","\n", $input[1]);
	$text = str_replace("<p>","", $text);
	$text = str_replace("</p>","", $text);
	//$ret = '<div class="code"><pre class="brush: php;">'.$text.'</pre></div>';
	return '[code]'.$text.'[/code]';
}
	
/** 
 * Strip unsafe characters
 * @param $text Text to strip from
 */
function parseString($text,$stripHtml=0) {
	$text = stripslashes($text);
	if($stripHtml) {
		$text = str_replace("\"","&quot;",$text);		
		$text = strip_tags($text);
	}
	$text = str_replace("& ","&amp; ",$text);

	//$text = stripslashes(nl2br($text));
	
	// Restore urls to site
	$text = restoreSiteUrls($text);

	// Return parsed text
	return $text;
}

/**
 * Parse thumbnail images to look for gallery images.
 * @param	$text	Text.
 */
function parseThumbnailImages($text) {
	$text = stripslashes($text);
	
	// Remove old links
	$text = preg_replace('`<a id="single_image"([^>]*)>(.+?)</a>`is', '\2', $text);
	
	// Find thumbnail images
	$text = preg_replace('`<img ([^>]*)src="([^>]*)&amp;imageWidth=([^(>"&)]*)"([^>]*) />`is', '<a id="single_image" href="\2" rel="gallery"><img \1src="\2&amp;imageWidth=\3"\4 /></a>', $text);
	return addslashes($text);
}

/**
  * Remove paragraphs at start and end of the text added by the TinyMCE editor
  * @param	$text	Text to remove paragraphs from.
  * @return	$text	Text without paragraphs.
  */
function removeEditorParagraphs($text) {
	$text = trim($text);
    $text = eregi_replace("<([^>]*)>","``\\1%%",$text);
    $text = eregi_replace("``(/?(b|i|u|a))%%","<\\1>",$text);
    $text = eregi_replace("``([^(%%)]*)%%","",$text);
	
	/*if (substr($text, 0, 3)=="<p>") $text = trim(substr($text, 3, strlen($text)));
	if (substr($text, strlen($text)-4, strlen($text))=="</p>") $text = trim(substr($text, 0, strlen($text)-4));*/
	return $text;
}

function replaceSiteUrls($text) {
	$text = str_replace(scriptUrl, "[siteurl]", $text);
	return $text;
}

function restoreSiteUrls($text) {
	return str_replace("[siteurl]", scriptUrl, $text);	
}

/**
  * Removes all BBcode tags.
  * @param     string      $text 
  * @return    string 
  */
function stripBBcode($text) {
	$text = preg_replace("/\[(.*?)\]/si","",stripslashes($text));
	return $text;
}

/**
 * Filter out all HTML
 * @param $text Text to filter.
 */
function stripHtml($text) {
	return strip_tags($text);
}

/**
 * Strip generated image tags from text. - DEPRECATED
 * @param $text Text to parse
 * @param $abpath Absolute path of image directory
 * @param $id Id of resource
 * @param $pictures Number of pictures to search for 
 */
function stripImgTags($text,$abpath,$id,$pictures) {
	for($i=0;$i<$pictures;$i++) {
		/* Replace img-tags with html-tags */
		if(file_exists(scriptPath."/$abpath/$id"."_$i.jpg")) {
			$text = preg_replace("/\<img name=\"img$i\"(.*?)title=\"(.*?)\"(.*?)\/>/si","<img$i>$2</img$i>",stripslashes($text));
		}
	}
	return stripslashes($text);
}

/**
  * Validate text length and return short version if too long.
  * @param	$text			Text to validate
  * @param	$max_allowed	Maximum number of characters allowed.
  * @param	$split_at_space	Split at the first available space after max allowed length.
  */
function validateTextLength($text, $max_allowed, $split_at_space=true) {
	if (strlen($text)>$max_allowed) {
		// Find first space
		$pos = strpos($text, ' ', $max_allowed);
		if ($pos===false || !$split_at_space) {
			return substr($text,0,$max_allowed)."...";
		}
		else {
			return substr($text,0,$pos)."...";
		}			
	}
	return $text;
}

/*$str = the string you want to process.
$cols = choose ie.80 for prop.fonts
       choose ie.450 for non prop fonts
       NOTE: $cols are not pixels!
$non_prop = choose "true" for arial,verdana etc.
           false = fixed (courier)
$exclude1 = excludesystem, read the begin of this note. enter '<'
$exclude2 = enter '>'*/
function wordWrapText($str="",$cols=95,$non_prop=true,$cut,$exclude1="<",$exclude2=">"){
	$count=0;
	$tagcount=0;
	$str_len=strlen($str);
	//$cut=" $cut ";
	$calcwidth=0;
 
	for ($i=1; $i<=$str_len;$i++){
		if (empty($str[$i])) continue;
		$str_len=strlen($str);
		if ($str[$i]==$exclude1)
			$tagcount++;
		elseif ($str[$i]==$exclude2){
			if ($tagcount>0)
				$tagcount--;
   		}
		else{
			if (($tagcount==0)){
				if (($str[$i]==' ') || ($str[$i]=="\n"))
					$calcwidth=0;
				else{
					if ($non_prop){
						if (ereg("([QWOSDGCM#@m%w]+)",$str[$i],$matches))
							$calcwidth=$calcwidth+7;
						elseif (ereg("([I?\|()\"]+)",$str[$i],$matches))
							$calcwidth=$calcwidth+4;
						elseif (ereg("([i']+)",$str[$i],$matches))
							$calcwidth=$calcwidth+2;
						elseif (ereg("([!]+)",$str[$i],$matches))
							$calcwidth=$calcwidth+3;
						else{
							$calcwidth=$calcwidth+5;
						}
					}
					else{
						$calcwidth++;
					}
					if ($calcwidth>$cols){
						$str=substr($str,0,$i-1).$cut.substr($str,$i,$str_len-1);
						$calcwidth=0;
					}
				}
			}
		}
	}
	return $str;
}
?>