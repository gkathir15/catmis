<?
/* Admin index */
$lAdminIndex["Header"]					= 	"Kontrolpanel";
$lAdminIndex["HeaderText"]				= 	"På disse sider kan du administrere hjemmesiden.";
$lAdminIndex["Administrator"]			= 	"Administrator";
$lAdminIndex["Categories"]				= 	"Kategorier";
$lAdminIndex["CategoriesText"]			= 	"I denne sektion kan du administrere kategorier.";
$lAdminIndex["ChangePassword"]			= 	"Skift kodeord";
$lAdminIndex["ChangePasswordText"]		= 	"I denne sektion kan du skifte dit kodeord.";
$lAdminIndex["Comments"]				= 	"Kommentarer";
$lAdminIndex["CommentsText"]			= 	"I denne sektion kan du administrere kommentarer.";
$lAdminIndex["Content"]					= 	"Indhold";
$lAdminIndex["General"]					= 	"Generelt";
$lAdminIndex["Groups"]					= 	"Grupper";
$lAdminIndex["GroupsText"]				= 	"I denne sektion kan du administrere grupper.";
$lAdminIndex["Log"]						= 	"Log";
$lAdminIndex["LogText"]					= 	"I denne sektion kan du se en log over transaktioner.";
$lAdminIndex["Logout"]					= 	"Log ud";
$lAdminIndex["LogoutText"]				= 	"Klik her for at logge ud af systemet.";
$lAdminIndex["MyProfile"]				= 	"Min profil";
$lAdminIndex["MyProfileEdit"]			= 	"Rediger profil";
$lAdminIndex["MyProfileEditText"]		= 	"I denne sektion kan du redigere din profil.";
$lAdminIndex["MyProfileText"]			= 	"På disse sider kan du redigere din profil og skifte dit kodeord.";
$lAdminIndex["PageSettings"]			= 	"Indstillinger";
$lAdminIndex["PageSettingsText"]		= 	"I denne sektion kan du administrere generelle indstillinger.";
$lAdminIndex["Pages"]					= 	"Sider";
$lAdminIndex["PagesText"]				= 	"I denne sektion kan du administrere sider.";
$lAdminIndex["SharedFiles"]				= 	"Filer";
$lAdminIndex["SharedFilesText"]			= 	"I denne sektion kan du administrere filer.";
$lAdminIndex["Users"]					= 	"Brugere";
$lAdminIndex["UsersText"]				= 	"I denne sektion kan du administrere brugere.";

/* Buttons */
$lButtons["Delete"]						= 	"Slet";
$lButtons["DeleteAll"]					= 	"Slet alle";
$lButtons["Hide"]						= 	"Skjul";
$lButtons["MarkSpam"]					= 	"Marker som spam";
$lButtons["MarkNotSpam"]				= 	"Marker som ikke-spam";
$lButtons["NotSpam"]					= 	"Ikke spam";
$lButtons["SelectAll"]					= 	"Vælg alle";
$lButtons["SelectNone"]					= 	"Vælg ingen";
$lButtons["Show"]						= 	"Vis";
$lButtons["Spam"]						= 	"Spam";

/* Category */
$lCategory["Header"]					=	"Kategori";
$lCategory["HeaderText"]				=	"På denne side kan du se indhold, der referer til kategorien \"%s\".";
$lCategory["ConfirmDelete"]				=	"Er du sikker på, at du vil slette de markerede referencer til kategorien?";
$lCategory["Title"]						=	"Titel";
$lCategory["Type"]						=	"Type";
$lCategory["NoReferences"]				=	"Der er ikke nogen referencer til denne kategori.";

/* Category edit */
$lCategoryEdit["EditCategory"]			= 	"Rediger kategori";
$lCategoryEdit["EditCategoryText"]		= 	"På denne side kan du redigere kategorien \"%s\".";
$lCategoryEdit["NewCategory"]			= 	"Ny kategori";
$lCategoryEdit["NewCategoryText"] 		= 	"På denne side kan du oprette en ny kategori.";
$lCategoryEdit["CategoryExists"]		= 	"En kategori med det indtastede navn eksisterer allerede.";
$lCategoryEdit["CategoryTitle"]			= 	"Titel";
$lCategoryEdit["ConfirmDelete"]			=	"Er du sikker på, at du vil slette denne kategori? Alle referencer fra indhold til denne kategori vil blive slettet.";
$lCategoryEdit["DeleteCategory"]		= 	"Slet kategori";
$lCategoryEdit["Description"]			= 	"Beskrivelse";
$lCategoryEdit["MissingTitle"]			= 	"Indtast venligst en titel.";
$lCategoryEdit["SaveCategory"]			= 	"Gem kategori";

