<?php
$totalsum = 0;
function moneyflow() {
global $totalsum;
$rv = "<center>";
$rv .= moneyspec();
ob_start();
system("color=none php /svn/svnroot/Applications/newl.php csv --related ^Aktiver:Likvider ^Passiver:Kassekredit");
$data = explode("\n",trim(ob_get_clean()));
foreach ($data as $curline) {
	$d = str_getcsv($curline);
	if (!isset($d[3])) continue;
	$acc = $d[3];
	$amount = -$d[5];
	error_reporting(0);
	$moneyflow[$acc] += $amount;
	error_reporting(E_ALL);
}
asort($moneyflow,SORT_NUMERIC);
foreach (array("Egenkapital","Indtægter","Udgifter","Aktiver","Passiver","Fejlkonto") as $curgroup) {
	$sum = 0;
	$psum = 0;
	//style="white-space:nowrap;"
	$currv= "<h3>$curgroup</h3>";
	$currv.= "<table style='white-space:nowrap' width=600 class=table>";
	$currv .= "<td width=550>Konto</td><td width=100><p align=right>Ind/Ud</p></td><td><p align=right>Nulkontrol</p></td></tr>";
	foreach ($moneyflow as $key => $val) {
		if (substr($key,0,strlen($curgroup)) == $curgroup) {
			$key=str_replace(":","⇒",$key);
			$pval = number_format($val,0,",",".");
			$totalsum += $val;
			$ptotal = number_format($totalsum,0,",",".");
			$currv .="<tr><td width=550>$key</td><td width=100><p align=right>$pval</p><td><p align=right>$ptotal</p></td></td>";
			$sum += $val;
			$psum = number_format($sum,0,",",".");
		}
	}
	$currv .="<tr><td width=550><b><u>Total $curgroup</u></b></td><td width=100><b><u><p align=right>$psum</p></u></b></td><td><p align=right><b><u>$ptotal</u></b></p></td>";
	$currv .= "</table><br>";
	if ($sum != 0) $rv .= $currv;
}
$rv .= "</center>";


return $rv;
}
	function moneyspec() {
	$rv = "";
	global $totalsum;
		$rv .= "<br><h3>Bevægelser</h3>";
	$rv .= "<table style='white-space:nowrap' width=600 class=table>";
		$rv .= "<td width=550>Konto</td><td width=100><p align=right>Ind/Ud</p></td><td><p align=right>Nulkontrol</p></td></tr>";
	ob_start();
	system("color=none php /svn/svnroot/Applications/newl.php csv ^Aktiver:Likvider ^Passiver:Kassekredit");
	$data = explode("\n",trim(ob_get_clean()));
	$moneyflow=array();
	foreach ($data as $curline) {
		$d = str_getcsv($curline);
		error_reporting(0);
		$acc = $d[3];
		$amount = $d[5];
		error_reporting(0);
		$moneyflow[$acc] += $amount;
		error_reporting(E_ALL);
	}

	foreach ($moneyflow as $key => $val) {
		if ($val == 0) continue;
		$totalsum -= $val;
		$pval = number_format($val,0,",",".");
		$ptotal = number_format($totalsum,0,",",".");
		$key=str_replace(":","⇒",$key);
		$rv .= "<tr><td width=550>$key</td><td width=100><p align=right>$pval</p></td><td><p align=right>$ptotal</p></td></tr>";
	}
	$rv .= "</table>";
	return $rv;
	}
?>
