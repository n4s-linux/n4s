<?php
	$op = exec("whoami");
	$tpath = getenv("tpath");
	if (isset($argv[1]) && $argv[1] == "r") {
		$acc = explode("[",$argv[2])[0];
		$acc = str_replace(" ",".",trim($acc));
		echo "acc='$acc'\n";
		system("LEDGER_ACCOUNT_WIDTH=10 LEDGER_BEGIN=1900-01-01 LEDGER_END=2099-12-31 php /svn/svnroot/Applications/newl.php r \"$acc\"");
		die();
	}
	require_once("/svn/svnroot/Applications/fzf.php");
	ob_start();
	system("cd $tpath;ls");
	$x = explode("\n",trim(ob_get_clean()));
	$data = json_decode(file_get_contents("$tpath/".$argv[1]),true);
	$stats = array();
	$total = 0;
	foreach ($x as $curx) {
		if (!file_exists("$tpath" . "/$curx")) continue;
		if (is_dir("$tpath" . "/$curx")) continue;
		$curx = json_decode(file_get_contents("$tpath" . "/$curx"),true);
		if ($curx == null) continue;
		if (!isset($data["Description"])) continue;
		if (!isset($curx["Description"])) continue;
		similar_text($curx["Description"],$data["Description"],$percent);
		if ($percent < 75) continue;
		foreach ($curx["Transactions"] as $curtrans) {
			$identify = $curtrans["Account"] . " [" . $curtrans["Func"] . "]";
			if (!isset($stats[$identify])) $stats[$identify] = 0;
			$stats[$identify] += $percent;
			$total += $percent;
		}
	}
	asort($stats);
	$fzf = "";
	foreach ($stats as $curstat => $curval) {
		$percent = round($curval / $total * 100,2);
		$fzf .= "$curstat\t" . $percent . "٪\n";
	}
	$valg = fzf($fzf,"Pick match","--preview-window=bottom --preview='php /svn/svnroot/Applications/newl_guesser.php r {}' --tac ",true);
	if ($valg == "") {
		file_put_contents("/home/$op/tmp/guesswhat.dat","");
	}else {
		$x = explode("[",$valg);
		$konto = trim($x[0]);
		$func = $x[1];
		$func = explode("]",$func)[0];
		file_put_contents("/home/$op/tmp/guesswhat.dat",json_encode(array("konto"=>$konto,"func"=>$func)));
	}
?>
