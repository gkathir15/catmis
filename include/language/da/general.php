<?
/* 
 * Default timestamp format 
 * This uses the PHP date() function to create the formatting.
 * Please read the PHP documentation before changing this, located at http://www.php.net
*/
$timeFormat 						= 	"d/m/y H:i";
$shortTimeFormat 					= 	"d/m/Y";

/* Activate */
$lActivate["Header"]				= 	"Profilaktivering";
$lActivate["HeaderText"]			= 	"Din profil er nu aktiveret og du kan logge ind på siden.";
$lActivate["HeaderTextError"]		= 	"Din profil kunne ikke aktiveres. Kontakt sidens ".protectMail(pageAdminMail,"webmaster").".<br /><br />Mvh.<br />".pageTitle;
$lActivate["MailSubject"]			= 	"Profil aktiveret på ".pageTitle;
$lActivate["MailMessage"]			= 	"Hej %s,\n\n" .
										"Du modtager denne mail som en bekræftelse på, at du har aktiveret din profil på ".pageTitle.".\n\n" .
										"Du kan nu logge ind på ".pageTitle." med brugernavn '%s'.\n\n\n" .
										"Mvh.\n" .
										pageTitle;

/* Buttom links */
$lBottom["DirectLink"]				= 	"Direkte link";
$lBottom["Edit"]					= 	"Rediger";
$lBottom["EditPermissions"]			= 	"Rediger rettigheder";
$lBottom["PrinterFriendly"]			= 	"Printervenlig version";
$lBottom["RecommendLink"]			= 	"Anbefal til ven";
$lBottom["Revisions"]				= 	"Revisioner";
$lBottom["RSSFeed"]					= 	"RSS feed";

/* Comments */
$lComment["Header"]					= 	"Kommentarer";
$lComment["WriteComment"]			= 	"Send kommentar";
$lComment["SendComment"]			= 	"Send kommentar";
$lComment["Comment"]				= 	"kommentar";
$lComment["Comments"]				= 	"kommentarer";
$lComment["NoComments"]				= 	"Der er endnu ingen kommentarer.";
$lComment["CommentTextLogged"]		= 	"Nedenfor kan du læse kommentarer til '%s'. Klik <a href=\"#post\">her</a> for at skrive en ny kommentar.";
$lComment["CommentTextNotLogged"]	= 	"Du skal være logget ind for at skrive kommentarer.";
$lComment["HideComments"]			= 	"Skjul kommentarer";
$lComment["ShowComments"]			= 	"Vis kommentarer";
$lComment["ValidationText"]			= 	"Af sikkerhedsmæssige årsager bedes du indtaste nedenstående talrække på billedet i tekstfeltet.";
$lComment["WrongValidation"]		= 	"Du har indtastet en forkert valideringskode. Indtast talrækken i billedet og prøv igen.";
$lComment["EditComment"]			= 	"Rediger kommentar";
$lComment["Mail"]					= 	"E-mail";
$lComment["Name"]					= 	"Navn";
$lComment["Subject"]				= 	"Emne";
$lComment["Message"]				= 	"Besked";
$lComment["RememberMe"]				= 	"Husk mig";
$lComment["Reply"]					= 	"Sv";
$lComment["PostText"]				= 	"Nedenfor kan du poste en kommentar til '%s'.";

/* Content types */
$lContentType["Category"]			= 	"Kategori";
$lContentType["Comment"]			= 	"Kommentar";
$lContentType["Page"]				= 	"Side";
$lContentType["User"]				= 	"Bruger";

/* Days */
$lDays["Monday"]					= 	"Mandag";
$lDays["Tuesday"]					= 	"Tirsdag";
$lDays["Wednesday"]					= 	"Onsdag";
$lDays["Thursday"]					= 	"Torsdag";
$lDays["Friday"]					= 	"Fredag";
$lDays["Saturday"]					= 	"Lørdag";
$lDays["Sunday"]					= 	"Søndag";

/* Errors */
$lErrors["ErrorsText"]				= 	"Følgende fejl skal rettes, før denne formular kan færdiggøres.";
$lErrors["ReUploadImages"]			= 	"Billeder valgt til upload er gået tabt. Vælg venligst billederne igen.";

