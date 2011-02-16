<?
$metaBody = '<table cellspacing="0" cellpadding="0"'.(!empty($metaInfo)?' style="padding-bottom:8px"':'').'><tr><td valign="top">&nbsp;»&nbsp;</td>'.
			'<td width="100%" valign="top"><a href="'.$metaLink.'" class="menu1">'.
			$metaTitle.
			'</a>'.
			($metaCount>=0?' <span style="font-size:80%;color:#666666">('.$metaCount.')</span>':'').
			(!empty($metaInfo) ? '<br /><span style="font-size:80%;color:#666666">' . $metaInfo . '</span><br />' : '' ) .
			'</td></tr></table>';
			
// Reset values
$metaTitle = "";
$metaLink = "";
$metaInfo = "";
?>