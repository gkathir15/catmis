<?
$metaBody = "<li><a href=\"".$metaLink."\" class=\"menu1\">".$metaTitle."</a>".($metaCount>=0?" <span style=\"font-size:80%;color:#666666\">(".$metaCount.")</span>":"").(!empty($metaInfo) ? '<br /><span style="font-size:80%;color:#666666">' . $metaInfo . '</span><br />' : '' );
			
// Reset values
$metaTitle = "";
$metaLink = "";
$metaInfo = "";
?>