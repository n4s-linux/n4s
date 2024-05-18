<?php
	require_once("/svn/svnroot/Applications/bookingfuncs.php");
	require_once("/svn/svnroot/Applications/fzf.php");
	$valg = fzf("Book\nValidate book hashes","Chose action");
	if ($valg == "") die("No action chosen");
	if ($valg == "Validate book hashes") {
		system("php /svn/svnroot/Applications/validatebook.php");
		die();
	}
	$op = exec("whoami");
	$tpath = getenv("tpath");
	ob_start();
	system("find $tpath/ -name \*.trans");
	$files = trim(ob_get_clean());$files = explode("\n",$files);
	$data = array();
	foreach ($files as $curfile) {
		$bn = basename($curfile);
		$data[$bn] = json_decode(file_get_contents("$curfile"),true);
		if (isset($data[$bn]["Status"]) && $data[$bn]["Status"] == "Locked") unset($data[$bn]);
	}
	$nbn = nextbookingnumber();
	$selection = array();
	$i = 0;
	$fzf = "";
	foreach ($data as $fn => $curdata) {
		$amount = $curdata["Transactions"][0]["Amount"];
		$pamount = str_pad(number_format($amount,2,".",","),15," ",STR_PAD_LEFT);
		$acc = $curdata["Transactions"][0]["Account"];
		$f1= $curdata["Transactions"][0]["Func"];
		$f2= $curdata["Transactions"][1]["Func"];
		$acc2 = $curdata["Transactions"][1]["Account"];
		$fzf .= "$i 💰\t$curdata[Date]\t$curdata[Description]\t$acc [$f1]\t$pamount\t$acc2 [$f2]\n";
		$selection[$i] = array("fn"=>$fn,"data"=>$curdata);
		$i++;
	}
	$accs = fzf($fzf,"Select transactions to BOOK - SPACE to select all","--multi --bind space:select-all",true);
	if ($accs == "") die("Aborted booking\n");
	$x = explode("\n",$accs);
	$dataselection = array();
	foreach ($x as $curline) {
		$id = trim(explode("💰",$curline)[0]);
		$ds = $selection[$id];
		$dataselection[$ds["fn"]] = $ds["data"];
	}
	$data = $dataselection;
	foreach ($data as $fn=>&$curdata) {
		$nbnstart = $nbn;
		$curdata["Status"] = "Locked";
		$curdata["History"][] = array("op"=>$op,"Desc"=>"Locked transaction","Date"=>date("Y-m-d H:m"));
		foreach ($curdata["Transactions"] as &$curtrans) {
			$curtrans["TransactionNo"] = $nbn++;
		}
		$curdata["HashOfPreviousLyBookedFiles"] = gethash($nbnstart);
		file_put_contents("$tpath/$fn",json_encode($curdata,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		file_put_contents("$tpath/.nextbookingno",$nbn);
	}
?>
