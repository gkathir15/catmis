<?
/* 
 * Default timestamp format 
 * This uses the PHP date() function to create the formatting.
 * Please read the PHP documentation before changing this, located at http://www.php.net
*/
$timeFormat 						= 	"M jS, Y, H:i";
$shortTimeFormat 					= 	"M jS, Y";

/* Activate */
$lActivate["Header"]				= 	"Profil Activation";
$lActivate["HeaderText"]			= 	"Your profile has been activated. You can now log in using the form in the top corner.<br /><br />Greetings<br />".pageTitle;
$lActivate["HeaderTextError"]		= 	"Your profile could not be activated. Please contact the ".protectMail(pageAdminMail,"webmaster")." of the site.<br /><br />Greetings<br />".pageTitle;
$lActivate["MailSubject"]			= 	"Profile activated on ".pageTitle;
$lActivate["MailMessage"]			= 	"Hi %s,\n\n" .
										"You are receiving this e-mail as a confirmation that you have activated your profile on ".pageTitle.".\n\n" .
										"You can now log in using the username '%s' and the password you received in the previous mail.\n\n\n" .
										"Greetings!\n" .
										pageTitle;

/* Buttom links */
$lBottom["DirectLink"]				= 	"Direct link";
$lBottom["Edit"]					= 	"Edit";
$lBottom["EditPermissions"]			= 	"Edit permissions";
$lBottom["PrinterFriendly"]			= 	"Printerfriendly version";
$lBottom["RecommendLink"]			= 	"Recommend to friend";
$lBottom["Revisions"]				= 	"Revisions";
$lBottom["RSSFeed"]					= 	"RSS feed";

/* Comments */
$lComment["Header"]					= 	"Comments";
$lComment["WriteComment"]			= 	"Post comment";
$lComment["SendComment"]			= 	"Post comment";
$lComment["Comment"]				= 	"comment";
$lComment["Comments"]				= 	"comments";
$lComment["NoComments"]				= 	"No comments yet.";
$lComment["CommentTextLogged"]		= 	"Below you can read comments to '%s'. Click <a href=\"#post\">here</a> to post a new comment.";
$lComment["CommentTextNotLogged"]	= 	"You must be logged in to write comments.";
$lComment["HideComments"]			= 	"Hide comments";
$lComment["ShowComments"]			= 	"Show comments";
$lComment["ValidationText"]			= 	"For safety reasons you are asked to enter the numbers below in the image in the textfield.";
$lComment["WrongValidation"]		= 	"You have entered an invalid validation code. Enter the numbers in the picture again.";
$lComment["EditComment"]			= 	"Edit comment";
$lComment["Name"]					= 	"Name";
$lComment["Mail"]					= 	"E-mail";
$lComment["Subject"]				= 	"Subject";
$lComment["Message"]				= 	"Message";
$lComment["RememberMe"]				= 	"Remember me";
$lComment["Reply"]					= 	"Re";
$lComment["PostText"]				= 	"Below you can post a comment to '%s'.";

/* Content types */
$lContentType["Category"]			= 	"Category";
$lContentType["Comment"]			= 	"Comment";
$lContentType["Page"]				= 	"Page";
$lContentType["User"]				= 	"User";

/* Days */
$lDays["Monday"]					= 	"Monday";
$lDays["Tuesday"]					= 	"Tuesday";
$lDays["Wednesday"]					= 	"Wednesday";
$lDays["Thursday"]					= 	"Thursday";
$lDays["Friday"]					= 	"Friday";
$lDays["Saturday"]					= 	"Saturday";
$lDays["Sunday"]					= 	"Sunday";

/* Errors */
$lErrors["ErrorsText"]				= 	"The following error(s) must be corrected before this form can be completed.";
$lErrors["ReUploadImages"]			= 	"Images chosen for upload has been lost. Please choose the images again.";