/* Category index */
$lCategoryIndex["Header"]				= 	"Kategorier";
$lCategoryIndex["HeaderText"]			= 	"På denne side kan du se kategorier på hjemmesiden. Klik <a href=\"".scriptUrl."/".folderCategory."/".fileCategoryEdit."\">her</a> for at oprette en ny kategori.";
$lCategoryIndex["ConfirmDelete"]		= 	"Er du sikker på, at du vil slette de markerede kategorier? Alle referencer fra indhold til denne kategori vil blive slettet.";
$lCategoryIndex["Description"]			= 	"Beskrivelse";
$lCategoryIndex["NoCategories"]			= 	"Der er ikke oprettet nogen kategorier på hjemmesiden.";
$lCategoryIndex["NoDescription"]		= 	"Ingen beskrivelse";
$lCategoryIndex["References"]			= 	"Referencer";
$lCategoryIndex["Title"]				= 	"Titel";

/* Change password */
$lChangePassword["Header"]				= 	"Skift kodeord";
$lChangePassword["HeaderText"]			= 	"Her kan du skifte dit kodeord.";
$lChangePassword["HeaderText2"]			= 	"På denne side kan skifte kodeord for brugeren '%s'.";

/* Comment index */
$lCommentIndex["Header"]				= 	"Kommentarer";
//$lCommentIndex["HeaderText"]			= 	"På denne side kan du se ".(!empty($_GET["showSpam"])?"spam ":"")."kommentarer postet på siden. Klik <a href=\"%s\">her</a> for at vise kommentarer klassificeret som ". (empty($_GET["showSpam"])? "spam" : "ikke-spam").".";
$lCommentIndex["HeaderText"]			= 	"På denne side kan du se kommentarer postet på siden.";
$lCommentIndex["ConfirmDelete"]			= 	"Er du sikker på, at du vil slette de markerede kommentarer?";
$lCommentIndex["ConfirmDeleteSpam"]		= 	"Er du sikker på, at du vil slette alle kommentarer markeret som spam?";
$lCommentIndex["ConfirmDeleteTrash"]	= 	"Er du sikker på, at du vil slette alle kommentarer i skraldespanden?";
$lCommentIndex["DeletedComments"]		= 	"Slettede kommentarer";
$lCommentIndex["Mail"]					= 	"Mail";
$lCommentIndex["Message"]				= 	"Besked";
$lCommentIndex["Module"]				= 	"Modul";
$lCommentIndex["Name"]					= 	"Navn";
$lCommentIndex["NoComments"]			= 	"Der ikke blevet postet nogen kommentarer på siden.";
$lCommentIndex["Posted"]				= 	"Sendt";
$lCommentIndex["PublishedComments"]		= 	"Publicerede kommentarer";
$lCommentIndex["RememberMe"]			= 	"Husk mig";
$lCommentIndex["Spam"]					= 	"Spam";
$lCommentIndex["SpamComments"]			= 	"Spam kommentarer";
$lCommentIndex["Subject"]				= 	"Emne";

/* Edit comment */
$lEditComment["Header"]					= 	"Rediger kommentar";
$lEditComment["HeaderText"]				= 	"På denne side kan du redigere kommentaren \"%s\".";
$lEditComment["BodyText"]				= 	"Tekst";
$lEditComment["ConfirmDelete"]			= 	"Er du sikker på, at du vil slette kommentaren?";
$lEditComment["DeleteComment"]			= 	"Slet kommentar";
$lEditComment["InsufficientPermissions"]= 	"Du har ikke rettigheder til at poste kommentarer på denne side.";
$lEditComment["Link"]					= 	"Link";
$lEditComment["Mail"]					= 	"Mail";
$lEditComment["MarkAsSpam"]				= 	"Marker som spam";
$lEditComment["MissingName"]			= 	"Indtast venligst dit navn.";
$lEditComment["MissingSubject"]			= 	"Indtast venligst et emne.";
$lEditComment["MissingText"]			= 	"Indtast venligst en tekst.";
$lEditComment["Name"]					= 	"Navn";
$lEditComment["NoSubject"]				= 	"[Intet emne]";
$lEditComment["SaveComment"]			= 	"Gem kommentar";
$lEditComment["Subject"]				= 	"Emne";
$lEditComment["WrongValidation"]		= 	"Du har indtastet en forkert valideringskode.";

