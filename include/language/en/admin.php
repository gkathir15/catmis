<?
/* Admin index */
$lAdminIndex["Header"]					= 	"Control Panel";
$lAdminIndex["HeaderText"]				= 	"On these pages you can administer the website.";
$lAdminIndex["Administrator"]			=	"Administrator";
$lAdminIndex["Categories"]				= 	"Categories";
$lAdminIndex["CategoriesText"]			= 	"In this section you can administer categories.";
$lAdminIndex["ChangePassword"]			= 	"Change Password";
$lAdminIndex["ChangePasswordText"]		= 	"In this section you can change your password.";
$lAdminIndex["Comments"]				= 	"Comments";
$lAdminIndex["CommentsText"]			= 	"In this section you can administer comments.";
$lAdminIndex["Content"]					= 	"Content";
$lAdminIndex["General"]					= 	"General";
$lAdminIndex["Groups"]					= 	"Groups";
$lAdminIndex["GroupsText"]				= 	"In this section you can administer groups.";
$lAdminIndex["Log"]						= 	"Log";
$lAdminIndex["LogText"]					= 	"In this section you can view a log of transactions.";
$lAdminIndex["Logout"]					= 	"Logout";
$lAdminIndex["LogoutText"]				= 	"Click here to log out of the system.";
$lAdminIndex["MyProfile"]				= 	"My profile";
$lAdminIndex["MyProfileEdit"]			= 	"Edit profile";
$lAdminIndex["MyProfileEditText"]		= 	"In this section you can edit your profile.";
$lAdminIndex["MyProfileText"]			= 	"On these pages you can edit your profile and change your password.";
$lAdminIndex["PageSettings"]			= 	"Settings";
$lAdminIndex["PageSettingsText"]		= 	"In this section you can administer general settings.";
$lAdminIndex["Pages"]					= 	"Pages";
$lAdminIndex["PagesText"]				= 	"In this section you can administer pages.";
$lAdminIndex["SharedFiles"]				= 	"Files";
$lAdminIndex["SharedFilesText"]			= 	"In this section you can administer files.";
$lAdminIndex["Users"]					= 	"Users";
$lAdminIndex["UsersText"]				= 	"In this section you can administer users.";

/* Buttons */
$lButtons["Delete"]						= 	"Delete";
$lButtons["DeleteAll"]					= 	"Delete all";
$lButtons["Hide"]						= 	"Hide";
$lButtons["MarkSpam"]					= 	"Mark as spam";
$lButtons["MarkNotSpam"]				= 	"Mark as not spam";
$lButtons["NotSpam"]					= 	"Not Spam";
$lButtons["SelectAll"]					= 	"Select All";
$lButtons["SelectNone"]					= 	"Select None";
$lButtons["Show"]						= 	"Show";
$lButtons["Spam"]						= 	"Spam";

/* Category */
$lCategory["Header"]					=	"Category";
$lCategory["HeaderText"]				=	"On this page you can view content that refers to the category \"%s\".";
$lCategory["ConfirmDelete"]				=	"Are you sure you want to delete the selected references to the category?";
$lCategory["Title"]						=	"Title";
$lCategory["Type"]						=	"Type";
$lCategory["NoReferences"]				=	"There are no references to this category.";

/* Edit category */
$lCategoryEdit["EditCategory"]			= 	"Edit category";
$lCategoryEdit["EditCategoryText"]		= 	"On this page you can edit the category \"%s\".";
$lCategoryEdit["NewCategory"]			= 	"New category";
$lCategoryEdit["NewCategoryText"] 		= 	"On this page you can create a new category.";
$lCategoryEdit["CategoryExists"]		= 	"A category with the entered name already exist.";
$lCategoryEdit["CategoryTitle"]			= 	"Title";
$lCategoryEdit["ConfirmDelete"]			= 	"Are you sure you want to delete this category? All references between this category and content will be deleted.";
$lCategoryEdit["DeleteCategory"]		= 	"Delete category";
$lCategoryEdit["Description"]			= 	"Description";
$lCategoryEdit["MissingTitle"]			= 	"Please enter a title.";
$lCategoryEdit["SaveCategory"]			= 	"Save category";

