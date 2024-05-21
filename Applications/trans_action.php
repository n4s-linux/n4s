<?php
$tpath = getenv("tpath");
$op = exec("whoami");
require_once("/svn/svnroot/Applications/fzf.php");
$data = "";
while ($line = fgets(STDIN)) {
	$data .= $line . "\n";
}
$data = trim($data);
$json = json_decode($data,true);
if ($json == null) {
	echo $data;
}
else {
	$fzf = "";
	$i = 0;
	if (!isset($json["Description"])) $json["Description"] = "";
	if (!isset($json["Comment"])) $json["Comment"] = "";
	if (!isset($json["Reference"])) $json["Reference"] = "";
	foreach (array("Description","Reference","Date","Comment") as $curprop) {
	$fzf .= str_pad("🕮  [$curprop]",20) . $json[$curprop] . "\n";
}	
	$bal = 0;
	foreach ($json["Transactions"] as $ct) {
		$bal += $ct["Amount"];
		$prettyamount = str_pad(number_format($ct["Amount"],2,".",","),15, " ",STR_PAD_LEFT);
		$prettybal= str_pad(number_format($bal,2,".",","),15, " ",STR_PAD_LEFT);
		$fzf .= "💸 [t$i] 💸\t$ct[Account]\t$ct[Func]\t$prettyamount\t$prettybal\n";
		$i++;
	}
	$fzf .= "💸 [t$i] 💸\tNew\n";
	$valg = fzf($fzf,"Select field for changing","--tac -e",true);
	if ($valg != "") {
		$x = explode("[",explode("]",$valg)[0])[1];
		if (substr($x,0,1) == "t") {
			$json = changetrans($json,substr($x,1));
		}
		else {
			exec("tmux display-popup -E 'gum input --prompt=\"$x [$json[$x]]: \">~/tmp/gumout'");
			$json[$x] = trim(file_get_contents("/home/$op/tmp/gumout"));
		}
	}
	echo json_encode($json,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}
function changetrans($json,$trans){
	//function lookup_acc($accounts,$bal,$alias = "",$multi = "--multi") {
	require_once("/svn/svnroot/Applications/lookup_account.php");
	global $tpath;
	global $op;
	if (!isset($json["Transactions"][$trans])) {
		$json["Transactions"][$trans]["Account"] = "New Account - Account";
		$json["Transactions"][$trans]["Func"] = "";
		$json["Transactions"][$trans]["Amount"] = "NEW";
	}
	exec("tmux display-popup -E 'gum input --prompt=\"" . $json["Transactions"][$trans]["Account"] . " \">~/tmp/gumout'");
	$json["Transactions"][$trans]["Account"] = trim(file_get_contents("/home/$op/tmp/gumout"));
	exec("tmux display-popup -E 'php /svn/svnroot/Applications/get_func.php getfunc'");
	$json["Transactions"][$trans]["Func"] = trim(file_get_contents("/home/$op/tmp/getfunc"));
	if ($json["Transactions"][$trans]["Amount"] == "NEW") {
		exec("tmux display-popup -E 'gum input --prompt=Amount >~/tmp/gumout'");
		$json["Transactions"][$trans]["Amount"] = trim(file_get_contents("/home/$op/tmp/gumout"));
	}
	return $json;
}
