<?
/**
 * SpamFilter contains various functions for detecting spam comments.
 * @author	Kaspar Rosengreen Nielsen
 */
class SpamFilter {
	/**
	 * Check if a comment should be classified as spam according to the spam filter settings.
	 * @param $name Comment name to check.
	 * @param $email Comment email to check.
	 * @param $title Comment title to check.
	 * @param $text Comment text to check.
	 * @return true if post is spam, false otherwise.
	 */
	function isSpam($name,$email,$title,$text) {
		global $settings;
		
		// Check if text contains dirty words
		$words = explode(",",$settings->commentBlacklist);
		for ($i=0;$i<sizeof($words);$i++) {
			$word = trim($words[$i]);
			if (!empty($word)) {
				if(stristr($title, $word) || stristr($text, $word) || stristr($name, $word)) {
					return true;
				}
			}
		}
		
		// Count number of links in text
		$linkCount = count(explode("http://", $text));
		if ($linkCount>$settings->maxNoOfLinksInComments) {
			return true;
		}
		return false;
	}
}
?>