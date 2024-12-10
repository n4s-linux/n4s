<?php
	ob_start();
	system("LEDGER_SORT=date LEDGER_BEGIN=1970-01-01 LEDGER_END=2099-12-31 tpath=/data/regnskaber/transactions_igangv/ php /svn/svnroot/Applications/newl.php csv");
	$data = explode("\n",ob_get_clean());
	if (!isset($argv[1])) $account = selectaccount();
	else $account = $argv[1];
	$curbal = 0;
	foreach ($data as $curdata) {
		$cdata = str_getcsv($curdata);
		if (!isset($cdata[3]) ||$cdata[3] != $account) continue;
		$cdata = str_getcsv($curdata);
		$date = str_replace("\"","",$cdata[0]);
		$tekst = str_pad(substr($cdata[2],0,40),40);
		$amount = str_pad(number_format($cdata[5],2,",","."),15," ",STR_PAD_LEFT);
		$curbal += $cdata[5];
		$balance = str_pad(number_format($curbal,2,",","."),15," ",STR_PAD_LEFT);
		echo "$date\t$tekst\t$amount\t$balance\n";
	}
function selectaccount() {
	global $data;
	$accounts = array();
	foreach ($data as $curdata) {
		$cdata = str_getcsv($curdata);
		if (!isset($cdata[3])) continue;
		if (!stristr($cdata[3],"Igangværende")) continue;
		if (!in_array($cdata[3],$accounts)) array_push($accounts,$cdata[3]);
	}
	require_once("/svn/svnroot/Applications/fzf.php");
	$fzf = "";
	foreach ($accounts as $curacc) $fzf .= "$curacc\n";
	$valg = fzf($fzf);
	if ($valg == "") die("must select acc\n");
	return $valg;
}
?>
