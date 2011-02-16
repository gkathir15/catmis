<?
class RSS {
	var $description = "";
	var $items = array();
	var $link = "";
	var $rssLink = "";
	var $title = "";
	
	function RSS($title, $description, $link, $rssLink, $items) {
		$this->description = $description;
		$this->items = $items;
		$this->link = $link;
		$this->rssLink = $rssLink;
		$this->title = $title;
	}
	
	function generateRSSFeed() {
		$feed = "<?xml version=\"1.0\" encoding=\"utf-8\"?".">";
		$feed .= "\n";
		$feed .= "<!-- generator=\"CMIS/".version."\" -->";
		$feed .= "<rss version=\"2.0\"";
		$feed .= " xmlns:atom=\"http://www.w3.org/2005/Atom\"";
		$feed .= " xmlns:content=\"http://purl.org/rss/1.0/modules/content/\"";
		$feed .= " xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\"";
		$feed .= ">\n";
		$feed .= "<channel>\n";

		$feed .= "<title>".cleanText($this->title)."</title>\n";
		$feed .= "<link>".cleanText($this->link)."</link>\n";
		$feed .= "<description>".cleanText($this->description)."</description>\n";
		$feed .= "<atom:link href=\"".$this->rssLink."\" rel=\"self\" type=\"application/rss+xml\" />";
		if (sizeof($this->items)!=0) {
			for ($i = 0; $i<sizeof($this->items); $i++) {
				$feed .= $this->items[$i]->generateRSSItem();
			}
		}		
		
		$feed .= "</channel>\n";
		$feed .= "</rss>";		
		return $feed;
	}
	
	function printRSSFeed() {
		header("Content-type: text/xml");
		echo $this->generateRSSFeed();	
	}
}
?>