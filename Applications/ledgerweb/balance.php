<?php 
require_once("header.php");
?>
<!doctype html> 
<html lang="en">
<?php
require_once("ledger.php");
require_once("config.php");
$tla = toplevels($datafile);
echo "<pre>";
$tablez = array();
foreach ($tla as $tl) {
	if (strlen(trim($tl)))
		$tablez[gettlasort($datafile,"$tl") . "-" . $tl] = balance_html($datafile,"bal $tl","$tl",true);
}
ksort($tablez);
foreach ($tablez as $table)
	echo $table . "<br>";
function gettlasort($datafile,$tl) {
	$cmd = "grep '; TLA-SORT $tl' $datafile";
	$data = exec($cmd);
	$x = explode("=",$data);
	if (isset($x[1]) && is_numeric($x[1]))
		return $x[1];
	else
		return 9999;

}
?>
<?php
function balance_html($datafile,$cmd,$overskrift,$first = false) {
ob_start();
	global $totalytd;
	$data = runledger($datafile,$cmd);
	echo "<table id=myTable name=myTable border=1 width=700>";
	$hoverskrift = "<b>" . strtoupper($overskrift) . "</b>";
	if ($first) { $hperiod = "<b>Period</b>"; $hytd = "<b>YTD</b>"; } else { $hperiod = "&nbsp;"; $hytd = "&nbsp"; }
	echo "<tr><td width=500>$hoverskrift</td><td width=100><p align=right>$hperiod</p></td><td width=100><p align=right>$hytd</p></td></tr>";
	$ytdsum = 0;
	$periodsum = 0;
	foreach ($data as $acc => $values) {
		$accname = $acc;
		if (!isset($values['period']))
			$values['period'] = 0;
		if (!isset($values['ytd']))
			$values['ytd'] = 0;
		$ytdsum += $values['ytd'];
		$totalytd  += $values['ytd'];
		$periodsum += $values['period'];
		$link = "browse.php?acc=$overskrift:$accname";
		if (substr($link,-1) == ":")
			$link = substr($link,0,strlen($link)-1);
		if (!strlen(trim($accname)))
			$accname = "($hoverskrift)";
		echo "<tr><td><a href=\"$link\">$accname</a></td><td><p align=right><a href=\"$link&showp=period\">$values[period]</a></p></td><td><p align=right><a href=\"$link&showp=ytd\">$values[ytd]</a></p></td></tr>";
	}
	echo "<tr><td><b>$overskrift i alt</b></td><td><p align=right><b>$periodsum</b></p></td><td><p align=right><b>$ytdsum</b></p></td></tr>";
	echo "</table>";
$retval = ob_get_contents();
	ob_end_clean();
return $retval;
}

