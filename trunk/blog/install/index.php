<?
/* Include common functions and declarations */
include "../../include/common.php";

if (!$login->isWebmaster()) {
	$login->printLoginForm();
	exit();
}

if (!$module->isModuleInstalled("Blog")) {
	// Create tables in database
	$dbi->parse_mysql_dump(scriptPath."/blog/install/tables/blog.sql");
		
	// Register content types
	$module->initialize();

	/* Get max position in sections */
	$result = $dbi->query("SELECT MAX(position) FROM ".sectionTableName);
	if ($result->rows()) {
		list($position) = $result->fetchrow_array();	
		$position++;
	}
	
	/* Install blog index in sections */
	$dbi->query("INSERT INTO ".sectionTableName."(parentId,title,link,userlevel,userlevelAdmin,position) VALUES(0,'Blogs','blog/index.php',0,3,$position)");
	
	/* Print common header */
	printHeader("Blog installed");
	
	/* Print section header */
	printSectionHeader("Blog installed");
	echo "<p>The blog module was succesfully installed into CMIS. Remember to remove the install folder for security reasons.</p>";
	
	/* Print footer */
	printFooter();
}
?>