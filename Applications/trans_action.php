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
	if(stristr($json["Transactions"][1]["Account"],"fejl")) {
		$fzf .= "💡" . applySubduedColors("Let me Guess") . "💡 \n";
	}
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
	$valg = fzf($fzf,"Select field for changing","--ansi --tac -e",true);
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
		else if (stristr($valg,"Let me Guess")) {
			exec("tmux display-popup -E -w120 \"tpath=$tpath php /svn/svnroot/Applications/newl_guesser.php \"$json[Filename]\"\"");
			$output = file_get_contents("/home/$op/tmp/guesswhat.dat");
			if ($output != "") {
				$j = json_decode($output,true);
				$json["Transactions"][1]["Account"] = $j["konto"];
				$json["Transactions"][1]["Func"] = $j["func"];
				$json["History"][] = array("date"=>date("Y-m-d H:i"),"op"=>$op,"Desc"=>"Accepted account based on similar transaction findings");
			}

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
function applyRainbowColors($text) {
    // ANSI escape codes for rainbow colors (using 256 color palette)
    $rainbow_colors = [
        "\033[38;5;196m", // Red
        "\033[38;5;202m", // Orange
        "\033[38;5;226m", // Yellow
        "\033[38;5;46m",  // Green
        "\033[38;5;21m",  // Blue
        "\033[38;5;93m",  // Indigo
        "\033[38;5;201m"  // Violet
    ];

    // ANSI escape code for resetting colors
    $reset = "\033[0m";

    // Initialize the rainbow text
    $rainbow_text = '';

    // Loop through each character in the string and apply a different color
    for ($i = 0; $i < strlen($text); $i++) {
        // Cycle through the rainbow colors
        $color = $rainbow_colors[$i % count($rainbow_colors)];
        $rainbow_text .= $color . $text[$i];
    }

    // Add the reset code at the end to reset the colors
    $rainbow_text .= $reset;

    return $rainbow_text;

}
function applySubduedColors($text) {
    // ANSI escape codes for a subdued color palette (using 256 color palette)
    $subdued_colors = [
        "\033[38;5;240m", // Grey
        "\033[38;5;243m", // Light Grey
        "\033[38;5;245m", // Light Grey
        "\033[38;5;247m", // Very Light Grey
        "\033[38;5;250m", // Almost White
        "\033[38;5;252m", // Very Light Grey
        "\033[38;5;254m"  // Lightest Grey
    ];

    // ANSI escape code for resetting colors
    $reset = "\033[0m";

    // Initialize the subdued text
    $subdued_text = '';

    // Loop through each character in the string and apply a different color
    for ($i = 0; $i < strlen($text); $i++) {
        // Cycle through the subdued colors
        $color = $subdued_colors[$i % count($subdued_colors)];
        $subdued_text .= $color . $text[$i];
    }

    // Add the reset code at the end to reset the colors
    $subdued_text .= $reset;

    return $subdued_text;
}
