<?php
// Get cache content
global $cache;
$content = $cache->getCacheFileContent("", "googlereader", 3600*4);
if (empty($content)) {
	$metaFeed = "http://www.google.com/reader/public/atom/user/01125475867569551235/state/com.google/broadcast";
	$metaTitle = "Shared Links";
	$metaTitleLink = "http://reader.google.com";
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
	$feed = "http://www.google.com/reader/public/atom/user/01125475867569551235/state/com.google/broadcast";	
	$rawFeed = file_get_contents($feed);
	$xml = new SimpleXmlElement($rawFeed);

	// step 2: extract the channel metadata
	/*$channel = array();
	$channel["title"]       = $xml->channel->title;
	$channel["link"]        = $xml->channel->link;
	$channel["description"] = $xml->channel->description;
	$channel["pubDate"]     = $xml->pubDate;
	$channel["timestamp"]   = strtotime($xml->pubDate);
	$channel["generator"]   = $xml->generator;
	$channel["language"]    = $xml->language;*/

	// step 3: extract the articles
	foreach ($xml->entry as $item) {
        $article = array();
        $article["title"] = trim($item->title);
        $article["link"] = trim($item->link->attributes()->href);
        if (!empty($article["title"])) {
			$metaCount = -1;
			$metaLink = $article["link"];
			$metaTitle = $article["title"];
			include layoutPath."/template/metaBody.template.php";
			$content .= $metaBody;
			// $content .= "<tr><td valign=\"top\" style=\"padding-bottom:8px\">&nbsp;»&nbsp;</td><td style=\"padding-bottom:8px\"><a href=\"".$article["link"]."/\" target=\"_blank\" class=\"menu1\">".$article["title"]."</a></td></tr>";
        }
	}

	include layoutPath."/template/metaFooter.template.php";
	$content .= $metaFooter;	

	// Cache file
	$cache->cacheFile("", "googlereader", $content);
}
echo $content;

/*
Plugin Name: LordElph's Google Reader RSS Widget
Plugin URI: http://blog.dixo.net/downloads/google_reader_rss_widget
Description: Designed to cope better with Google Reader's 'shared posts' feed
Author: Paul Dixon
Version: 1.0
Author URI: http://blog.dixo.net

	My Widget is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl.txt

	This is a WordPress plugin (http://wordpress.org) and widget
	(http://automattic.com/code/widgets/).
*/


//very simple sax based parser
/*class XMLParser
{
	var $parser;
	var $path;
	var $tree;
	
	var $entry;
	var $entryidx=0;
	
	function XMLParser()
	{
	}

	function parse($data) {
		$this->parser = xml_parser_create();
		xml_set_object($this->parser,$this);
		xml_parser_set_option($this->parser,XML_OPTION_CASE_FOLDING, 0);
		xml_set_element_handler($this->parser,"tag_open","tag_close");
		xml_set_character_data_handler($this->parser,"cdata");


		$this->path="";
		$this->tree=array();

		xml_parse($this->parser,$data);
	}

	function tag_open($parser,$tag,$attributes) {

		if ($tag=='entry')
		{
			$this->entry[$this->entryidx]=array();
			$this->entry[$this->entryidx]['attributes']=$attributes;
		}
		
		if (strlen($this->path))
			$this->path=$this->path."/".strtolower($tag);
		else
			$this->path=strtolower($tag);

		if ($this->path=='feed/entry/title')
		{
			$this->entry[$this->entryidx]['title']='';
		}
		if ($this->path=='feed/entry/summary')
		{
			$this->entry[$this->entryidx]['summary']='';
		}
		
		if ($this->path=='feed/entry/link')
		{
			$this->entry[$this->entryidx]['url']=$attributes['href'];
		}
		if ($this->path=='feed/entry/source/title')
		{
			$this->entry[$this->entryidx]['source']='';
		}
		if ($this->path=='feed/entry/source/link')
		{
			$this->entry[$this->entryidx]['sourceurl']=$attributes['href'];
		}
		
	}

	function cdata($parser,$cdata) {

		if ($this->path=='feed/entry/title')
		{
			$this->entry[$this->entryidx]['title'].=$cdata;
		}
		
		if ($this->path=='feed/entry/summary')
		{
			$this->entry[$this->entryidx]['summary'].=$cdata;
		}
		
		if ($this->path=='feed/entry/source/title')
		{
			$this->entry[$this->entryidx]['source'].=$cdata;
		}
	}

	function tag_close($parser,$tag) {

		$len=strlen($this->path) - (strlen($tag) + 1);
		$this->path=substr($this->path, 0, $len);
		
		if ($tag=='entry')
		{
			$this->entryidx++;
		}
	}

	function get_tag($path)
	{
		return $this->tree[strtolower($path)];
	}

}

//returns path to cached HTML
function widget_lordelph_rss_cachefile($number)
{
	return cachePath."/googlereader_{$number}.html";
}

function printSharedItems($number = 1) {
	extract($args);
	$options = get_option('lordelph_rss');
	if ( isset($options['error']) && $options['error'] )
		return;
	$num_items = (int) $options[$number]['items'];
	$show_summary = $options[$number]['show_summary'];
	if ( empty($num_items) || $num_items < 1 || $num_items > 10 ) $num_items = 10;
	$url = $options[$number]['url'];
	while ( strstr($url, 'http') != $url )
		$url = substr($url, 1);
	if ( empty($url) )
		return;
	$url = "http://www.google.com/reader/public/atom/user/01125475867569551235/state/com.google/broadcast";
	
	//$count=$options[$number]['items'];
	$count = 7;
	
	//do we have a cached file?
	$cache=widget_lordelph_rss_cachefile($number);
	$mtime=0;
	if (file_exists($cache))
		$mtime=filemtime($cache);
	$age=time()-$mtime;

	global $settings;
	
	//cache for 4 hours
	if ($age>(3600*4) || !$settings->enableCaching)
	{
		$xml=file_get_contents($url);
		if (strlen($xml))
		{
			//parse it
			$parser=new XMLParser;
			$parser->parse($xml);
	
			//create output
			$output = '';
	
			//if (strlen($options[$number]['summary']))
			//{
			//	$output.='<p><a title="Click for RSS feed of my favourite posts" href="'.$url.'"><img style="margin-right:2px" align="left" width="14" height="14" src="/wp-includes/images/rss.png"></a>';
			//	$output.=$options[$number]['summary'].'</p>';
			//}
			
			$output.='<table cellspacing="0" cellpadding="0">';			
					
			foreach($parser->entry as $idx=>$entry)
			{
				$entry['title']=str_replace('&#39;', "'",$entry['title']);
				$title = htmlentities(strip_tags($entry['title']));
				$url = strip_tags($entry['url']);
				$source = htmlentities(strip_tags($entry['source']));
				
				$output.="<tr><td valign=\"top\" style=\"padding-bottom:8px\">&nbsp;»&nbsp;</td><td style=\"padding-bottom:8px\">";
				$output.= "<a title=\"via $source\" href=\"$url\" target=\"_blank\" class=\"menu1\">$title</a>";
				$output.="</td></tr>";
				
				if ($idx+1 >= $count)
					break;
			}
			
			$output .= "</table>";
						
			//save output in cache
			$fp=fopen($cache, 'w');
			fwrite($fp, $output);
			fclose($fp);
		}	
	}
	else
	{
		$output = file_get_contents($cache);
	}

	//we we go
	//$title=$options[$number]['title'];
	//echo $title; 
	echo $output;
	return;
}

printSharedItems();*/
?>