<?
// Include common functions and declarations
require_once "include/common.php";

// Get profile identifier
$id = getGetValue("profileId");

if (!empty($id)) {
	// Create user object
	$user = new User($id);

	// Check if user exists
	if (!empty($user->id)) {
		// Add navigation links
		$site->addNavigationLink(scriptUrl."/".fileUserProfile."?profileId=".$user->id, $user->name);

		// Print common header
		$site->printHeader();

		if (file_exists(templatePath.'/profile.template.php')) {
			include templatePath.'/profile.template.php';
		}
		else {
			// Print image
			if (file_exists(scriptPath."/".folderUploadedFiles."/user_".$user->id.".jpg")) echo "<p><img src=\"".scriptUrl."/".folderUploadedFiles."/user_".$user->id.".jpg\" alt=\"".$user->name."\" title=\"".$user->name."\" class=\"border\" style=\"margin-left:5px;margin-bottom:5px;float:right\" /></p>";
		
			// Print position
			if (!empty ($user->position)) {
				echo "<p><b>".$lProfile["Occupation"]."</b><br />";
				echo $user->position;
				echo "</p>";
			}
		
			// Print location
			if (!empty ($user->location)) {
				echo "<p><b>".$lProfile["Location"]."</b><br />";
				echo $user->location;
				echo "</p>";
			}

			// Print phone
			if (!$user->hideTelephone) {
				if (!empty ($user->phone) || !empty ($user->mobile)) {
					echo "<p><b>".$lProfile["Phone"]."</b><br />";
					echo $user->phone. (!empty ($user->mobile) ? (!empty ($user->phone) ? "/" : "").$user->mobile : "");
					echo "</p>";
				}
			}
		
			// Print email
			if (!empty ($user->email) && !$user->hideEmail) {
				echo "<p><b>".$lProfile["Email"]."</b><br />";
				echo protectMail($user->email, $user->email);
				echo "</p>";
			}
		
			// Print website
			if (!empty ($user->linkurl)) {
				$linkurl = $user->linkurl;
				if (substr($linkurl,0,4)!="http") {
					$linkurl = "http://".$linkurl;
				}

				echo "<p><b>".$lProfile["Website"]."</b><br />";
				echo "<a href=\"".$linkurl."\" target=\"_blank\">". (!empty ($user->linkname) ? $user->linkname : $user->linkurl)."</a>";
				echo "</p>";
			}
			
			// Print facebook
			if (!empty($user->facebook)) {
				echo "<p><b>Facebook</b><br />";
				echo "<a href=\"".$user->facebook."\" target=\"_blank\">".$user->facebook."</a>";
				echo "</p>";				
			}

			// Print twitter
			if (!empty($user->twitter)) {
				$twitter = $user->twitter;
				if (substr($twitter,0,1)=="@") $twitter = "http://www.twitter.com/".substr($twitter,1);
				else if (substr($twitter,0,4)!="http") {
					$linkurl = "http://www.twitter.com/".$twitter;
				}
				echo "<p><b>Twitter</b><br />";
				echo "<a href=\"".$twitter."\" target=\"_blank\">".$user->twitter."</a>";
				echo "</p>";				
			}
		
			// Print department
			if (!empty ($user->department)) {
				echo "<p><b>".$lProfile["Department"]."</b><br />";
				echo $user->department;
				echo "</p>";
			}
				
			// Print profile text
			if (!empty ($user->profileText)) {
				echo "<p><b>".$lProfile["Profile"]."</b><br />";
				echo $user->profileText;
				echo "</p>";
			}
		}
		
		// Print common footer
		$site->printFooter();
	}
}
?>