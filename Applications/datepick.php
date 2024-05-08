<?php
	if (!isset($datepicktext)) $datepicktext="";
	require_once("nicem.php");
	if (getenv("tpath") == "") die("dp Kræver du er i et regnskab\n");
	require_once("ansi-color.php");
	$begin = getenv("LEDGER_BEGIN"); $end = getenv("LEDGER_END");
	if (!isset($quiet)) echo set("\nUdskifter periode\t$begin - $end\n","yellow");
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
		if (date("Y") != $år)
			$fzf .= "$år\n";
	
	}
	$fzf .= "Altid\n";
	$fzf .= "Idag\n";
	$fzf .= "ÅTD\n";
	$fzf .= "MANUEL\n";
	$fzf .= date("Y");
	$valg = fzf($fzf,"vælg periode $datepicktext","--height=10 --tac --exact");
	if ($valg =="")die();
	else if ($valg == "Altid") {$begin = "1970-01-01"; $end=date("Y-m-d",strtotime("tomorrow"));}
	else if ($valg == "MANUEL") {
		echo "Begin: "; $fd = fopen("PHP://stdin","r");$begin = trim(explode("\n",fgets($fd))[0]);fclose($fd);
		echo "End: "; $fd = fopen("PHP://stdin","r");$end = trim(fgets($fd));fclose($fd);
	}
	else if ($valg == "Idag") {
		$begin = date("Y-m-d",strtotime("today"));
		$end = date("Y-m-d",strtotime("tomorrow"));
	}
	else if ($valg == "ÅTD") {
		$begin = date("Y") . "-01-01";
		$end = date("Y-m-d",strtotime("tomorrow"));
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
	$bn = basename(getenv("tpath"));
	if (!isset($quiet))
		file_put_contents("/home/$op/tmp/.datepick_$bn","export LEDGER_BEGIN=$begin\nexport LEDGER_END=$end\n");	
	else
		file_put_contents("/home/$op/tmp/.datepick_$bn","LEDGER_BEGIN=$begin LEDGER_END=$end\n");	
	if (!isset($quiet)) echo set("Ny periode\t\t$begin - $end\n","green");
?>