/* Forgot password */
$lForgotPassword["Header"]			=	"Forgot Password";
$lForgotPassword["HeaderText"]		=	"On this page you can request to set a new password for your user on the site if you have forgotten your password.";
$lForgotPassword["Email"]			=	"E-mail";
$lForgotPassword["InvalidKey"]		=	"Your key is invalid.";
$lForgotPassword["MailFailed"]		=	"The system was unable to send you an e-mail.";
$lForgotPassword["MailSubject"]		=	"Forgot password on ".pageTitle;
$lForgotPassword["MailMessage"]		=	"<p>You have requested to change your password because you have forgotten your old password. Click on the link below to change your password:</p><p><a href=\"%s\">%s</a></p><p>Greetings!<br />".pageTitle.'</p>';
$lForgotPassword["MailSent"]		=	"An e-mail has been sent to the e-mail address you registered with. Please click the link in that e-mail to continue.";
$lForgotPassword["Or"]				=	"Or";
$lForgotPassword["PasswordChanged"] = 	"Your password has been successfully changed.";
$lForgotPassword["Submit"]			=	"Send";
$lForgotPassword["Username"]		=	"Username";

/* General */
$lGeneral["Administrator"]			= 	"Administrator";
$lGeneral["ControlPanel"]			= 	"Control Panel";
$lGeneral["Draft"]					= 	"Draft";
$lGeneral["Login"]					= 	"Login";
$lGeneral["Logout"]					= 	"Logout";
$lGeneral["MainPage"]				= 	"Main";
$lGeneral["Pages"]					= 	"Pages";
$lGeneral["Search"]					= 	"Search";

/* Letters */
$lLetters["Everything"] = "Everything";
$lLetters["Misc"] = "Misc";
$lLetters["News"] = "New";
$lLetters["Popular"] = "Popular";	

/* Log */
$lLog["CreatedBy"]					= 	"Created by";
$lLog["LastUpdatedBy"]				= 	"Last updated by";
$lLog["UnknownUser"]				= 	"Unknown";

/* Login */
$lLogin["Header"]					= 	"Login";
$lLogin["Error"]					= 	"You are not able to login. Please shutdown your browser and try again.";
$lLogin["Username"]					= 	"Username";
$lLogin["Password"]					= 	"Password";
$lLogin["RememberMe"]				= 	"Remember me";
$lLogin["Send"]						= 	"Send";
$lLogin["ForgotPassword"]			= 	"Forgot Password?";
$lLogin["InvalidData"]				= 	"Enter a valid username and password.";
$lLogin["SessionTimedOut"]			= 	"Your login session has timed out. Login below save the data you just sent.";

/* Months */
$lMonths["January"]					= 	"January";
$lMonths["February"]				= 	"February";
$lMonths["March"]					= 	"March";
$lMonths["April"]					= 	"April";
$lMonths["May"]						= 	"May";
$lMonths["June"]					= 	"June";
$lMonths["July"]					= 	"July";
$lMonths["August"]					= 	"August";
$lMonths["September"]				= 	"September";
$lMonths["October"]					= 	"October";
$lMonths["November"]				= 	"November";
$lMonths["December"]				= 	"December";

/* Page */
$lPage["Header"]					=	"Pages";
$lPage["CreateSubsection"]			=	"Create subsection";
$lPage["EditPage"]					= 	"Edit page";
$lPage["UnderConstruction"]			= 	"Under construction";
$lPage["Subpages"]					= 	"Subpages";
$lPage["EditPermissions"]			= 	"Edit permissions";
$lPage["LastModified"]				= 	"Last modified %s.";

/* Profile */
$lProfile["Name"]					= 	"Name";
$lProfile["Department"]				= 	"Department";
$lProfile["Email"]					= 	"E-mail";
$lProfile["Location"]				= 	"Location";
$lProfile["Occupation"]				= 	"Occupation";
$lProfile["Phone"]					= 	"Telephone";
$lProfile["Profile"]				= 	"Profile";
$lProfile["Website"]				= 	"Website";

/* Register */
$lRegister["Header"]				= 	"Register";
$lRegister["HeaderText"]			= 	"On this page you can create a profile on ".pageTitle.".";
$lRegister["Success"]				= 	"Registration Succeeded";
$lRegister["SuccessText"]			= 	"Your registration on '".pageTitle."' succeeded. You choose the username '%s'.<br /><br />".
										"We have sendt an e-mail to your e-mail address with an activation link you must click on before you can log in with your profile..<br /><br />".	
										"Greetings<br />".pageTitle;

