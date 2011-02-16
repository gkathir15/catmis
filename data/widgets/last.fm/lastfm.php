<?php
global $settings;
/*
  blogscrobbler v1.3 by amkdesign.co.uk

  - caches the recent tracks text feed
  - you can choose how many songs to display
  - gets updates for cache if it's X minutes old or empty, where
    X is 30 seconds minimum track length times the number of tracks
  - cache is still output if update cannot be accessed
  - still outputs cache if you haven't listened to exactly the
    number of tracks you want to display
  - if your recent track list update is empty,
    it will be ignored and the cache will still be output
  - can cope with bands/tracks with obscure characters
  - ouputs data in an ordered list
*/

$user = 'maquatre';
$cacheFile = cachePath.'/recenttracks.txt';
$tracks = 15;

// cache is old or empty, get more recent data
if (time() - @filemtime($cacheFile) > 30*$tracks || filesize($cacheFile) == 0 || !$settings->enableCaching) {
	$update = @file_get_contents("http://ws.audioscrobbler.com/1.0/user/$user/recenttracks.txt");
	// if the update isn't empty, update the cache
	if ($update) {
		file_put_contents($cacheFile, $update);
	}
	else { // If update fails rewrite file
		file_put_contents($cacheFile, @file_get_contents($cacheFile));		
	}
}

// read cached data
$cacheFile = @file_get_contents($cacheFile);
$line = explode("\n", $cacheFile);
$lines = count($line)-1;

$i = 0;
echo '<table cellspacing="0" cellpadding="0">';
/*
  while the number of tracks ($i) is smaller than the number of tracks wanted ($tracks)
  and the number of tracks is smaller than the number of available lines ($lines)
  keep displaying...
*/
while ($i < $tracks && $i < $lines) {
	$details = explode(',', $line[$i], 2);
	$details = explode(' – ', $details[1], 2);
	/*
	  lots of trial and error with band name hyperlinks
	  lead me to this which seems to work for all
	*/
	$url = rawurlencode(urlencode($details[0]))."/_/".rawurlencode(urlencode($details[1]));
	$artist = htmlentities(utf8_decode($details[0]));
	$track = htmlentities(utf8_decode($details[1]));
	echo "<tr><td valign=\"top\" style=\"padding-bottom:8px\">&nbsp;»&nbsp;</td><td style=\"padding-bottom:8px\"><a href=\"http://last.fm/music/$url/\" target=\"_blank\" class=\"menu1\">$artist: $track</a></td></tr>";
	$i++;
}
echo '</table>';
?>
