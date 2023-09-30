<?php
$cmd = "grep \"#ng\" /data/regnskaber/transactions_crm/.tags/*|grep -v .diff > ~/tmp/ngers.txt";
system($cmd);
$ngers = explode("\n",file_get_contents("/home/joo/tmp/ngers.txt"));
foreach ($ngers as $curng) {
	$line = explode(":",$curng);
	if (!isset($line[1])) continue;
	$fn = basename($line[0]);
	$ng = explode(" ",$line[1]);
	$tag = trim($ng[0]);
	$deadline = $ng[2];
	if (strlen($deadline) != strlen("YYYY-mm-dd")) continue;
	$estimate = getestimate($tag,$fn);
	error_reporting(0);
	$dlz[$deadline][$tag] += 1;
	error_reporting(E_ALL);
}
ksort($dlz);
echo "\n|dato|pligt|tæller|\n|---|---|---|\n";
foreach ($dlz as $date => $tag) {
	$color = getcolor($date);
	foreach ($tag as $pligt => $count) {
		echo "|<font color=$color>$date</b>|<font color=$color>$pligt</b>|<font color=$color>$count</b>|\n";
	}
}
function getcolor($date) {
	$ts = strtotime($date);
	$now = time();
	$fortnight = strtotime("+14 days");
	if ($ts > $fortnight)
		return "green";
	else if ($ts < $now)
		return "red";
	else
		return "yellow";
}
function getestimate($tag,$fn) {
	return 1;
}
?>
