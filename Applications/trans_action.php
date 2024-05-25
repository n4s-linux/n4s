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
	$fzf .= "Reverse numbers\n";
	$fzf .= "Invert account order\n";
	if ($json["Transactions"][0]["Amount"] == $json["Transactions"][1]["Amount"] *-1) $fzf .= "Change amount\n";
	$i = 0;
	if (!isset($json["Description"])) $json["Description"] = "";
	if (!isset($json["Comment"])) $json["Comment"] = "";
	if (!isset($json["Reference"])) $json["Reference"] = "";
	foreach (array("Description","Reference","Date","Comment") as $curprop) {
	$fzf .= str_pad("🕮  [$curprop]",20) . $json[$curprop] . "\n";
}	
	$bal = 0;

	if (stristr(json_encode($json["Transactions"]),"AccountSuggestion"))
		$fzf .= "Accept suggestion(s)\n";
	foreach ($json["Transactions"] as $ct) {
		$bal += $ct["Amount"];
		$prettyamount = str_pad(number_format($ct["Amount"],2,".",","),15, " ",STR_PAD_LEFT);
		$prettybal= str_pad(number_format($bal,2,".",","),15, " ",STR_PAD_LEFT);
		$fzf .= "💸 [$i] 💸\t$ct[Account]\t$ct[Func]\t$prettyamount\t$prettybal\n";
		$i++;
	}
	$fzf .= "New Transaction\n";
	$valg = fzf($fzf,"Select field for changing","--tac -e",true);
	if ($valg != "") {
		if ($valg == "Invert account order") {
			$json["Transactions"] = array_reverse($json["Transactions"]);
		}
		else if ($valg == "Accept suggestion(s)") {
		$i = 0;
		foreach ($json["Transactions"] as &$curtrans) {
			if (isset($curtrans["AccountSuggestion"])) {
				$curtrans["Account"] = $curtrans["AccountSuggestion"];
				$json["History"][] = array("date"=>date("Y-m-d H:i"),"op"=>$op,"Desc"=>"Accepted account suggestion for transaction $i");
			}
			if (isset($curtrans["FuncSuggestion"])) {
				$curtrans["Func"] = $curtrans["FuncSuggestion"];
				$json["History"][] = array("date"=>date("Y-m-d H:i"),"op"=>$op,"Desc"=>"Accepted func suggestion for transaction $i");
			}
			$i++;
		}
//                                        $data["Transactions"][$id]['AccountSuggestion'] = $similar["Kontoforslag"];
//					$data["Transactions"][$id]['FuncSuggestion'] = $similar["Momsforslag"];
		}
		else if ($valg == "Change amount") {
			exec("tmux display-popup -h3 -E 'gum input --prompt=\"new amount: \">~/tmp/gumout'");
			$amount = floatval(str_replace(",",".",trim(file_get_contents("/home/$op/tmp/gumout"))));
			foreach ($json["Transactions"] as &$ct) {
				if ($ct["Amount"] < 0) $ct["Amount"] = -$amount;
				else $ct["Amount"] = $amount;
			}
		}
		else if ($valg == "Reverse numbers") {
			foreach ($json["Transactions"] as &$ct) {
				$ct["Amount"] = $ct["Amount"] *-1;	
			}
		}
		else if (substr($valg,0,strlen("💸 [")) == "💸 [") {
			$x = explode("[",explode("]",$valg)[0])[1];
			$json = changetrans($json,$x);
		}
		else if ($valg == "New Transaction") {
			$json["Transactions"][] = array("Account"=>"","Amount"=>0,"Func"=>"");
		}
		else {
			$x = explode("[",explode("]",$valg)[0])[1];
			exec("tmux display-popup -h3 -E 'gum input --prompt=\"change $x: \">~/tmp/gumout'");
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
	exec("tmux display-popup -h3 -E 'gum input --prompt=\"" . $json["Transactions"][$trans]["Account"] . " \">~/tmp/gumout'");
	$json["Transactions"][$trans]["Account"] = trim(file_get_contents("/home/$op/tmp/gumout"));
	exec("tmux display-popup -h20 -E 'php /svn/svnroot/Applications/get_func.php getfunc'");
	$json["Transactions"][$trans]["Func"] = trim(file_get_contents("/home/$op/tmp/getfunc"));
	if ($json["Transactions"][$trans]["Amount"] == "NEW") {
		exec("tmux display-popup -h3 -E 'gum input --prompt=Amount >~/tmp/gumout'");
		$json["Transactions"][$trans]["Amount"] = trim(file_get_contents("/home/$op/tmp/gumout"));
	}
	return $json;
}
