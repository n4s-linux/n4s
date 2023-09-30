<?php
$data = "";
$t = array();
$data = array();
$k = array();
while ($line = fgetcsv(STDIN,null,",","\"","\\")) {
	$ct = array('date'=>$line[0],'tekst'=>$line[2],'reference'=>$line[1],'account'=>$line[3],'amount'=>$line[5],'tags'=>$line[7]);
	if ((stristr($ct['tekst'],'rabat'))) continue;
	if ((stristr($ct['konto'],'kompensation'))) continue;
	if ((stristr($ct['konto'],'camilla'))) continue;
	if ((stristr($ct['tekst'],'faktura') || stristr($ct['tekst'],'stripe')) && $ct['amount'] < 0) continue;
	if (strtotime($ct['date']) < time() - (86400*365)) continue;
	array_push($data,$ct);
	$t[intval(date("m",strtotime($ct['date'])))] += $ct['amount'];
	$k[$ct['account']] += $ct['amount'];
	echo $ct['amount'] . "\t";
	echo implode("-",$ct) . "\n";
}
ksort($t);
print_r($t);

$start = date("m") +1;
for ($i = 1;$i<12;$i++) {
	$date = date("Y-m",strtotime("+$i months"));
	echo $date . "\n";

}