/* Forgot password */
$lForgotPassword["Header"]			=	"Glemt kodeord";
$lForgotPassword["HeaderText"]		=	"På denne side kan du anmode om at skifte dit kodeord til siden, hvis du har glemt det.";
$lForgotPassword["Email"]			=	"E-mail";
$lForgotPassword["InvalidKey"]		=	"Din nøgle er ugyldig.";
$lForgotPassword["MailFailed"]		=	"Systemet var ikke i stand til at sende dig en mail.";
$lForgotPassword["MailSubject"]		=	"Glemt kodeord på ".pageTitle;
$lForgotPassword["MailMessage"]		=	"<p>Du har anmodet om at skifte dit kodeord, da du har glemt dit gamle. Klik på nedenstående link for at skifte dit kodeord:</p><p><a href=\"%s\">%s</a></p><p>Mvh.<br />".pageTitle.'</p>';
$lForgotPassword["MailSent"]		=	"En e-mail er nu blevet sendt til den e-mailadresse, du registrerede dig med. Klik venligst på linket i mailen for at fortsætte.";
$lForgotPassword["Or"]				=	"Eller";
$lForgotPassword["PasswordChanged"] = 	"Dit kodeord er nu blevet skiftet.";
$lForgotPassword["Submit"]			=	"Send";
$lForgotPassword["Username"]		=	"Brugernavn";

/* General */
$lGeneral["Administrator"]			= 	"Administrator";
$lGeneral["ControlPanel"]			= 	"Kontrolpanel";
$lGeneral["Draft"]					= 	"Kladde";
$lGeneral["Login"]					= 	"Login";
$lGeneral["Logout"]					= 	"Log ud";
$lGeneral["MainPage"]				= 	"Forside";
$lGeneral["Pages"]					= 	"Sider";
$lGeneral["Search"]					= 	"Søg";

/* Letters */
$lLetters["Everything"] = "Alle";
$lLetters["Misc"] = "Andet";
$lLetters["News"] = "Nye";
$lLetters["Popular"] = "Populære";

/* Log */
$lLog["CreatedBy"]					= 	"Oprettet af";
$lLog["LastUpdatedBy"]				= 	"Sidst opdateret af";
$lLog["UnknownUser"]				= 	"Ukendt";

/* Login */
$lLogin["Header"]					= 	"Login";
$lLogin["Error"]					= 	"Du kan desværre ikke logge ind. Luk din browser ned og prøv igen.";
$lLogin["ForgotPassword"]			= 	"Glemt kodeord?";
$lLogin["Username"]					= 	"Brugernavn";
$lLogin["Password"]					= 	"Kodeord";
$lLogin["RememberMe"]				= 	"Husk mig";
$lLogin["Send"]						= 	"Send";
$lLogin["InvalidData"]				= 	"Angiv et gyldigt brugernavn og kodeord.";
$lLogin["SessionTimedOut"]			= 	"Din login session er løbet ud. Log ind nedenfor for at gemme den data, du lige har sendt.";

/* Months */
$lMonths["January"]					= 	"Januar";
$lMonths["February"]				= 	"Februar";
$lMonths["March"]					= 	"Marts";
$lMonths["April"]					= 	"April";
$lMonths["May"]						= 	"Maj";
$lMonths["June"]					= 	"Juni";
$lMonths["July"]					= 	"Juli";
$lMonths["August"]					= 	"August";
$lMonths["September"]				= 	"September";
$lMonths["October"]					= 	"Oktober";
$lMonths["November"]				= 	"November";
$lMonths["December"]				= 	"December";

/* Page */
$lPage["Header"]					=	"Sektioner";
$lPage["CreateSubsection"]			=	"Opret underside";
$lPage["EditPage"]					= 	"Rediger side";
$lPage["UnderConstruction"]			= 	"Under konstruktion";
$lPage["Subpages"]					= 	"Undersektioner";
$lPage["EditPermissions"]			= 	"Rediger rettigheder";
$lPage["LastModified"]				= 	"Sidst opdateret %s.";

/* Profile */
$lProfile["Name"]					= 	"Navn";
$lProfile["Department"]				= 	"Afdeling";
$lProfile["Email"]					= 	"E-mail";
$lProfile["Location"]				= 	"Lokation";
$lProfile["Occupation"]				= 	"Beskæftigelse";
$lProfile["Phone"]					= 	"Telefon";
$lProfile["Profile"]				= 	"Profil";
$lProfile["Website"]				= 	"Hjemmeside";

/* Register */
$lRegister["Header"]				= 	"Registrer";
$lRegister["HeaderText"]			= 	"På denne side kan du registrere en profil på ".pageTitle.".";
$lRegister["Success"]				= 	"Registrering vellykket";
$lRegister["SuccessText"]			= 	"Din registrering på '".pageTitle."' er vellykket. Du valgte brugernavn '%s'.<br /><br />".
										"Vi har sendt en mail til din e-mailadresse med et aktiveringslink, du skal klikke på, før du kan logge ind på siden.<br /><br />".	
										"Mvh.<br />".pageTitle;

