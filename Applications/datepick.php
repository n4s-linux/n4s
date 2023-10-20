<?php
	require_once("nicem.php");
	if (getenv("tpath") == "") die("dp Kræver du er i et regnskab\n");
	require_once("ansi-color.php");
	$begin = getenv("LEDGER_BEGIN"); $end = getenv("LEDGER_END");
	echo set("Udskifter periode\t$begin - $end\n","yellow");
	$begin = null;
	$end = null;
	require_once("fzf.php");
	$fzf = "";
	for ($år = date("Y") -15; $år <= date("Y") +5;$år++) { // HVERT ÅR i $år
		for ($i = 1;$i<13;$i++) {
			$fzf .= "$år" . "-" . nicem($i) . "\n";;		
		}
		for ($i=1;$i<5;$i++) {
			$fzf .= "$år" . "Q$i\n";	
		}
		for ($i=1;$i<3;$i++) {
			$fzf .= "$år" . "H$i\n";	
		}
		$fzf .= "$år\n";
	
	}
	$fzf .= "Altid\n";
	$fzf .= "MANUEL";
	$valg = fzf($fzf,"vælg periode","--height=10 --tac --exact");
	if ($valg =="")die();
	else if ($valg == "Altid") {$begin = "1970-01-01"; $end=date("Y-m-d",strtotime("tomorrow"));}
	else if ($valg == "MANUEL") {
		echo "Begin: "; $fd = fopen("PHP://stdin","r");$begin = trim(explode("\n",fgets($fd))[0]);fclose($fd);
		echo "End: "; $fd = fopen("PHP://stdin","r");$end = trim(fgets($fd));fclose($fd);
	}
	else if (strlen($valg) == 4) {
		$begin = $valg . "-01-01";
		$end = $valg+1 . "-01-01";
	}
	else if (stristr($valg,"Q")) {
		$år = explode("Q",$valg)[0];
		$q = substr($valg,"-1");
		if ($q == 1) { $begin = $år ."-01-01"; $end = $år . "-04-01"; }
		if ($q == 2) { $begin = $år . "-04-01"; $end = $år . "-07-01"; }
		if ($q == 3) { $begin = $år . "-07-01"; $end = $år . "-10-01"; }
		if ($q == 4) { $begin = $år . "-10-01"; $end = $år + 1 . "-01-01"; }

	}
	else if (stristr($valg,"H")) {
		$år = explode("H",$valg)[0];
		$q = substr($valg,"-1");
		if ($q == 1) { $begin = $år ."-01-01"; $end = $år . "-07-01"; }
		if ($q == 2) { $begin = $år . "-07-01"; $end = $år + 1 . "-01-01"; }

	}
	else {
		$år = explode("-",$valg)[0];
		$m = intval(explode("-",$valg)[1]);
		if ($m != 12) { 
			$begin = $år . "-" . nicem($m) . "-01";
			$end = $år . "-" .  nicem($m+1) . "-01";
		}
		else {
			$begin = $år . "-" . nicem($m) . "-01"; 
			$end = $år +1 . "-01-01"; 
		}

	}
	$op = 	exec("whoami");
	file_put_contents("/home/$op/tmp/.datepick","export LEDGER_BEGIN=$begin\nexport LEDGER_END=$end\n");	
	echo set("Ny periode\t\t$begin - $end\n","green");
?>

