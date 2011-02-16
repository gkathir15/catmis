<?
class RSSItem {
	var $author = "";
	var $categories = array();
	var $commentLink = "";
	var $commentRSS = "";
	var $id = "";
	var $link = "";
	var $summary = "";
	var $text = "";
	var $timestamp = "";
	var $title = "";
	
	function RSSItem($id, $author, $categories, $commentLink, $commentRSS, $link, $summary, $text, $timestamp, $title) {
		$this->id = cleanText($id);
		$this->author = cleanText($author);
		$this->categories = $categories;
		$this->commentLink = cleanText($commentLink);
		$this->commentRSS = cleanText($commentRSS);
		$this->link = cleanText($link);
		$this->summary = cleanText($summary);
		$this->text = $text;
		$this->timestamp = $timestamp;
		$this->title = cleanText($title);
	}
	
	function generateRSSItem() {
		global $settings;
		$item = "<item>\n";
		//echo "<id>".$this->id."</id>";
		$item .= "<title>".$this->title."</title>\n";
		$item .= "<author>".$this->author."</author>\n";
		$item .= "<link>".$this->link."</link>\n";
		if (!empty($this->commentLink)) {
			$item .= "<comments>".$this->commentLink."</comments>\n";
		}
		$item .= "<pubDate>".date("r", $this->timestamp)."</pubDate>\n";
		for ($i=0; $i<sizeof($this->categories); $i++) {
			if (empty($this->categories[$i])) continue;
			$item .= "<category>".cleanText($this->categories[$i])."</category>\n";
		}
		$item .= "<guid>".$this->link."</guid>\n";
		$item .= "<description>".$this->summary."</description>\n";
		if (!empty($this->text)) {
			$item .= "<content:encoded><![CDATA[";
			$item .= $this->text;
			$item .= "]]></content:encoded>\n";
		}
		if (!empty($this->commentRSS)) {
			$item .= "<wfw:commentRss>".$this->commentRSS."</wfw:commentRss>\n";
		}
		$item .= "</item>\n\n";		
		return $item;
	}
	
	function printRSSItem() {
		echo $this->generateRSSItem();
	}
}
?>