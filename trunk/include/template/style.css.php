<? 
require_once "../../include/common.php";
header("Content-type: text/css"); 
?>

html, body { 
	background-color: #ffffff;
	font-family: Verdana, Arial, Sans-Serif;
	font-size: 90%;
	margin-top: 20px;
	margin-left: 20px;
	margin-right: 20px;
	margin-bottom:20px;
}

table.h1 {
	margin-top:10px;
	margin-bottom:15px;
	margin-right:5px;
}
table.h2 {
	margin-top:10px;
	margin-bottom:15px;
	margin-right:5px;
}
table.index {
	width:100%;
	border-top:2px #ebebeb solid;
	border-left:2px #ebebeb solid;
	border-bottom:2px #cccccc solid;
	border-right:2px #cccccc solid;
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
	font-family: Sans-Serif, Verdana, Arial;
	font-size:90%;	
}
.shortInput {
	border:1px #cccccc solid;
	font-family: Sans-Serif, Verdana, Arial;
	font-size:90%;	
	width:40%;
}
.longInput {
	border:1px #cccccc solid;
	font-family: Sans-Serif, Verdana, Arial;
	font-size:90%;	
	width:80%;
}
textarea {
	border:1px #cccccc solid;
	font-family: Sans-Serif, Verdana, Arial;
	font-size:90%;	
	width:90%;
}

/* Text sizes */
.small1 {
	font-size:85%;
}