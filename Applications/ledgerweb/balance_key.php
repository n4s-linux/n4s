<?php 
require_once("/svn/svnroot/Applications/key.php");
?>
<!doctype html> 
<html lang="en">
<?php
require_once("/svn/svnroot/Applications/ledgerweb/ledger.php");
require_once("config.php");
	global $datafile;
$tla = toplevels($datafile);
	system("ledger --balance-format=\"%(account)\\n\" -f \"$datafile\" --depth 1 bal --no-total >/tmp/tla");
	$tla = explode("\n",file_get_contents("/tmp/tla")); // TLA stands for Top Level Account :)
	system("unlink /tmp/tla");

	//$tla = array("Indtægter",'Udgifter','Aktiver','Egenkapital','Passiver');
echo "<pre>";
$tablez = array();
	usort($tla,"tlasort");
foreach ($tla as $tl) {
	if (strlen(trim($tl)))
		$tablez[] = balance_html($datafile,"bal $tl:","$tl",true);
}
	echo "Balance for $begin - $end / $beginytd / $endytd\n";
foreach ($tablez as $table)
	echo $table . "<br>";
?>
<?php
	function tlasort($a,$b) {
		$keys = array("Indtægter"=>0,"Udgifter"=>1,"Aktiver"=>3,"Passiver"=>5,"Egenkapital"=>4,"Resultatdisponering"=>2);
		if (isset($keys[$a]) && isset($keys[$b])) {
			return $keys[$a] > $keys[$b];
		}

		
	}
function balance_html($datafile,$cmd,$overskrift,$first = false) {
	ob_start();
	global $totalytd;
	global $begin;
	global $end;
	global $beginytd;
	global $endytd;
	$data = runledger($datafile,$cmd,$begin,$end,$beginytd,$endytd);
	echo "<table id=myTable name=myTable border=1 width=700>";
	$hoverskrift = "<b>" . ($overskrift) . "</b>";
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
		echo "<tr><td>$accname</td><td><p align=right>$values[period]</p></td><td><p align=right>$values[ytd]</p></td></tr>";
	}
	echo "<tr><td><b>$overskrift i alt</b></td><td><p align=right><b>$periodsum</b></p></td><td><p align=right><b>$ytdsum</b></p></td></tr>";
	echo "</table>";
$retval = ob_get_contents();
	ob_end_clean();
return $retval;
}