/* Edit group */
$lEditGroup["EditGroup"]				= 	"Rediger gruppe";
$lEditGroup["EditGroupText"]			= 	"På denne side kan du redigere gruppen \"%s\".";
$lEditGroup["NewGroup"]					= 	"Ny gruppe";
$lEditGroup["NewGroupText"]				= 	"På denne side kan du oprette en ny gruppe.";
$lEditGroup["ConfirmDelete"]			= 	"Er du sikker på, at du vil slette gruppen?";
$lEditGroup["Delete"]					= 	"Slet gruppe";
$lEditGroup["DeleteText"]				= 	"I denne sektion kan du vælge at slette gruppen.";
$lEditGroup["Description"]				= 	"Beskrivelse";
$lEditGroup["GroupExists"]				= 	"Der eksisterer allerede en gruppe med dette navn.";
$lEditGroup["MissingName"]				= 	"Indtast venligst et gruppenavn.";
$lEditGroup["Name"]						= 	"Gruppenavn";
$lEditGroup["Permissions"]				=	"Rettigheder";
$lEditGroup["PermissionsText"]			=	"I denne sektion kan du angive hvilke rettigheder denne gruppe har.";
$lEditGroup["Save"]						= 	"Gem gruppe";

/* Edit page */
$lEditPage["EditPage"]					=	"Rediger side";
$lEditPage["EditPageText"]				= 	"På denne side kan du redigere siden \"%s\".";
$lEditPage["NewPage"]					= 	"Ny side";
$lEditPage["NewPageText"]				= 	"På denne side kan du oprette en ny side.";
$lEditPage["BodyText"]					= 	"Tekst";
$lEditPage["Content"]					= 	"Indhold";
$lEditPage["ContentText"]				= 	"I denne sektion kan du redigere sidens titel og brødtekst.";
$lEditPage["ConfirmDelete"]				= 	"Er du sikker på, at du vil slette denne side og alle dens undersider?";
$lEditPage["Delete"] 					= 	"Slet side";
$lEditPage["DeleteText"]				= 	"I denne sektion kan du vælge at slette siden.";
$lEditPage["DisableComments"] 			= 	"Deaktiver kommentarer";
$lEditPage["Link"] 						= 	"Eksternt link";
$lEditPage["Options"] 					= 	"Indstillinger";
$lEditPage["OptionsText"]				= 	"I denne sektion kan du ændre indstillinger for siden.";
$lEditPage["Save"] 						= 	"Gem side";
$lEditPage["PageModified"]				= 	"Siden er blevet ændret siden af en anden bruger, og du er nu ved at overskrive disse ændringer.";
$lEditPage["PageModifiedWarning"]		= 	"Siden er blevet ændret siden af en anden bruger. Er du sikker på, du vil overskrive ændringerne?";
$lEditPage["Separator"]					= 	"Tilføj separator efter dette punkt i menuen.";
$lEditPage["ShowComments"] 				= 	"Vis kommentarer";
$lEditPage["ShowInNavbar"]				=	"Vis side i navigationsbaren";
$lEditPage["ShowLastModified"]			= 	"Vis hvornår siden sidst er opdateret i bunden af siden.";
$lEditPage["SubpageOf"] 				= 	"Underside af";
$lEditPage["Title"]						= 	"Titel";
$lEditPage["TitleMissing"]				= 	"Indtast venligst en titel.";

/* Edit page bar */
$lEditPageBar["EditPageBar"]			= 	"Rediger sidebar";
$lEditPageBar["EditPageBarText"]		= 	"På denne side kan du redigere sidebarer til siden \"%s\".";
$lEditPageBar["LeftBarParent"]			= 	"Venstre skabelon";
$lEditPageBar["LeftText"]				= 	"Venstre bar";
$lEditPageBar["RightBarParent"]			= 	"Højre skabelon";
$lEditPageBar["RightText"]				= 	"Højre bar";
$lEditPageBar["Save"]					= 	"Gem sidebarer";

