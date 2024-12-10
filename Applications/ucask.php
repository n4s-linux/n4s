<!DOCTYPE html>
<?php
require_once("/svn/svnroot/Applications/uc_odata.php");
?>
<?php
$companies = getdata("CompanyClient");
$ids = array();
$inbox = array();
global $firmaid;
$ask = array();
echo "<pre>";
$scan = array();
$names = array();
foreach ($companies as $cc) {
	$firmaid= $cc["PrimaryKeyId"];
	$data = getdata("GLTransClient","Account eq '9900'","https://odata.uniconta.com/odata");
	if (!isset($cc["Email"])) { $cc["Email"] = "olsenit+" . $firmaid . "@gmail.com";}
	foreach ($data["value"] as $ct) {
		$ask[$cc["Email"]][] = $ct;
		$names[$cc["Email"]] = $cc["CompanyName"];
		$scan[$cc["Email"]] = $cc["ScanMail"];
	}
}
foreach ($ask as $mail => $t) {
	$count = 0;
	$i = 0;
	$html = "<table border=1><tr>";
	foreach ($t as $curt) {
		if ($i== 0) {
			$html .= "<tr>";
			foreach (array("Dato","Tekst","Beløb","Spurgt tidligere","Har bilag") as $key) {
				$html .= "<td><b>$key</b></td>\n";
			}
			$html .= "</tr>";
		}
		$i++;
		$hash = md5(json_encode($curt));
		$date = date("Y-m-d",strtotime($curt["Date"]));
		if (strtotime($date) < strtotime("2024-09-01")) continue; // skip old bilag from before we moved
		if (file_exists("/var/www/$hash")) $firstasked = date("Y-m-d",filemtime("/var/www/$hash")); else $firstasked="Nej";
		if ($curt["HasVoucher"] == 1) $harbilag = "✔"; else $harbilag = "⁒";
		$html .= "<tr>";
		$pamount = number_format($curt["Amount"],2,",",".");
		$html .= "<td>$date</td><td>$curt[Text]</td><td><p align=right>$pamount</p></td><td>$firstasked</td><td>$harbilag</td>\n";
		$count++;
		$html .= "</tr>";
		touch("/var/www/$hash");
	}
	if ($count) {
		$html.="</table>";
		$ucid = preg_replace('/\D/', '', $scan[$mail]);
		$cname = $names[$mail];
		$greeting = "<h3>Vedr $cname</h3><br>Der er poster i dit regnskab der kræver respons fra dig.<br>Poster uden bilag kræver bilag, mens poster der har bilag kræver en nærmere forklaring omkring hvad det er, og hvordan det er betalt.<br>";
		$greeting .= "<u>Hvis du allerede har indsendt et bilag og vi ikke har nået at opdatere listen, så lad venligst være med at indsende det igen.</u><br>Fejllisten udsendes automatisk ugentligt, men tager ikke hensyn til nye bilag vi ikke har nået at kontere endnu.";
		$greeting.= "<br><h3>Mangelliste:</h3>";
		$html = $greeting . $html;
		$html .= "<h3>Indsendelse</h3><br>";
		$html .= "Indsend venligst disse bilag/forklaringer hurtigst muligt til <a href=mailto:$scan[$mail]>Uniconta</a><br>";
		$html .= "Du kan også indsende bilag via din telefon med Unicontas App";
		$html .= "<ul><li><a href=https://play.google.com/store/apps/details?id=com.companyname.UniScan&pli=1>Android</a></li><li><a href=https://apps.apple.com/dk/app/uniconta-upload/id1572238584?l=da#?platform=iphone>iPhone</a></ul><br>";
		$html .= "<i>I Uniconta App'en skal du ved første kørsel indtaste dit Unikke ID som er $ucid</i><br>";
		$html .= "<br><br><b>Med venlig hilsen</b><br>Olsens Revision ApS";
		mail("$mail", "Manglende bilag/forklaringer til dit regnskab", "$html", "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\nFrom: n4s@0lsen.com\r\nReply-To: joo@0lsen.com\r\nBcc: joo@0lsen.com"); 
	}
}	