/* Category index */
$lCategoryIndex["Header"]				= 	"Categories";
$lCategoryIndex["HeaderText"]			= 	"On this page you can view categories on the website. Click <a href=\"".scriptUrl."/".folderCategory."/".fileCategoryEdit."\">here</a> to create a new category.";
$lCategoryIndex["ConfirmDelete"]		= 	"Are you sure you want to delete the selected categories? All references between this category and content will be deleted.";
$lCategoryIndex["Description"]			= 	"Description";
$lCategoryIndex["NoDescription"]		= 	"No description";
$lCategoryIndex["NoCategories"]			= 	"No categories has been created on the website.";
$lCategoryIndex["References"]			= 	"References";
$lCategoryIndex["Title"]				= 	"Title";

/* Change password */
$lChangePassword["Header"]				= 	"Change password";
$lChangePassword["HeaderText"]			= 	"On this page you can change your password.";
$lChangePassword["HeaderText2"]			= 	"On this page you can change password for the user '%s'.";

/* Comment index */
$lCommentIndex["Header"]				= 	"Comments";
$lCommentIndex["HeaderText"]			= 	"On this page you can view comments posted on the website.";
//$lCommentIndex["HeaderText"]			= 	"On this page you can view ".(!empty($_GET["showSpam"])?"spam ":"")."comments posted on the website. Click <a href=\"".scriptUrl."/".folderComment."/".fileCommentIndex.(empty($_GET["showSpam"])?"?showSpam=1":"")."\">here</a> to view comments classified as ". (empty($_GET["showSpam"])? "spam" : "not spam").".";
$lCommentIndex["ConfirmDelete"]			= 	"Are you sure you want to delete the selected comments?";
$lCommentIndex["ConfirmDeleteSpam"]		= 	"Are you sure you want to delete all comments classified as spam?";
$lCommentIndex["ConfirmDeleteTrash"]	= 	"Are you sure you want to delete all comments in trash?";
$lCommentIndex["DeletedComments"]		= 	"Deleted comments";
$lCommentIndex["Mail"]					= 	"Mail";
$lCommentIndex["Message"]				= 	"Message";
$lCommentIndex["Module"]				= 	"Module";
$lCommentIndex["Name"]					= 	"Name";
$lCommentIndex["NoComments"]			= 	"No comments have been posted on the website.";
$lCommentIndex["PublishedComments"]		= 	"Published comments";
$lCommentIndex["Posted"]				= 	"Posted";
$lCommentIndex["RememberMe"]			= 	"Remember Me";
$lCommentIndex["Spam"]					= 	"Spam";
$lCommentIndex["SpamComments"]			= 	"Spam comments";
$lCommentIndex["Subject"]				= 	"Subject";

/* Edit comment */
$lEditComment["Header"]					= 	"Edit comment";
$lEditComment["HeaderText"]				= 	"On this page you can edit the comment \"%s\".";
$lEditComment["BodyText"]				= 	"Text";
$lEditComment["ConfirmDelete"]			= 	"Are you sure you want to delete the comment?";
$lEditComment["DeleteComment"]			= 	"Delete comment";
$lEditComment["InsufficientPermissions"]= 	"You don't have permission to post comments on this page.";
$lEditComment["Link"]					= 	"Link";
$lEditComment["Mail"]					= 	"Mail";
$lEditComment["MarkAsSpam"]				= 	"Mark as spam";
$lEditComment["MissingName"]			= 	"Please enter your name.";
$lEditComment["MissingSubject"]			= 	"Please enter a subject.";
$lEditComment["MissingText"]			= 	"Please enter a message text.";
$lEditComment["Name"]					= 	"Name";
$lEditComment["NoSubject"]				= 	"[No Subject]";
$lEditComment["SaveComment"]			= 	"Save comment";
$lEditComment["Subject"]				= 	"Subject";
$lEditComment["WrongValidation"]		= 	"You have entered an incorrect validation code.";

/* Edit group */
$lEditGroup["EditGroup"]				= 	"Edit group";
$lEditGroup["EditGroupText"]			= 	"On this page you can edit the group \"%s\".";
$lEditGroup["NewGroup"]					= 	"New group";
$lEditGroup["NewGroupText"]				= 	"On this page you can create a new group.";
$lEditGroup["ConfirmDelete"]			= 	"Are you sure you want to delete this group?";
$lEditGroup["Delete"]					= 	"Delete group";
$lEditGroup["DeleteText"]				= 	"In this section you can choose to delete the group.";
$lEditGroup["Description"]				= 	"Description";
$lEditGroup["GroupExists"]				= 	"A group with this name already exists.";
$lEditGroup["MissingName"]				= 	"Please enter a group name.";
$lEditGroup["Name"]						= 	"Name";
$lEditGroup["Permissions"]				=	"Permissions";
$lEditGroup["PermissionsText"]			=	"In this section you can specify permissions for the group.";
$lEditGroup["Save"]						= 	"Save group";