/* Search */
$lSearch["Header"]					= 	"Søg";
$lSearch["HeaderText"]				= 	"På denne side kan du resultater af søgningen efter <i>'%s'</i>.";
$lSearch["AllSearchTypes"]			= 	"Alle søgetyper";
$lSearch["DisplayingResults"]		= 	"Viser %s-%s af %s resultat(er).";
$lSearch["MakeInvisible"]			=	"Skjul søgesektionen fra oversigten.";
$lSearch["MakeVisible"]				=	"Vis søgesektionen i oversigten.";
$lSearch["MoveDown"]				=	"Flyt søgesektionen ned.";
$lSearch["MoveUp"]					=	"Flyt søgesektionen op.";
$lSearch["NoSearchResult"]			= 	"Ingen søgeresultater.";
$lSearch["ViewAllResults"]			= 	"Klik <a href=\"%s\">her</a> for at vise alle resultater.";

/* Section */
$lSection["Collapse"]				=	"Collapse section";
$lSection["Expand"]					=	"Expand section";

/* Send mail */
$lSendMail["Header"]				= 	"Kontakt";
$lSendMail["HeaderText"]			=	"Nedenfor kan du kontakte sidens ejer.";
$lSendMail["InvalidMail"]			=	"Indtast venligst en gyldig e-mailadresse.";
$lSendMail["Mail"]					=	"Din mail";
$lSendMail["MailSent"]				=	"Din mail er blevet afsendt.";
$lSendMail["Message"]				=	"Besked";
$lSendMail["MissingMail"]			=	"Indtast venligst din e-mail.";
$lSendMail["MissingName"]			=	"Indtast venligst dit navn.";
$lSendMail["MissingSubject"]		=	"Indtast venligst et emne for mailen.";
$lSendMail["Name"]					=	"Dit navn";
$lSendMail["SendMail"]				=	"Send mail";
$lSendMail["Subject"]				=	"Emne";

/* Send to friend */
$lSendToFriend["Header"]			= 	"Send til en ven";
$lSendToFriend["HeaderText"]		= 	"Du kan sende et link til en ven ved at klikke send på knappen nedenfor. Du kan også vælge at skrive en besked.";
$lSendToFriend["DefaultMessage"]	= 	"Hej,\n\nJeg har fundet følgende interessante link, jeg synes, du skal kigge på:\n\n".
										"Titel:\n%s\n\n".
										"Resumé:\n%s\n\n".
										"Link:\n%s\n";
$lSendToFriend["DeliveryFailed"]	=	"Beskeden kunne ikke afsendes. Prøv igen.";
$lSendToFriend["FriendMail"]		= 	"Send til";
$lSendToFriend["InvalidFriendMail"]	= 	"Indtast venligst en korrekt e-mail for din ven.";
$lSendToFriend["InvalidMail"]		=	"Indtast venligst en korrekt e-mail for dig selv";
$lSendToFriend["MailMessage"]		=	"Hej,\n\nEn bruger på ".pageTitle." har sendt dig et link.\n\nBesked:\n%s\n\nLink:\n%s\n\nMed venlig hilsen\n".pageTitle;
$lSendToFriend["MailSent"]			=	"Der er nu afsendt et link til din ven.";
$lSendToFriend["MailSubject"]		=	"Link fra ".pageTitle;
$lSendToFriend["Message"]			=	"Besked til modtageren";
$lSendToFriend["MissingName"]		=	"Indtast venligst dit navn.";
$lSendToFriend["MissingLink"]		= 	"Der er ikke noget link at sende.";
$lSendToFriend["MissingMail"]		= 	"Indtast venligst din e-mail.";
$lSendToFriend["MissingFriendMail"]	=	"Indtast venligst din vens e-mail.";
$lSendToFriend["NoSummary"]			=	"Intet resumé.";
$lSendToFriend["NoTitle"]			=	"Ingen titel";
$lSendToFriend["SendLink"]			=	"Send link";
$lSendToFriend["YourName"]			=	"Dit navn";
$lSendToFriend["YourMail"]			=	"Din e-mail";

/* Sitemap */
$lSitemap["Header"]					= 	"Sidestruktur";
$lSitemap["HeaderText"]				= 	"På denne side kan du få overblik over sektionerne på denne hjemmeside.";
$lSitemap["NoPages"]				= 	"Der er ikke oprettet nogen sektioner på denne hjemmeside.";

/* Userlist */
$lUserlist["Header"]				= 	"Brugere";
$lUserlist["HeaderText"]			= 	"På denne side kan du se brugerne på denne hjemmeside.";
$lUserlist["NoUsers"]				= 	"Der er ikke oprettet nogen brugere på denne hjemmeside.";

/* Include overrides from theme */
if (!defined("installing")) {
	if (file_exists(layoutLanguagePath.'/'.pageLanguage.'/general.php')) {
		include layoutLanguagePath.'/'.pageLanguage.'/general.php';
	}
}	
?>