/* Edit permissions */
$lEditPermissions["Header"]				= 	"Rediger rettigheder";
$lEditPermissions["HeaderText"]			= 	"På denne side kan du indstille rettigheder for '%s'.";
$lEditPermissions["AllTypes"]			= 	"Alle typer";
$lEditPermissions["General"]			=	"Generelt";
$lEditPermissions["Groups"]				=	"Grupper";
$lEditPermissions["Level0"]				= 	"Ingen rettigheder";
$lEditPermissions["Level1"]				= 	"Læs";
$lEditPermissions["Level2"]				= 	"Læs, kommenter";
$lEditPermissions["Level3"]				= 	"Læs, kommenter, rediger";
$lEditPermissions["Level4"]				= 	"Læs, kommenter, rediger, publicer";
$lEditPermissions["Level5"]				= 	"Læs, kommenter, rediger, publicer, slet";
$lEditPermissions["Level6"]				= 	"Læs, kommenter, rediger alle, publicer, slet alle";
$lEditPermissions["Level7"]				= 	"Administrator";
$lEditPermissions["Name"]				= 	"Navn";
$lEditPermissions["NumberOfUsers"]		= 	"Brugere";
$lEditPermissions["Permissions"]		= 	"Rettigheder";
$lEditPermissions["RegisteredUsers"]	= 	"Registrerede brugere";
$lEditPermissions["SavePermissions"]	= 	"Gem rettigheder";
$lEditPermissions["Username"]			= 	"Brugernavn";
$lEditPermissions["Users"]				=	"Brugere";
$lEditPermissions["Visitors"]			= 	"Gæster";

/* Edit user profile */
$lEditProfile["Header"]					=	"Rediger profil";
$lEditProfile["HeaderText"]				=	"På denne side kan du redigere din profil.";
$lEditProfile["LinksText"]				= 	"I denne sektion kan du angive link til din hjemmeside, Facebook eller Twitter profil.";
$lEditProfile["NotifyAboutChanges"]		= 	"Giv mig besked på e-mail, når folk svarer i en diskussion, jeg selv er med i.";
$lEditProfile["OptionsText"]			= 	"I denne sektion kan du ændre diverse indstillinger for din profil.";
$lEditProfile["PasswordText"]			=	"I denne sektion skal du sætte et kodeord til din profil.";
$lEditProfile["ProfileText"]			= 	"I denne sektion kan du uploade et billede af dig selv samt knytte en tekst og en signatur til din profil.";
$lEditProfile["Save"]					=	"Gem profil";