/* Edit page */
$lEditPage["EditPage"]					= 	"Edit page";
$lEditPage["EditPageText"]				= 	"On this page you can edit the page \"%s\".";
$lEditPage["NewPage"]					= 	"New page";
$lEditPage["NewPageText"]				= 	"On this page you create a new page.";
$lEditPage["BodyText"]					= 	"Text";
$lEditPage["Content"]					= 	"Content";
$lEditPage["ContentText"]				= 	"In this section you can edit the title and text of this page.";
$lEditPage["ConfirmDelete"]				= 	"Are you sure you want to delete this page and all its subpages?";
$lEditPage["Delete"] 					= 	"Delete page";
$lEditPage["DeleteText"]				= 	"In this section you can choose to delete the page.";
$lEditPage["DisableComments"] 			= 	"Disable comments";
$lEditPage["Link"] 						= 	"External Link";
$lEditPage["Options"] 					= 	"Options";
$lEditPage["OptionsText"]				= 	"In this section you set various options for the page.";
$lEditPage["Save"] 						= 	"Save page";
$lEditPage["PageModified"]				= 	"This page has been modified by another user.";
$lEditPage["PageModifiedWarning"]		= 	"This page has been modified by another user. Are you sure you want to overwrite these changes?";
$lEditPage["Separator"]					= 	"Add separator after this page in menu.";
$lEditPage["ShowComments"] 				= 	"Show comments";
$lEditPage["ShowInNavbar"]				= 	"Show page in navigationbar";
$lEditPage["ShowLastModified"]			= 	"Show when page was last updated in the bottom of the page.";
$lEditPage["SubpageOf"] 				= 	"Subpage of";
$lEditPage["Title"]						= 	"Title";
$lEditPage["TitleMissing"]				= 	"Please enter a title.";

/* Edit page bar */
$lEditPageBar["EditPageBar"]			= 	"Edit sidebars";
$lEditPageBar["EditPageBarText"]		= 	"On this page you can edit sidebars for the page \"%s\".";
$lEditPageBar["LeftBarParent"]			= 	"Left template";
$lEditPageBar["LeftText"]				= 	"Left Bar";
$lEditPageBar["RightBarParent"]			= 	"Right template";
$lEditPageBar["RightText"]				= 	"Right Bar";
$lEditPageBar["Save"]					= 	"Save sidebars";

/* Edit permissions */
$lEditPermissions["Header"]				= 	"Edit permissions";
$lEditPermissions["HeaderText"]			= 	"On this page you can set permissions for \"%s\".";
$lEditPermissions["AllTypes"]			= 	"All types";
$lEditPermissions["General"]			=	"General";
$lEditPermissions["Groups"]				=	"Groups";
$lEditPermissions["Level0"]				= 	"No permissions";
$lEditPermissions["Level1"]				= 	"Read";
$lEditPermissions["Level2"]				= 	"Read, comment";
$lEditPermissions["Level3"]				= 	"Read, comment, edit";
$lEditPermissions["Level4"]				= 	"Read, comment, edit, publish";
$lEditPermissions["Level5"]				= 	"Read, comment, edit, publish, delete";
$lEditPermissions["Level6"]				= 	"Read, comment, edit all, publish, delete all";
$lEditPermissions["Level7"]				= 	"Administrator";
$lEditPermissions["Name"]				= 	"Name";
$lEditPermissions["NumberOfUsers"]		= 	"Users";
$lEditPermissions["Permissions"]		= 	"Permissions";
$lEditPermissions["RegisteredUsers"]	= 	"Registered users";
$lEditPermissions["SavePermissions"]	= 	"Save Permissions";
$lEditPermissions["Username"]			= 	"Username";
$lEditPermissions["Users"]				=	"Users";
$lEditPermissions["Visitors"]			= 	"Visitors";

