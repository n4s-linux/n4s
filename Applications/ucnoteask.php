<!DOCTYPE html>
<?php
echo "<pre>";
require_once("/svn/svnroot/Applications/uc_odata.php");
?>
<?php
$companies = getdata("CompanyClient");
$ids = array();
$inbox = array();
global $firmaid;
$ask = array();
$scan = array();
$names = array();
foreach ($companies as $cc) {
	$firmaid= $cc["PrimaryKeyId"];
	$data = getdata("UserNotesClient","","https://odata.uniconta.com/odata");
	if (!isset($cc["Email"])) { $cc["Email"] = "olsenit+" . $firmaid . "@gmail.com";}
	foreach ($data["value"] as $ct) {
		$ask[$cc["Email"]][] = $ct;
		$names[$cc["Email"]] = $cc["CompanyName"];
		$scan[$cc["Email"]] = $cc["ScanMail"];
		$ids[$cc["Email"]] = $cc["PrimaryKeyId"];
	}
}
foreach ($ask as $mail => $entries) {
	foreach ($entries as $curentry) {
		$hash = md5(json_encode($curentry));
		if (file_exists("/var/www/notes_$mail.json")) $notefile = json_decode(file_get_contents("/var/www/notes_$mail.json"),true); else $notefile = array();
		if (!isset($notefile[$hash])) $notefile[$hash] = $curentry;
		file_put_contents("/var/www/notes_$mail.json",json_encode($notefile,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		if ($curentry["KeyStr"] != 9900) continue;
		if (file_exists("/var/www/ask_$hash")) continue;
		$text = $curentry["Text"];
		$text = preg_replace('/\bhttps?:\/\/[^\s]+/', '<a href="$0">Link</a>', $text);
		$html = "<h3>Vedr $names[$mail]</h3>Ifbm. bogføring af dit regnskab er deres opstået et spørgsmål der kræver din respons<br><br>";
		$html .= "<h4>Spørgsmål</h4><i><font color=blue>$text</font><br>Vi se frem til hurtig respons<br>";
		$html .= "<br><h3>Indsendelse</h3>";
		$html .= "Indsend venligst evt. relevante bilag hurtigst muligt til <a href=mailto:$scan[$mail]>Uniconta</a><br>";
		$html .= "Du kan også indsende bilag via din telefon med Unicontas App";
		$html .= "<ul><li><a href=https://play.google.com/store/apps/details?id=com.companyname.UniScan&pli=1>Android</a></li><li><a href=https://apps.apple.com/dk/app/uniconta-upload/id1572238584?l=da#?platform=iphone>iPhone</a></ul><br>";
		$ucid = $ids[$mail];
		$html .= "<i>I Uniconta App'en skal du ved første kørsel indtaste dit Unikke ID som er $ucid</i><br>";
		$html .= "<br><br><b>Med venlig hilsen</b><br>Olsens Revision ApS";
		mail("$mail", "Spørgsmål til dit regnskab", "$html", "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\nFrom: n4s@0lsen.com\r\nReply-To: joo@0lsen.com\r\nCc: joo@0lsen.com"); 
		touch("/var/www/ask_$hash");
	}
}