/* Edit user */
$lEditUser["EditUser"]					= 	"Rediger bruger";
$lEditUser["EditUserText"]				= 	"På denne side kan du redigere brugeren \"%s\". Klik <a href=\"%s\">her</a> for at skifte brugerens kodeord.";
$lEditUser["EditUserProfile"]			= 	"Min profil";
$lEditUser["EditUserProfileText"]		= 	"På denne side kan du opdatere din profil for \"%s\".";
$lEditUser["NewUser"]					= 	"Ny bruger";
$lEditUser["NewUserText"]				= 	"På denne side kan du oprette en ny bruger.";
$lEditUser["BlockUser"]					= 	"Bloker bruger";
$lEditUser["ConfirmDelete"]				= 	"Er du sikker på, at du vil slette denne bruger?";
$lEditUser["Delete"]					= 	"Slet bruger";
$lEditUser["DeleteText"]				= 	"I denne sektion kan du vælge at slette brugeren.";
$lEditUser["Department"]				= 	"Afdeling";
$lEditUser["Details"]					= 	"Detaljer";
$lEditUser["DetailsText"]				= 	"I denne sektion kan du indtaste flere detaljer om brugeren.";
$lEditUser["DifferentPasswords"]		= 	"Kodeord og gentaget kodeord er ikke ens.";
$lEditUser["Email"]						= 	"Email";
$lEditUser["EmailExists"]				= 	"Den indtastede email er registreret med en anden bruger.";
$lEditUser["ExistingPassword"]			= 	"Nuværende kodeord";
$lEditUser["FullName"]					= 	"Fuldt navn";
$lEditUser["Groups"]					= 	"Grupper";
$lEditUser["GroupsText"]				= 	"I denne sektion kan du angive hvilke grupper brugeren er en del af.";
$lEditUser["HideEmail"]					= 	"Skjul mailadresse";
$lEditUser["HideInUserlist"]			= 	"Skjul i brugerliste";
$lEditUser["HideOnlineStatus"]			= 	"Skjul online status";
$lEditUser["HideTelephoneNumber"]   	=	"Skjul telefonnumre"; 
$lEditUser["InvalidEmail"]				= 	"Indtast venligst en gyldig email adresse.";
$lEditUser["Link"]						= 	"Hjemmeside";
$lEditUser["Linkname"]					= 	"Hjemmesidenavn";
$lEditUser["Links"]						= 	"Links";
$lEditUser["LinksText"]					= 	"I denne sektion kan du angive link til brugerens hjemmeside, Facebook eller Twitter profil.";
$lEditUser["Location"]					= 	"Lokation";
$lEditUser["MissingEmail"]				= 	"Indtast venligst en email adresse.";
$lEditUser["MissingFullName"]			= 	"Indtast venligst et navn.";
$lEditUser["MissingOccupation"]			= 	"Indtast venligst din beskæftigelse.";
$lEditUser["MissingUsername"]			= 	"Indtast venligst et brugernavn.";
$lEditUser["MissingPassword"]			= 	"Indtast venligst et kodeord.";
$lEditUser["MissingOldPassword"]		= 	"Indtast venligst dit eksisterende kodeord.";
$lEditUser["MissingRepeatedPassword"]	= 	"Indtast venligst det gentagede kodeord.";
$lEditUser["MissingValidation"]			= 	"Indtast venligist valideringskoden.";
$lEditUser["Mobile"]					= 	"Mobil";
$lEditUser["NewPassword"]				= 	"Nyt kodeord";
$lEditUser["NoGroups"]					= 	"Ingen grupper oprettet.";
$lEditUser["NotifyAboutChanges"]		= 	"Giv brugeren besked på e-mail, når folk svarer i en diskussion, denne selv er med i.";
$lEditUser["Occupation"]				= 	"Beskæftigelse";
$lEditUser["Options"]					= 	"Indstillinger";
$lEditUser["OptionsText"]				= 	"I denne sektion kan du ændre indstillinger for brugeren.";
$lEditUser["Password"]					= 	"Kodeord";
$lEditUser["PasswordText"]				= 	"I denne sektion kan du sætte et kodeord for brugeren.";
$lEditUser["Permissions"]				= 	"Rettigheder";
$lEditUser["PermissionsText"]			= 	"I denne sektion kan du angive hvilke rettigheder brugeren har.";
$lEditUser["Profile"]					= 	"Profil";
$lEditUser["ProfileBody"]				= 	"Profil";
$lEditUser["ProfileText"]				= 	"I denne sektion kan du uploade et billede samt indtaste en profil og en signatur for brugeren.";
$lEditUser["ProfileUpdated"]			= 	"Din profil er nu blevet opdateret i systemet.";
$lEditUser["ReenterPasswords"]			= 	"Indtast venligst kodeord igen af sikkerhedsmæssige årsager.";
$lEditUser["RepeatPassword"]			= 	"Gentag kodeord";
$lEditUser["RepeatNewPassword"]			= 	"Gentag nyt kodeord";
$lEditUser["Save"]						= 	"Gem bruger";
$lEditUser["Signature"]					= 	"Signatur";
$lEditUser["Telephone"]					= 	"Telefon";
$lEditUser["Username"]					= 	"Brugernavn";
$lEditUser["UserCategory"]				= 	"Brugerkategori";
$lEditUser["UserCategoryPick"]			= 	"Vælg brugerkategori";
$lEditUser["UserCategoryCreate"]		= 	"Opret brugerkategori";
$lEditUser["UserData"]					= 	"Brugerdata";
$lEditUser["UserGroup"]					= 	"Brugergruppe";
$lEditUser["UploadPortrait"]			= 	"Upload portræt";
$lEditUser["UploadPortraitText"]		= 	"Vælg billede (jpg)";
$lEditUser["UsernameExists"]			= 	"Det indtastede brugernavn eksisterer allerede.";
$lEditUser["WrongValidation"]			= 	"Du har indtastet en ugyldig valideringskode.";
$lEditUser["WelcomeEmailSubject"]		= 	"Registrering på \"%s\"";
$lEditUser["WelcomeEmailText"]			= 	"Hej %s\n\n" .
											"Du modtager denne mail som en bekræftelse på, at du er blevet oprettet som bruger på ".pageTitle.".\n\n" .
											"Følg linket for at bekræfte din tilmelding til ".pageTitle."\n\n" .
											"%s\n\n" .
											"Med venlig hilsen\n" .
											pageTitle;"";