/* Edit user profile */
$lEditProfile["Header"]					=	"Edit profile";
$lEditProfile["HeaderText"]				=	"On this page you can edit your profile.";
$lEditProfile["LinksText"]				= 	"In this section you can enter a link to your website, Facebook eller Twitter profile.";
$lEditProfile["NotifyAboutChanges"]		= 	"Notify me by email when people reply in a discussion that I am participating in.";
$lEditProfile["ProfileText"]			= 	"In this section you can upload a picture of yourself and write a text and a signature for your profile.";
$lEditProfile["PasswordText"]			=	"In this section you must set a password for your profile.";
$lEditProfile["OptionsText"]			= 	"In this section you can change various settings for your profile.";
$lEditProfile["Save"]					=	"Save profile";

/* Edit user */
$lEditUser["EditUser"]					= 	"Edit user";
$lEditUser["EditUserText"]				= 	"On this page you can edit the user \"%s\". Click <a href=\"%s\">here</a> to change the users password.";
$lEditUser["NewUser"]					= 	"New user";
$lEditUser["NewUserText"]				= 	"On this page you can create a new user.";
$lEditUser["BlockUser"]					= 	"Block user";
$lEditUser["ConfirmDelete"]				= 	"Are you sure you want to delete the user?";
$lEditUser["Delete"]					= 	"Delete user";
$lEditUser["DeleteText"]				= 	"In this section you can choose to delete the user.";
$lEditUser["Department"]				= 	"Department";
$lEditUser["Details"]					= 	"Details";
$lEditUser["DetailsText"]				= 	"In this section you can enter further details about the user.";
$lEditUser["DifferentPasswords"]		= 	"Password and repeated password doesn't match.";
$lEditUser["Email"]						= 	"Email";
$lEditUser["EmailExists"]				= 	"The entered email address is registered with another user.";
$lEditUser["ExistingPassword"]			= 	"Existing password";
$lEditUser["FullName"]					= 	"Full name";
$lEditUser["Groups"]					= 	"Groups";
$lEditUser["GroupsText"]				= 	"In this section you can select the groups this user is part of.";
$lEditUser["InvalidEmail"]				= 	"Please enter a valid email address.";
$lEditUser["HideEmail"]					= 	"Hide email";
$lEditUser["HideInUserlist"]			= 	"Hide in userlist";
$lEditUser["HideOnlineStatus"]			= 	"Hide online status";
$lEditUser["HideTelephoneNumber"]   	=	"Hide phone numbers"; 
$lEditUser["Link"]						= 	"Website";
$lEditUser["Linkname"]					= 	"Website name";
$lEditUser["Links"]						= 	"Links";
$lEditUser["LinksText"]					= 	"In this section you can enter links to the users website, Facebook or Twitter profile.";
$lEditUser["Location"]					= 	"City";
$lEditUser["MissingEmail"]				= 	"Please enter an email address.";
$lEditUser["MissingFullName"]			= 	"Please enter a name.";
$lEditUser["MissingOccupation"]			= 	"Please enter your occupation.";
$lEditUser["MissingOldPassword"]		= 	"Please enter your existing password.";
$lEditUser["MissingPassword"]			= 	"Please enter a password.";
$lEditUser["MissingRepeatedPassword"]	= 	"Please repeat the new password.";
$lEditUser["MissingUsername"]			= 	"Please enter a username.";
$lEditUser["MissingValidation"]			= 	"Please enter the validation code.";
$lEditUser["Mobile"]					= 	"Mobile";
$lEditUser["NewPassword"]				= 	"New password";
$lEditUser["NoGroups"]					= 	"No groups created.";
$lEditUser["NotifyAboutChanges"]		= 	"Notify user by email when people reply in a discussion the user is participating in.";
$lEditUser["Occupation"]				= 	"Occupation";
$lEditUser["Options"]					= 	"Options";
$lEditUser["OptionsText"]				= 	"In this section you can change options for the user.";
$lEditUser["Password"]					= 	"Password";
$lEditUser["PasswordText"]				= 	"In this section you can set a password for the user.";
$lEditUser["Permissions"]				= 	"Permissions";
$lEditUser["PermissionsText"]			= 	"In this section you can set permissions for the user.";
$lEditUser["Profile"]					= 	"Profile";
$lEditUser["ProfileBody"]				= 	"Profile";
$lEditUser["ProfileText"]				= 	"In this section you can upload a picture and enter a profile and a signature for the user.";
$lEditUser["ProfileUpdated"]			= 	"Your profile has been updated in the system.";
$lEditUser["ReenterPasswords"]			= 	"Please enter passwords again for security reasons.";
$lEditUser["RepeatPassword"]			= 	"Repeat password";
$lEditUser["RepeatNewPassword"]			= 	"Repeat new password";
$lEditUser["Save"]						= 	"Save user";
$lEditUser["Signature"]					= 	"Signature";
$lEditUser["Telephone"]					= 	"Telephone";
$lEditUser["Username"]					= 	"Username";
$lEditUser["UsernameExists"]			= 	"The entered username already exists.";
$lEditUser["UserCategory"]				= 	"User category";
$lEditUser["UserCategoryPick"]			= 	"Choose user category";
$lEditUser["UserCategoryCreate"]		= 	"Create user category";
$lEditUser["UserData"]					= 	"User data";
$lEditUser["UserGroup"]					= 	"User group";
$lEditUser["UploadPortrait"]			= 	"Upload portrait";
$lEditUser["UploadPortraitText"]		= 	"Choose image (jpg)";
$lEditUser["Validation"]				= 	"Validation";
$lEditUser["WelcomeEmailSubject"]		= 	"Registration on \"%s\"";
$lEditUser["WelcomeEmailText"]			= 	"Hi %s\n\n" .
											"You're receiving this email as a confirmation that you been registered as a user on ".pageTitle.".\n\n" .
											"You can't login before you have activated your profile. To activate your profile click the link below.\n\n" .
											"%s\n\n" .
											"Greetings!\n" .
											pageTitle;
