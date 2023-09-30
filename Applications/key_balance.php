<?php
	top:
	require_once("ansi-color.php");
	$op = exec("whoami");
	$data = file_get_contents("/home/$op/tmp/key_balance.tmp");
	$lines = explode("\n",$data);
	$saldi = array();
	foreach ($lines as $curline) {
		$x = explode("|||||",$curline);
		if (!isset($x[1])) continue;
		$account = $x[0];
		$amount = $x[1];
		$tla = explode(":",$account)[0];
		$nextlevel = explode(":",$account)[1];
		$saldi[$tla][] = array('account'=>substr($account,strlen($tla) +1),'amount'=>$amount);
	}
	$fzf =array();
	$sumz = array();
	foreach ($saldi as $curtla => $key) {
	$tlasum = 0;
		if (!isset($fzf[$curtla])) $fzf[$curtla] = "";
		$fzf[$curtla] .= set(($curtla) . "\t" . str_pad("",15," ",STR_PAD_LEFT) . "\n" ,"underline");
		$subacc = null;
		foreach ($key as $curkey) {
			$tlasum += $curkey['amount'];
			$subacc = explode(":",$curkey['account'])[0];
			$fzf[$curtla] .= set($curtla . ":" . $curkey['account'] . "\t⚡" . str_pad(number_format($curkey['amount'],2,",","."),15," ",STR_PAD_LEFT) . "⚡\n","blue_bg");
		}
		$nicesum = number_format($tlasum , 2,",",".");
		$fzf[$curtla] .= set(($curtla) . "\t⚡" . str_pad(number_format($tlasum,2,",","."),15," ",STR_PAD_LEFT) . "⚡\n" ,"magenta_bg");
	}
	require_once("fzf.php"); 
	$fzfarray = $fzf;
	$fzf = "";
	$order = array('Indtægter','Udgifter','Resultatdisponering','Aktiver','Egenkapital','Passiver');
	foreach ($order as $curacc) {
		if (isset($fzfarray[$curacc])) {
			$fzf .= $fzfarray[$curacc];
		}
	}
	$konto = trim(explode("⚡",fzf($fzf,"vælg konto","--ansi --layout=reverse",true))[0]);
	require_once("proc_open.php");
	if ($konto != "") {
		exec_app("php /svn/svnroot/Applications/key.php ledger csv \"^$konto\"|php /svn/svnroot/Applications/zoom.php");
		goto top;
	}
?>