$lEditUser["Validation"]				= 	"Validering";
$lEditUser["WrongPassword"]				= 	"Indtast venligst et gyldig kodeord for denne bruger.";

/* Browse files */
$lFileBrowseFiles["Header"]				= 	"Gennemse filer";
$lFileBrowseFiles["HeaderText"]			= 	"På denne side kan du se filer på hjemmesiden.";

/* Create folder */
$lFileCreateFolder["Header"]			= 	"Ny mappe";
$lFileCreateFolder["HeaderText"]		= 	"På denne side kan du oprette en mappe. Indtast et navn for mappen nedenfor.";
$lFileCreateFolder["FolderExists"]		=	"Der eksisterer allerede en folder med det angivne navn.";
$lFileCreateFolder["MissingName"]		= 	"Indtast venligst et navn til mappen.";
$lFileCreateFolder["MissingFolder"]		= 	"Angiv venligst en mappe mappen skal oprettes i.";
$lFileCreateFolder["MissingParentFolder"]	=	"Angiv venligst en gyldig mappe mappen skal oprettes i.";
$lFileCreateFolder["SaveFolder"]		= 	"Opret mappe";

/* Edit file */
$lFileEditFile["Header"]				= 	"Rediger fil";
$lFileEditFile["HeaderText"]			= 	"På denne side kan du redigere filen \"%s\".";
$lFileEditFile["Folder"]				= 	"Folder";
$lFileEditFile["MissingFilename"]		= 	"Indtast venligst et filnavn.";
$lFileEditFile["Name"]					= 	"Navn";
$lFileEditFile["SaveFile"]				= 	"Gem fil";

/* Edit folder */
$lFileEditFolder["Header"]				= 	"Rediger folder";
$lFileEditFolder["HeaderText"]			= 	"På denne side kan du redigere folderen \"%s\".";
$lFileEditFolder["Folder"]				= 	"Folder";
$lFileEditFolder["MissingFoldername"]	= 	"Indtast venligst et foldernavn";
$lFileEditFolder["Name"]				= 	"Navn";
$lFileEditFolder["SaveFolder"]			= 	"Gem folder";

/* File index */
$lFileIndex["Header"]					= 	"Filer";
$lFileIndex["HeaderText"]				= 	"På denne side kan du se filer uploadet til hjemmesiden.";
$lFileIndex["ConfirmDelete"]			= 	"Er du sikker på, du vil slette de valgte filer og foldere?";
$lFileIndex["Delete"]					= 	"Slet";
$lFileIndex["LastUpdated"]				= 	"Sidst opdateret";
$lFileIndex["Move"]						= 	"Flyt";
$lFileIndex["Name"]						= 	"Navn";
$lFileIndex["Path"]						= 	"Sti";
$lFileIndex["Size"]						= 	"Størrelse";

/* Upload files */
$lFileUploadFiles["Header"]				= 	"Upload filer";
$lFileUploadFiles["HeaderText"]			= 	"På denne side kan du uploade filer til hjemmesiden.";
$lFileUploadFiles["File"]				=	"Fil";
$lFileUploadFiles["FileAlreadyExists"]	=	"En fil med navnet \"%s\" eksisterer allerede i folderen.";
$lFileUploadFiles["FileTypeNotAllowed"]	=	"Filtypen for filen \"%s\" er ikke tilladt.";
$lFileUploadFiles["InvalidNumberOfFiles"]	=	"Angiv venligst et gyldigt antal filer til upload.";
$lFileUploadFiles["MaxUploadExceeded"]	=	"Det er ikke muligt at uploade mere end 50 filer per gang.";
$lFileUploadFiles["NumberOfFilesText"]	=	"Angiv hvor mange filer du vil uploade nedenfor.";
$lFileUploadFiles["UploadFiles"]		=	"Upload filer";

/* Group */
$lGroup["Header"]						=	"Gruppe";