$lEditUser["WrongPassword"]				= 	"Please enter a valid password for the user.";
$lEditUser["WrongValidation"]			= 	"You have entered an incorrect validation code.";

/* Browse files */
$lFileBrowseFiles["Header"]				= 	"Browse files";
$lFileBrowseFiles["HeaderText"]			= 	"On this page you can view files on the website.";

/* Create folder */
$lFileCreateFolder["Header"]			= 	"Create folder";
$lFileCreateFolder["HeaderText"]		= 	"On this page you can create a folder. Please enter a name for the folder below.";
$lFileCreateFolder["FolderExists"]		=	"A folder with the given name already exists.";
$lFileCreateFolder["MissingName"]		= 	"Please enter a name for the folder.";
$lFileCreateFolder["MissingFolder"]		= 	"Please specify a parent folder for the folder.";
$lFileCreateFolder["MissingParentFolder"]	=	"Please specify a valid parent folder for the folder.";
$lFileCreateFolder["SaveFolder"]		= 	"Create folder";

/* Edit file */
$lFileEditFile["Header"]				= 	"Edit file";
$lFileEditFile["HeaderText"]			= 	"On this page you can edit the file \"%s\".";
$lFileEditFile["Folder"]				= 	"Folder";
$lFileEditFile["MissingFoldername"]		= 	"Please enter a filename.";
$lFileEditFile["Name"]					= 	"Name";
$lFileEditFile["SaveFile"]				= 	"Save file";

/* Edit folder */
$lFileEditFolder["Header"]				= 	"Edit folder";
$lFileEditFolder["HeaderText"]			= 	"On this page you can edit the folder \"%s\".";
$lFileEditFolder["Folder"]				= 	"Folder";
$lFileEditFolder["MissingFilename"]		= 	"Please enter a foldername";
$lFileEditFolder["Name"]				= 	"Name";
$lFileEditFolder["SaveFolder"]			= 	"Save folder";

/* General */
$lFileIndex["Header"]					= 	"Files";
$lFileIndex["HeaderText"]				= 	"On this page you can view the files that has been uploaded to the website.";
$lFileIndex["ConfirmDelete"]			= 	"Are you sure you want to delete the selected files and folders?";
$lFileIndex["Delete"]					= 	"Delete";
$lFileIndex["Move"]						= 	"Move";
$lFileIndex["Path"]						= 	"Path";
$lFileIndex["Name"]						= 	"Name";
$lFileIndex["LastUpdated"]				= 	"Last updated";
$lFileIndex["Size"]						= 	"Size";

