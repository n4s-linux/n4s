<?php
require_once("/svn/svnroot/Applications/fzf.php");
$op = exec("whoami");
$tpath = getenv("tpath");
if (!file_exists("$tpath/.importedtransactions.json"))
	$imported = array();
else
	$imported = json_decode(file_get_contents("$tpath/.importedtransactions.json"),true);
if ($tpath == "") die("bankintegration requires tpath\n");
if (!file_exists("$tpath/.banksrc")) {
	ob_start();
	system("cd ~/banktrans;ls");
	$req = ob_get_clean();
	$bn = basename($tpath);
	file_put_contents("$tpath/.banksrc",fzf($req,"Pick bank requisition to use for $bn"));
}
$banksrc = file_get_contents("$tpath/.banksrc");
if (!file_exists("$tpath/.bankstartdate")) {
	echo "Date to start importing from (YYYY-m-dd): ";
	$date = trim(fgets(STDIN));
	file_put_contents("$tpath/.bankstartdate",$date);
}
$startdate = file_get_contents("$tpath/.bankstartdate");
echo "Getting $banksrc from $startdate...\n";
ob_start();
system("cd ~/banktrans/$banksrc;ls");
$files = explode("\n",trim(ob_get_clean()));
foreach ($files as $curfile) {
	if ($curfile == "curl") continue;
	if (in_array($curfile,$imported)) continue;
	$j = json_decode(file_get_contents("/home/$op/banktrans/$banksrc/$curfile"),true);
	if ($j == null) continue;
	if (strtotime($j["bookingDate"]) < strtotime($startdate)) continue;
	$id = $j["internalTransactionId"];
	$accref = $j["AccountRef"];
	$fn = "$tpath/ba_" . $accref . "_" . "$id.trans";
	if (file_exists($fn)) continue;
	$trans = array();
	$trans["Reference"] = "CSV-" . uniqid();
	$trans["Date"] = $j["bookingDate"];
	$trans["Comment"] = "";
	$trans["Transactions"][0]["Account"] = "🏦".$accref;
	$trans["Transactions"][0]["Func"] = "";
	$trans["Transactions"][0]["Amount"] = $j["transactionAmount"]["amount"];
	if ($j["transactionAmount"]["amount"] < 0) $fk = "Fejlkonto:Uhåndterede kreditorbetalinger"; else $fk = "Fejlkonto:Uhåndterede debitorbetalinger";
	$trans["Transactions"][1]["Account"] = $fk;
	$trans["Transactions"][1]["Func"] = "";
	$trans["Transactions"][1]["Amount"] = -$j["transactionAmount"]["amount"];
	if (!isset($j["remittanceInformationUnstructured"])) {
		if (isset($j["creditorName"])) $trans["Description"] = $j["creditorName"];
		else if (isset($j["additionalInformation"])) $trans["Description"] = $j["additionalInformation"];
		else if (isset($j["remittanceInfo"])) {
			$text = "";
			foreach ($j["remittanceInfo"] as $curinfo) $text .= $curinfo . " " ;
			$trans["Description"] = $text;
		}
		else if (isset($j["Bankdata"])) {
			$text = "";
			foreach ($j["Bankdata"]["remittanceInfo"] as $curinfo) $text .= $curinfo . " " ;
			$trans["Description"] = $text;
		}
		else $trans["Description"] = "Transfer";
	}
	else
	$trans["Description"] = $j["remittanceInformationUnstructured"];
	$trans["Description"] = trim(str_replace("\n"," - ",$trans["Description"]));
	if (isset($j["creditorName"])) $trans["Bankdata"]["creditorName"] = $j["creditorName"];
	if (isset($j["creditorAccount"])) $trans["Bankdata"]["creditorAccount"] = $j["creditorAccount"];
	if (isset($j["remittanceInformationUnstructuredArray"])) $trans["Bankdata"]["remittanceInfo"] = $j["remittanceInformationUnstructuredArray"];
	$trans["History"][] = array("desc"=>"Loaded bank transaction","date"=>date("Y-m-d H:i"),"op"=>$op);
	$trans["Filename"] = basename($fn);
	file_put_contents("$fn",json_encode($trans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	array_push($imported,$curfile);
}
if (!file_exists("$tpath/.importedtransactions.json"))
	file_put_contents("$tpath/.importedtransactions.json",json_encode($imported,JSON_PRETTY_PRINT));
