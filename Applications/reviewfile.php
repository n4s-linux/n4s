<?php
	require_once("ansi-color.php");
	function diec($txt,$color = 'red') {
		die(set($txt,$color) . "\n");
	}
	/*
	2023-03-27T08:19 joo	#ide hvis en transaktion indeholder #review skal den altid reviewes
	2023-03-27T11:10 joo	#ide alle tidsregistreringer skal godkendes hvis de er +30min
	*/
	$review = false;
	$data = json_decode(file_get_contents($argv[1]),true);
	$amount = abs($data['Transactions'][0]['Amount']);
	$desc = $data['Description'];
	if (stristr($desc,"#review")) $review = true;
	if (stristr($desc,"løn") && !$review) diec ( "$argv[1] er lønbilag");
	$uid = $data['UID'];
	$dato = $data['Date'];
	$a1 = $data['Transactions'][0]['Account'];
	$a2 = $data['Transactions'][1]['Account'];
	if (erstatus($a1) && erstatus($a2)) {
		diec("$argv[1] status til status","blue");
	}
	if (isset($data['Comment'])) 	$comment = $data['Comment']; else $comment = "";
	if (isset($data['Approved'])) diec("$argv[1] already approved","yellow");
	if (strtotime($dato) < strtotime("2022-01-01")) diec("$argv[1] < 2022-01-01","green");
	if ($amount < 10000 && !$review) diec("$argv[1] Amount < 10000","brown");
	$bn = dirname($argv[1]);
	$fn = pathinfo($argv[1], PATHINFO_FILENAME);
	$today = date("Y-m-d",strtotime("tomorrow"));
	$cmd = "cd \"$bn\";LEDGER_DEPTH=5 LEDGER_BEGIN=1970/1/1 LEDGER_END=$today justshowall=1 tpath=\"$bn\" php /svn/svnroot/Applications/key.php search $uid $dato $amount $desc";
	system("$cmd");
	require_once("fzf.php");
	$svar = fzf("Godkend\nAfvis","Vil du godkende ?");
	if ($svar == "Godkend") {
		$op = exec("whoami");
		$date = date("Y-m-d G:i:s");
		$data['Approved'] = " ✔ $op @ $date";
		array_push($data['History'], array('Date'=>date("Y-m-d G:i:s"),'Desc'=>"Approved transaction by $op"));
		file_put_contents($argv[1],json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	}
	sleep(5);
	function erstatus($konto) {
		return (stristr($konto,"Aktiver")||stristr($konto,"Passiver")||stristr($konto,"Egenkapital"));
	}
?>