/* Upload files */
$lFileUploadFiles["Header"]				= 	"Upload files";
$lFileUploadFiles["HeaderText"]			= 	"On this page you can upload files to the website.";
$lFileUploadFiles["File"]				=	"File";
$lFileUploadFiles["FileAlreadyExists"]	=	"A file with the name \"%s\" already exist in the folder.";
$lFileUploadFiles["FileTypeNotAllowed"]	=	"The file type for the file \"%s\" is not allowed.";
$lFileUploadFiles["InvalidNumberOfFiles"]	=	"Please specify a valid number of files to upload.";
$lFileUploadFiles["MaxUploadExceeded"]	=	"It is not possible to upload more than 50 files per time.";
$lFileUploadFiles["NumberOfFilesText"]	=	"Please specify the number of files you want to upload below.";
$lFileUploadFiles["UploadFiles"]		=	"Upload files";

/* Group */
$lGroup["Header"]						=	"Group";

/* Group index */
$lGroupIndex["Header"]					= 	"Groups";
$lGroupIndex["HeaderText"]				= 	"On this page you view groups on the website. Click <a href=\"".fileGroupEdit."\">here</a> to create a group.";
$lGroupIndex["EditGroup"]				= 	"Edit group";
$lGroupIndex["ConfirmDelete"]			= 	"Are you sure you want to delete the selected groups?";
$lGroupIndex["Description"]				= 	"Description";
$lGroupIndex["Name"]					= 	"Name";
$lGroupIndex["NoGroups"]				= 	"No groups have been created on the website.";
$lGroupIndex["NoOfMembers"]				= 	"Members";
$lGroupIndex["NoResults"]				= 	"No groups were found matching \"%s\".";

/* Log index */
$lLogIndex["Header"]					= 	"Log";
$lLogIndex["HeaderText"]				= 	"On this page you can view a log of changes to the website.";
$lLogIndex["AllTypes"]					= 	"All types";
$lLogIndex["ConfirmDelete"]				= 	"Are you sure you want to delete the selected entries?";
$lLogIndex["LastModified"]				= 	"Last modified";
$lLogIndex["LastModifiedBy"]			= 	"Last modified by";
$lLogIndex["NoLogEntries"]				= 	"No entries in the log.";
$lLogIndex["NoLogResults"]				= 	"No entries in the log matching \"%s\".";
$lLogIndex["Resource"]					= 	"Resource";
$lLogIndex["Type"]						= 	"Type";

/* Page index */
$lPageIndex["Header"]					= 	"Pages";
$lPageIndex["HeaderText"]				= 	"On this page you can view pages on the website. Click <a href=\"".filePageEdit."?return=1\">here</a> to create a page.";
$lPageIndex["CollapsePage"]				= 	"Collapse page";
$lPageIndex["ConfirmDelete"]			= 	"Are you sure you want to delete the selected pages?";
$lPageIndex["ConfirmHide"]				= 	"Are you sure you want to hide the selected pages?";
$lPageIndex["ConfirmShow"]				= 	"Are you sure you want to make the selected pages visible?";
$lPageIndex["EditPage"]					= 	"Edit page";
$lPageIndex["ExpandPage"]				= 	"Expand page";
$lPageIndex["MoveUp"]					= 	"Up";
$lPageIndex["MoveUpText"]				= 	"Move page up";
$lPageIndex["MoveDown"]					= 	"Down";
$lPageIndex["MoveDownText"]				= 	"Move page down";
$lPageIndex["NoPages"]					= 	"There no pages on this website.";
$lPageIndex["Subpages"]					= 	"Subpages";
$lPageIndex["Title"]					= 	"Title";
$lPageIndex["Visible"]					= 	"Visible";
$lPageIndex["VisibleText"]				= 	"Set visibility of page";

/* Revisions */
$lRevisions["Header"]					= 	"Revisions";
$lRevisions["HeaderText"]				= 	"On this page you can view revisions of '%s'";

