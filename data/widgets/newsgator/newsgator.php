<?php
// Get cache content
global $cache;
$content = $cache->getCacheFileContent("", "newsgator", 3600*4);
if (empty($content)) {	
	$metaFeed = "http://services.newsgator.com/ngws/svc/ClippingsRSS.aspx?uid=947256";
	$metaTitle = "Clippings";
	$metaTitleLink = "http://www.newsgator.com/INDIVIDUALS/NETNEWSWIRE/";
	include layoutPath."/template/metaHeader.template.php";
	$content .= $metaHeader;
	
	// define the namespaces that we are interested in
	$ns = array
	(
	        "content" => "http://purl.org/rss/1.0/modules/content/",
	        "wfw" => "http://wellformedweb.org/CommentAPI/",
	        "dc" => "http://purl.org/dc/elements/1.1/"
	);
		
	// step 1: get the feed
	$feed = "http://services.newsgator.com/ngws/svc/ClippingsRSS.aspx?uid=947256";	
	$rawFeed = file_get_contents($feed);
	$xml = new SimpleXmlElement($rawFeed);

	// step 2: extract the channel metadata
	$channel = array();
	$channel["title"]       = $xml->channel->title;
	$channel["link"]        = $xml->channel->link;
	$channel["description"] = $xml->channel->description;
	$channel["pubDate"]     = $xml->pubDate;
	$channel["timestamp"]   = strtotime($xml->pubDate);
	$channel["generator"]   = $xml->generator;
	$channel["language"]    = $xml->language;
		
	// step 3: extract the articles
	foreach ($xml->channel->item as $item) {
        $article = array();
        //$article["channel"] = $blog;
        $article["title"] = trim($item->title);
        $article["link"] = trim($item->link);
        /*$article["comments"] = $item->comments;
        $article["pubDate"] = $item->pubDate;
        $article["timestamp"] = strtotime($item->pubDate);
        $article["description"] = (string) trim($item->description);
        $article["isPermaLink"] = $item->guid["isPermaLink"];*/

        // get data held in namespaces
        /*$content = $item->children($ns["content"]);
        $dc      = $item->children($ns["dc"]);
        $wfw     = $item->children($ns["wfw"]);

        $article["creator"] = (string) $dc->creator;
        foreach ($dc->subject as $subject)
                $article["subject"][] = (string)$subject;

        $article["content"] = (string)trim($content->encoded);
        $article["commentRss"] = $wfw->commentRss;

        // add this article to the list
        $articles[$article["timestamp"]] = $article;*/
        
        if (!empty($article["title"])) {
			$metaTitle = $article["title"];
			$metaLink = $article["link"];		
			$metaCount = -1;
			
			//include layoutPath."/template/metaBody.template.php";
			//$content .= $metaBody;
			$content .= "<tr><td valign=\"top\" style=\"padding-bottom:8px\">&nbsp;»&nbsp;</td><td style=\"padding-bottom:8px\"><a href=\"".$metaLink."/\" target=\"_blank\" class=\"menu1\">".$metaTitle."</a></td></tr>";
			
        }
	}

	include layoutPath."/template/metaFooter.template.php";
	$content .= $metaFooter;	

	// Cache file
	$cache->cacheFile("", "newsgator", $content);
}
echo $content;
?>