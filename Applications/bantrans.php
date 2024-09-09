<?php
	if ($tpath == "") die("no tpath set - banaccount\n");
	if (!file_exists("$tpath/.bannedaccounts")) file_put_contents("$tpath/.bannedaccounts",json_encode(array("ToxicAccount1","ToxicAccount2"),JSON_PRETTY_PRINT));
	$banned = json_decode(file_get_contents("$tpath/.bannedaccounts"),true);
	function scanbanned($transactions) {
		global $tpath;
		global $banned;
		if ($tpath == "") die("banaccounts require tpath\n");
		foreach ($transactions as $ct) {
			if (!isset($ct["Transactions"])) continue;
			$changed = 0;
			foreach ($ct["Transactions"] as &$curtrans) {
				if (isset($ct["Status"]) && $ct["Status"] == "Locked") continue;
				if (in_array($curtrans["Account"],$banned)) {
					$curtrans["Account"] = "Banned:$curtrans[Account]";
					$ct["History"][] = array("date"=>date("Y-m-d H:i"),"op"=>exec("whoami"),"Desc"=>"Detected use of banned account '$curtrans[Account]'");
					$changed++;
				}
			}
			if ($changed > 0) {
				$fn = "$tpath/" . $ct["Filename"];
				file_put_contents($fn,json_encode($ct,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
		}
	}
?>