/* Settings */
$lSettings["Header"]					= 	"Settings";
$lSettings["HeaderText"]				= 	"On this page you can administer general settings for the website.";
$lSettings["ActivateWithEmail"]			= 	"Users must activate their profiles by clicking a link in an email.";
$lSettings["AdminEmail"]				= 	"Administrator email";
$lSettings["AllowUserRegistration"]		= 	"Users are allowed to register a profile on the website.";
$lSettings["CacheSize"]					= 	"Cache size in KB: ";
$lSettings["CommentModeration"]			= 	"Comment Moderation";
$lSettings["CommentModerationText"]		= 	"In this section you can adjust how comments are moderated in the system.";
$lSettings["CommentRequireValidation"]	= 	"Validate comments using the numbers-in-image method";
$lSettings["DefaultPage"]				= 	"Default page";
$lSettings["DefaultUploadFolder"]		= 	"Default folder for file uploads";
$lSettings["Description"]				= 	"Description";
$lSettings["EnableCaching"]				= 	"Activate caching of pages.";
$lSettings["EnableRevisioning"]			=	"Activate revisioning of pages.";
$lSettings["IconTheme"]					= 	"Icon theme";
$lSettings["InvalidAdminMail"]			= 	"Please enter a valid email address.";
$lSettings["Keywords"]					= 	"Keywords";
$lSettings["MaxLinks"]					= 	"Maximum number of links allowed in a comment";
$lSettings["MissingTitle"]				= 	"Please enter a title.";
$lSettings["MissingAdminMail"]			= 	"Please enter an administrator email.";
$lSettings["Options"]					= 	"Options";
$lSettings["OptionsText"]				= 	"In this section you can adjust different settings for the website.";
$lSettings["PageInformation"]			= 	"Page information";
$lSettings["PageLanguage"]				= 	"Language";
$lSettings["Permalinks"]				= 	"Permalinks";
$lSettings["PermalinksText"]			= 	"In this section you can adjust the appearance of links. It is possible to embed the name of the page in the link which makes the link prettier and more readable.";
$lSettings["PermalinksById"]			= 	"By id";
$lSettings["PermalinksByName"]			= 	"By name";
$lSettings["PermalinksByNameAndId"]		= 	"By name and id";
$lSettings["Profiles"]					= 	"Profiles";
$lSettings["ProfilesText"]				= 	"In this section you adjust settings for user profiles on the website.";
$lSettings["RequireValidation"]			= 	"Users must be validated using the numbers-in-image method";
$lSettings["SaveSettings"]				= 	"Save settings";
$lSettings["ShowDirectLink"]			= 	"Show direct links.";
$lSettings["ShowPrinterLink"]			= 	"Show printerfriendly version links.";
$lSettings["ShowRecommendLink"]			= 	"Show recommend to friend links.";
$lSettings["SpamWords"]					= 	"If one of the words you type below occour in a comment, the comment will be marked as spam. Separate words with a ','";
$lSettings["Theme"]						= 	"Theme";
$lSettings["ThemeHeaderUrl"]			= 	"Address to header image";
$lSettings["ThemeWidth"]				= 	"Theme width (in pixels)";
$lSettings["Themes"]					= 	"Themes";
$lSettings["ThemesText"]				= 	"In this section you can choose a theme for the page and for icons.";
$lSettings["Title"]						= 	"Title";

/* User types */
$lUser["Webmaster"]						= 	"Webmaster";
$lUser["Administrator"]					= 	"Administrator";
$lUser["ModuleAdministrator"]			= 	"Module Administrator";
$lUser["Guest"]							= 	"Guest";
$lUser["AllUsers"]						= 	"All users";

/* User index */
$lUserIndex["Header"]					= 	"Users";
$lUserIndex["HeaderText"]				= 	"On this page you view users on the website. Click <a href=\"".fileUserEdit."\">here</a> to create a user.";
$lUserIndex["ConfirmDelete"]			= 	"Are you sure you want to delete the selected users?";
$lUserIndex["EditUser"]					= 	"Edit user";
$lUserIndex["FullName"]					= 	"Full name";
$lUserIndex["Groups"]					= 	"Groups";
$lUserIndex["NoUsers"]					= 	"There are no users on this webpage.";
$lUserIndex["NoUsersInGroup"]			= 	"There are no users in the group \"%s\".";
$lUserIndex["Registered"]				= 	"Registered";
$lUserIndex["SortBy"]					= 	"Sort by";
$lUserIndex["Userlevel"]				= 	"Userlevel";
$lUserIndex["UserLevels"]				= 	"Userlevel";
$lUserIndex["Username"]					= 	"Username";

/* Include overrides from theme */
if (file_exists(scriptPath.'/'.layoutLanguagePath.'/'.pageLanguage.'/admin.php')) {
	include scriptPath.'/'.layoutLanguagePath.'/'.pageLanguage.'/admin.php';
}
?>