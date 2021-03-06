<? 
require_once "../../../../include/common.php";
header("Content-type: text/css"); 

$menuTextColor = "#ffffff";
switch ($settings->subtheme) {
	case "Blue":
		$link = "#003366";
		$linkHover = "#333333";
		$linkVisited = "#003366";		
		break;
	default:
		$link = "#333333";
		$linkHover = "#333333";
		$linkVisited = "#333333";	
		break;
}
?>
html, body {
	font-family: Trebuchet MS, Verdana, Arial, Sans-Serif;
	font-size: 10pt;
	background-color:#ffffff;
	padding:3px;
}

/* Header tags */
h1 {
	font-weight:normal;
	font-family: Georgia, Arial, Sans-Serif;
	font-size:210%;
	margin-top:0px;
	margin-bottom:15px;
	color:#333333;
}
h1.dotted {
	font-weight:normal;
	font-family: Georgia, Arial, Sans-Serif;
	font-size:210%;
	border-bottom:1px #000000 dotted;
	font-size:130%;
	margin-top:25px;
	margin-bottom:15px;
}
h1.menuHeader {
	color:<?= $menuTextColor ?>;
	font-size:260%;	
}
h1.noMargin {
	font-weight:normal;
	font-family: Georgia, Arial, Sans-Serif;
	font-size:210%;
	font-size:130%;
	margin-top:0px;
	margin-bottom:0px;
	color:#000000;
}
h2 {
	font-weight:normal;
	font-family: Georgia, Arial, Sans-Serif;
	font-size:170%;
	margin-top:25px;
	margin-bottom:15px;
	color:#333333;
}
h2.noMargin {
	font-weight:normal;
	font-family: Georgia, Arial, Sans-Serif;
	font-size:170%;
	margin-top:0px;
	margin-bottom:0px;
	color:#000000;
}
h3 {
	font-weight:normal;
	font-family: Georgia, Arial, Sans-Serif;
	font-size:120%;
	margin-top:25px;
	margin-bottom:15px;
	color:#000000;
}
h4 {
	font-size:110%;
	margin-top:25px;
	margin-bottom:15px;
	color:#000000;
}

/* Links */
a:link { 
	color: <?= $link ?>; 
	text-decoration: underline; 
} 
a:visited { 
	color: <?= $linkVisited ?>;
	text-decoration: underline; 
} 
a:hover { 
	color: <?= $linkHover ?>; 
	text-decoration: underline; 
}
a.columnHeader1:link {
	color:#000000; 
	text-decoration: none; 
} 
a.columnHeader1:visited { 
	color:#000000; 
	text-decoration: none; 
}
a.columnHeader1:hover { 
	color:#333333; 
	text-decoration: underline; 
}
a.menu1:link { 
	text-decoration: none; 
} 
a.menu1:visited { 
	text-decoration: none; 
}
a.menu1:hover { 
	text-decoration: underline; 
}
a.menuHeader:link { 
	text-decoration: none; 
} 
a.menuHeader:visited { 
	text-decoration: none; 
}
a.menuHeader:hover { 
	text-decoration: none; 
}
a.navigation1:link { 
	text-decoration: none; 
} 
a.navigation1:visited { 
	text-decoration: none; 
}
a.navigation1:hover { 
	text-decoration: underline; 
}
a.pageLink:link { 
	text-decoration: none; 
} 
a.pageLink:visited { 
	text-decoration: none; 
} 
a.pageLink:hover { 
	text-decoration: underline; 
}
pre {
	font-size:110%;
}
ul.list1 {
	list-style-type:square;
	margin-top:0;
	margin-left:0;
	padding-left:1.3em;
	margin-bottom:0;
}
ul.list1 li {
	padding-bottom:8px;
}