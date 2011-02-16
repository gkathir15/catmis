<?php
if (!empty($twitterUsername)) {
    // Get cache content
    global $cache;
    $content = $cache->getCacheFileContent("", "twitter_".$twitterUsername, 3600*4);
    if (empty($content)) {
            $metaTitle = !empty($twitterName) ? $twitterName : "Twitter";
            $metaTitleLink = !empty($twitterLink) ? $twitterLink : "http://twitter.com/maquatre";
            $metaFeed = !empty($twitterFeed) ? $twitterFeed : "http://twitter.com/statuses/user_timeline/22970953.rss";
            include layoutPath."/template/metaHeader.template.php";
            $content = $metaHeader;

            // define the namespaces that we are interested in
            $ns = array
            (
                    "content" => "http://purl.org/rss/1.0/modules/content/",
                    "wfw" => "http://wellformedweb.org/CommentAPI/",
                    "dc" => "http://purl.org/dc/elements/1.1/"
            );

            // step 1: get the feed
            $rawFeed = file_get_contents($metaFeed);
            $xml = new SimpleXmlElement($rawFeed);

            // step 2: extract the channel metadata
            $channel = array();
            $channel["title"]       = $xml->channel->title;
            $channel["link"]        = $xml->channel->link;
            $channel["description"] = $xml->channel->description;
            $channel["pubDate"]     = $xml->pubDate;

            // step 3: extract the articles
            $count = 0;
            foreach ($xml->channel->item as $item) {
                if ($count > 4) break;
                $article = array();
                //$article["title"] = wordWrapText(str_replace("maquatre: ","", trim($item->title)), 25, false, "<br />");
                $article["title"] = wordWrapText(wordwrap(str_replace($twitterUsername.": ", "", $item->title), 24, "<br />\n"),22,false,"<br />");
                $article["link"] = trim($item->link);

                if (!empty($article["title"])) {
                   $metaTitle = $article["title"];
                   $metaLink = $article["link"];
                   $metaCount = -1;

                   include layoutPath."/template/metaBody.template.php";
                   $content .= $metaBody;
                   // $content .= "<tr><td valign=\"top\" style=\"padding-bottom:8px\">&nbsp;»&nbsp;</td><td style=\"padding-bottom:8px\"><a href=\"".$metaLink."/\" target=\"_blank\" class=\"menu1\">".$metaTitle."</a></td></tr>";
                }
                $count++;
            }

            include layoutPath."/template/metaFooter.template.php";
            $content .= $metaFooter;

            // Cache file
            $cache->cacheFile("", "twitter_".$twitterUsername, $content);
    }
    echo $content;
}
?>