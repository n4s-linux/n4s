<?php
$bank = $argv[1];
$saldi = array();
$msaldi = array();
error_reporting(0);
$postings = array();
$latestba = strtotime("1970-01-01");
while ($line = fgetcsv(STDIN,null,",","\"","\\")) {
	$date = $line[0];
	if (strtotime($date) < strtotime(getenv("LEDGER_BEGIN")) || strtotime($date) >= strtotime(getenv("LEDGER_END"))) continue;
	$tekst = $line[2];
	$konto = $line[3];
	$belob = $line[5];
	$month = date("Y-m",strtotime($date));
	$saldi[$date] += $belob;
	$msaldi[$month] += $belob;
	if (intval($saldi[$date]) == 0) unset($saldi[$date]);
	if (intval($msaldi[$month]) == 0) unset($msaldi[$month]);
	$postings[] = array('match'=>false,'date'=>$date,'tekst'=>$tekst,'konto'=>$konto,'belob'=>$belob);
}
error_reporting(E_ALL);
require_once("fzf.php");
$t = $postings;
$fzf = "";
$saldo = 0;
while (removematches()) echo "";
printnonmatch($t);
function mktrans($date,$tekst,$bank,$belob,$modkonto) {
	$uid = uniqid();
	$fn = date("Ymd") . "_bankafstemning_$uid.trans";
	$retval = array('Description'=>$tekst,'UID'=>$uid,'Reference'=>'BANKAFSTEMNING','Filename'=>$fn);
	$retval['History'] = array(array('date'=>date("Y-m-d"),'desc'=>'Indlæst via bankafstemning'));
	$retval['Transactions'] = array(
		array('Account'=>$bank,'Amount'=>$belob,'Func'=>""),
		array('Account'=>$modkonto,'Amount'=>-$belob,'Func'=>"")
	);
	print_r($retval);
	return $retval;
}
function printnonmatch($t) {
	global $bank;
	foreach ($t as $curt) {
		if ($curt['match'] != true) {
			mktrans($curt['date'],$curt['tekst'],$bank,$curt['belob'],"Fejlkonto:Bankafstemning");
		}
	}
}
function removematches() {
	global $t;
	foreach ($t as &$curtrans) {
		if ($curtrans['match'] == true) continue;
		foreach ($t as &$curtransmatch) {
			if ($curtransmatch['match'] == true) continue;
			if ($curtrans['belob'] == -1 * $curtransmatch['belob']) {
				$curtrans['match'] = true;
				$curtransmatch['match'] = true;
				return true;
			}
		}
	}
	return false; // no success, stop trying
}
?>
