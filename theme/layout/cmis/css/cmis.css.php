<?
require_once "../../../../include/common.php";
header("Content-type: text/css"); 
?>
table.h1 {
	background-image:url(<?= imgUrl ?>/dottedVertical.gif);
	background-position:bottom left;
	background-repeat:repeat-x;
	margin-top:25px;
	margin-bottom:15px;
	margin-right:5px;
}
table.h2 {
	background-image:url(<?= imgUrl ?>/dottedVertical.gif);
	background-position:bottom left;
	background-repeat:repeat-x;
	margin-top:25px;
	margin-bottom:15px;
	margin-right:5px;
}
table.index {
	width:100%;
	border-top:2px #ebebeb solid;
	border-left:2px #ebebeb solid;
	border-bottom:2px #cccccc solid;
	border-right:2px #cccccc solid;
	background-image:url(<?= imgUrl ?>/top_background.jpg);
	background-position:top left;
	background-repeat:repeat-x;
}
td.indexHeader {
	font-size:85%;
	font-weight:bold;
	padding-top:5px;
	padding-bottom:5px;
}
table.info {
	background-color:#ffff99;
	border:1px #cccccc solid;
}
td.item {
	height:30px;
	background-color:#ffffff;
	border-bottom:1px #ebebeb solid;
	padding-right:10px;
}
td.itemAlt {
	height:30px;
	background-color:#ffffff;
	border-bottom:1px #ebebeb solid;
	padding-right:10px;
}

img.border {
	border:1px #333333 solid;
}
.border {
	border: 1px solid #a9a9a9;
}
.code {
	border: #666666 1px solid;
	background-color:#ebebeb;
	padding: 10px;
	color: #333333;
	margin-left: 0px;
}
.draft {
	background-color:#ffff33;
	color:#ff3333;
	font-size:100%;
	font-weight:bold;
	font-style:italic;
}
.error {
	background-color:#FFFF99;
}

/* Form elements */
.formIndent {
	margin-left:16px;
	margin-right:16px;
}
.normalInput {
	border:1px #cccccc solid;
	font-size:90%;	
}
.shortInput {
	border:1px #cccccc solid;
	font-size:90%;	
	width:40%;
}
.longInput {
	border:1px #cccccc solid;
	font-size:90%;	
	width:80%;
}
textarea {
	border:1px #cccccc solid;
	font-size:90%;	
	width:90%;
}

/* Text sizes */
.small1 {
	font-size:85%;
}