/* Group index */
$lGroupIndex["Header"]					= 	"Grupper";
$lGroupIndex["HeaderText"]				= 	"På denne side kan du se grupper på hjemmesiden. Klik <a href=\"".fileGroupEdit."\">her</a> for at oprette en gruppe.";
$lGroupIndex["Description"]				= 	"Beskrivelse";
$lGroupIndex["EditGroup"]				= 	"Rediger gruppe";
$lGroupIndex["Name"]					= 	"Navn";
$lGroupIndex["NoGroups"]				= 	"Der er ikke oprettet nogen grupper på hjemmesiden.";
$lGroupIndex["NoResults"]				= 	"Ingen gruppe blevet fundet indeholdende \"%s\".";
$lGroupIndex["NoOfMembers"]				= 	"Medlemmer";
$lGroupIndex["ConfirmDelete"]			= 	"Er du sikker på, du vil slette de valgte grupper?";

/* Log index */
$lLogIndex["Header"]					= 	"Log";
$lLogIndex["HeaderText"]				= 	"På denne side kan du se en log over transaktioner på hjemmesiden.";
$lLogIndex["AllTypes"]					= 	"Alle typer";
$lLogIndex["ConfirmDelete"]				= 	"Er du sikker på, du vil slette de valgte indgange?";
$lLogIndex["LastModified"]				= 	"Sidst opdateret";
$lLogIndex["LastModifiedBy"]			= 	"Sidst opdateret af";
$lLogIndex["NoLogEntries"]				= 	"Ingen indgange i loggen.";
$lLogIndex["NoLogResults"]				= 	"Ingen indgange i loggen der matcher \"%s\".";
$lLogIndex["Resource"]					= 	"Resource";
$lLogIndex["Type"]						= 	"Type";

/* Page index */
$lPageIndex["Header"]					= 	"Sider";
$lPageIndex["HeaderText"]				= 	"På denne side kan du se sider på hjemmesiden. Klik <a href=\"".filePageEdit."?return=1\">her</a> for at oprette en side.";
$lPageIndex["CollapsePage"]				= 	"Skjul undersider";
$lPageIndex["ConfirmDelete"]			= 	"Er du sikker på, du vil slette de valgte sider?";
$lPageIndex["ConfirmHide"]				= 	"Er du sikker på, du vil skjule de valgte sider?";
$lPageIndex["ConfirmShow"]				= 	"Er du sikker på, du vil gøre de valgte sider synlige?";
$lPageIndex["EditPage"]					= 	"Rediger side";
$lPageIndex["ExpandPage"]				= 	"Vis undersider";
$lPageIndex["MoveUp"]					= 	"Op";
$lPageIndex["MoveUpText"]				= 	"Flyt siden op";
$lPageIndex["MoveDown"]					= 	"Ned";
$lPageIndex["MoveDownText"]				= 	"Flyt siden ned";
$lPageIndex["NoPages"]					= 	"Der er ikke oprettet nogen sider på hjemmesiden.";
$lPageIndex["Subpages"]					= 	"Undersider";
$lPageIndex["Title"]					= 	"Titel";
$lPageIndex["Visible"]					= 	"Synlighed";
$lPageIndex["VisibleText"]				=	"Sæt synligheden for denne side";

/* Revisions */
$lRevisions["Header"]					= 	"Revisioner";
$lRevisions["HeaderText"]				= 	"På denne side kan du se revisioner af '%s'";

