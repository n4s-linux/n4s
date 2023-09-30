<?php
// Formål: Beregning af rente på baggrund af aktuel ledger
// Status: Ikke funktionel

$accounts = "";
$transactions = array();
$op = exec("whoami");
$fd = fopen("/home/$op/tmp/interest.out","r");
$acc = array();
while ($line = fgetcsv($fd,null,",","\"","\\")) { // GET DATA 
	$date = $line[0];
	$bilag = $line[1];
	$desc = $line[2];
	$konto = $line[3];
	$fn = $line[7];
	$belob = floatval($line[5]);
	if (!isset($acc[$konto])) {
		$accounts .= "$konto\n";
		$acc[$konto] = 1;
	}
	array_push($transactions,array('date'=>$date,'bilag'=>$bilag,'desc'=>$desc,'konto'=>$konto,'fn'=>$fn,'belob'=>$belob));
}
fclose ($fd);
require_once("/svn/svnroot/Applications/fzf.php");
$konto = fzf($accounts,"vælg konto");
$firsttrans = time();
foreach ($transactions as $curtrans) {
	if ($curtrans['konto'] != $konto) continue;
	if (strtotime($curtrans['date']) < $firsttrans) $firsttrans = strtotime($curtrans['date']);
}
$endtrans = strtotime("+24 months");
$curtime = $firsttrans;
while ($curtrans < $endtrans) {
	$curtime += 86400*30;
	echo "ct: $curtime\n";
	foreach ($transactions as $curtrans) {
		if ($curtrans['konto'] != $konto) continue;
		print_r($curtrans);
	}
}
