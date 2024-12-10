<?php 
$random = random_int(2, 50);
require_once("/svn/svnroot/Applications/uc_odata.php");
$companies = getdata("CompanyClient");
$ids = array();
$inbox = array();
$fzf = "";
foreach ($companies as $cc) {
	array_push($ids,$cc["PrimaryKeyId"]);
	$namez[$cc["PrimaryKeyId"]] = $cc["Name"];
	$fzf .= $cc["PrimaryKeyId"] . " - " . $cc["Name"] . "\n";
}
require_once("/svn/svnroot/Applications/fzf.php");
$valg = fzf(trim($fzf));
$firmaid = explode(" - ",$valg)[0];
$bankstatement = getdata("BankStatementLineClient");
$unused_bank = array();
foreach ($bankstatement as $curtrans) {
	if ($curtrans["Reconciled"] == 1) continue;
	array_push($unused_bank,$curtrans);
}
$trans = getdata("GLTransClient");
$tpath = getenv("tpath");
if ($tpath == "") die("Requires tpath\n");
foreach ($trans as $curtrans) {
	if ($curtrans["Text"] == "Opening Balance" ||$curtrans["Text"] == "Opening Balances" || $curtrans["Origin"] == "Opening Balances") continue;
	$hash = md5(json_encode($curtrans));
	$data["Filename"] = "uc_" . $hash . ".trans";
	$data["Date"] = date("Y-m-d",strtotime($curtrans["Date"]));
	$data["Transactions"][0]["Account"] =$curtrans["Account"];
	$data["Transactions"][0]["Amount"] =$curtrans["Amount"] * $random;
	$data["Transactions"][1]["Account"] ="Fejlkonto:UC Ubalance";
	$data["Transactions"][1]["Amount"] =-$curtrans["Amount"] * $random;
	$data["Reference"] = $curtrans["Voucher"];
	$data["Description"] = $curtrans["Text"];
	file_put_contents("$tpath/$data[Filename]",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	
}