/* Settings */
$lSettings["Header"]					= 	"Indstillinger";
$lSettings["HeaderText"]				= 	"På denne side kan du administrere generelle indstillinger for hjemmesiden.";
$lSettings["ActivateWithEmail"]			= 	"Brugere skal aktivere deres profil ved at klikke på et link i en mail.";
$lSettings["AdminEmail"]				= 	"Administrator email";
$lSettings["AllowUserRegistration"]		= 	"Brugere har tilladelse til at registrere en profil på siden.";
$lSettings["CacheSize"]					= 	"Cache størrelse i KB: ";
$lSettings["CommentModeration"]			= 	"Kommentar moderering";
$lSettings["CommentModerationText"]		= 	"I denne sektion kan du indstille, hvordan kommentarer skal modereres i systemet."; 
$lSettings["CommentRequireValidation"]	= 	"Valider kommentarer med tal-i-billede metoden";
$lSettings["DefaultPage"]				= 	"Standard side";
$lSettings["DefaultUploadFolder"]		= 	"Standard folder til uploads";
$lSettings["Description"]				= 	"Beskrivelse";
$lSettings["EnableCaching"]				= 	"Aktiver caching af sider.";
$lSettings["EnableRevisioning"]			=	"Aktiver revisioner af sider";
$lSettings["IconTheme"]					= 	"Ikontema";
$lSettings["InvalidAdminMail"]			= 	"Indtast venligst en gyldig email adresse.";
$lSettings["Keywords"]					= 	"Nøgleord";
$lSettings["MaxLinks"]					= 	"Maksimalt antal links i en kommentar";
$lSettings["MissingTitle"]				= 	"Indtast venligst en titel.";
$lSettings["MissingAdminMail"]			= 	"Indtast venligst en administrator email.";
$lSettings["Options"]					= 	"Indstillinger";
$lSettings["OptionsText"]				= 	"I denne sektion kan du justere en række forskellige indstillinger for siden.";
$lSettings["PageInformation"]			= 	"Sideinformation";
$lSettings["PageLanguage"]				= 	"Sprog";
$lSettings["Permalinks"]				= 	"Permalinks";
$lSettings["PermalinksText"]			= 	"I denne sektion kan du indstille, hvordan links skal vises. Det er muligt at indlejre sidens navn i linket, hvilket gør det pænere og mere læsbart.";
$lSettings["PermalinksById"]			= 	"Med id";
$lSettings["PermalinksByName"]			= 	"Med navn";
$lSettings["PermalinksByNameAndId"]		= 	"Med navn og id";
$lSettings["Profiles"]					= 	"Profiler";
$lSettings["ProfilesText"]				= 	"I denne sektion kan du redigere indstillinger for brugerprofiler på siden.";
$lSettings["RequireValidation"]			= 	"Brugeren skal valideres vha. tal-i-billede metoden";
$lSettings["SaveSettings"]				= 	"Gem indstillinger";
$lSettings["ShowDirectLink"]			= 	"Vis direkte links.";
$lSettings["ShowPrinterLink"]			= 	"Vis printervenlig version links.";
$lSettings["ShowRecommendLink"]			= 	"Vis anbefal til ven links.";
$lSettings["SpamWords"]					= 	"Hvis et af de ord, du indtaster nedenfor optræder i en kommentar, vil den blive markeret som spam. Adskil ord med et ','.";
$lSettings["Theme"]						= 	"Tema";
$lSettings["ThemeHeaderUrl"]			= 	"Adresse til hovedbillede";
$lSettings["ThemeWidth"]				= 	"Temabredde (i pixels)";
$lSettings["Themes"]					= 	"Temaer";
$lSettings["ThemesText"]				= 	"I denne sektion kan du vælge et tema for siden og for ikoner.";
$lSettings["Title"]						= 	"Titel";

/* User types */
$lUser["Administrator"]					= 	"Administrator";
$lUser["AllUsers"]						= 	"Alle brugere";
$lUser["Guest"]							= 	"Gæst";
$lUser["ModuleAdministrator"]			= 	"Moduladministrator";
$lUser["Webmaster"]						=	"Webmaster";

/* User index */
$lUserIndex["Header"]					= 	"Brugere";
$lUserIndex["HeaderText"]				= 	"På denne side kan du se brugere på hjemmesiden. Klik <a href=\"".fileUserEdit."\">her</a> for at oprette en bruger.";
$lUserIndex["ConfirmDelete"]			= 	"Er du sikker på, du vil slette de valgte brugere?";
$lUserIndex["EditUser"]					= 	"Rediger bruger";
$lUserIndex["FullName"]					= 	"Fuldt navn";
$lUserIndex["Groups"]					= 	"Grupper";
$lUserIndex["NoUsers"]					= 	"Der er ikke oprettet nogen brugere på hjemmesiden.";
$lUserIndex["NoUsersInGroup"]			= 	"Der er ikke nogle brugere i gruppen \"%s\".";
$lUserIndex["Registered"]				= 	"Registreret";
$lUserIndex["SortBy"]					= 	"Sorter efter";
$lUserIndex["Userlevel"]				= 	"Brugergruppe";
$lUserIndex["UserLevels"]				= 	"Brugerniveau";
$lUserIndex["Username"]					= 	"Brugernavn";

/* Include overrides from theme */
if (file_exists(layoutLanguagePath.'/'.pageLanguage.'/admin.php')) {
	include layoutLanguagePath.'/'.pageLanguage.'/admin.php';
}
?>