/* Search */
$lSearch["Header"]					= 	"Search";
$lSearch["HeaderText"]				= 	"On this page you can view results of the search for <i>'%s'</i>.";
$lSearch["AllSearchTypes"]			= 	"All search types";
$lSearch["DisplayingResults"]		= 	"Displaying %s-%s of %s result(s).";
$lSearch["MakeInvisible"]			=	"Hide the search page in the index.";
$lSearch["MakeVisible"]				=	"Show the search page in the index.";
$lSearch["MoveDown"]				=	"Move search page down.";
$lSearch["MoveUp"]					=	"Move search page up.";
$lSearch["NoSearchResult"]			= 	"No search results.";
$lSearch["ViewAllResults"]			= 	"Click <a href=\"%s\">here</a> to show all results.";

/* Section */
$lSection["Collapse"]				=	"Kollaps sektion";
$lSection["Expand"]					=	"Udvid sektion";

/* Send mail */
$lSendMail["Header"]				= 	"Contact";
$lSendMail["HeaderText"]			=	"Below you can contact the owner of the site.";
$lSendMail["InvalidMail"]			=	"Please enter a valid e-mail address.";
$lSendMail["Mail"]					=	"Your mail";
$lSendMail["MailSent"]				=	"Your mail has been sent.";
$lSendMail["Message"]				=	"Message";
$lSendMail["MissingMail"]			=	"Please enter your e-mail.";
$lSendMail["MissingName"]			=	"Please enter your name.";
$lSendMail["MissingSubject"]		=	"Please enter a subject for the e-mail.";
$lSendMail["Name"]					=	"Your name";
$lSendMail["SendMail"]				=	"Send mail";
$lSendMail["Subject"]				=	"Subject";

/* Send to friend */
$lSendToFriend["Header"]			= 	"Send to a friend";
$lSendToFriend["HeaderText"]		= 	"You can send a link to a friend by clicking the \"Send\" button below. You can also write a message.";
$lSendToFriend["DefaultMessage"]	= 	"Hi,\n\nI have found the following link I thought you should take a look at:\n\n".
										"Title:\n%s\n\n".
										"Summary:\n%s\n\n".
										"Link:\n%s\n";
$lSendToFriend["DeliveryFailed"]	=	"The message could not be sent. Please try again.";
$lSendToFriend["FriendMail"]		= 	"Send to";
$lSendToFriend["InvalidFriendMail"]	= 	"Please enter a valid e-mail address for your friend.";
$lSendToFriend["InvalidMail"]		=	"Please enter a valid e-mail address for yourself.";
$lSendToFriend["MailMessage"]		=	"Hi,\n\nA user of ".pageTitle." has sent you a link.\n\nMessage:\n%s\n\nLink:\n%s\n\nGreetings\n".pageTitle;
$lSendToFriend["MailSent"]			=	"A link has been sent to your friend.";
$lSendToFriend["MailSubject"]		=	"Link from ".pageTitle;
$lSendToFriend["Message"]			=	"Message to receiver";
$lSendToFriend["MissingName"]		=	"Please enter your name.";
$lSendToFriend["MissingLink"]		= 	"No link to send.";
$lSendToFriend["MissingMail"]		= 	"Please enter your e-mail.";
$lSendToFriend["MissingFriendMail"]	=	"Please enter your friend's e-mail.";
$lSendToFriend["NoSummary"]			=	"No summary.";
$lSendToFriend["NoTitle"]			=	"No title";
$lSendToFriend["SendLink"]			=	"Send link";
$lSendToFriend["YourName"]			=	"Your name";
$lSendToFriend["YourMail"]			=	"Your e-mail";

/* Sitemap */
$lSitemap["Header"]					= 	"Sitemap";
$lSitemap["HeaderText"]				= 	"In this page you can view the pages on the webpage.";
$lSitemap["NoPages"]				= 	"There are no pages on this webpage.";

/* Userlist */
$lUserlist["Header"]				= 	"Users";
$lUserlist["HeaderText"]			= 	"In this page you can view the users of the system.";
$lUserlist["NoUsers"]				= 	"There are no pages on this webpage.";

/* Include overrides from theme */
if (!defined("installing")) {
	if (file_exists(layoutLanguagePath.'/'.pageLanguage.'/general.php')) {
		include layoutLanguagePath.'/'.pageLanguage.'/general.php';
	}
}
?>