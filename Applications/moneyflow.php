<?php
	$totalsum = 0;
	function moneyflow() {
	global $totalsum;
	$rv = "<center>";
	ob_start();
	system("color=none php /svn/svnroot/Applications/newl.php csv --related ^Aktiver:Likvider");
	$data = explode("\n",trim(ob_get_clean()));
	foreach ($data as $curline) {
		$d = str_getcsv($curline);
		$acc = $d[3];
		$amount = -$d[5];
		error_reporting(0);
		$moneyflow[$acc] += $amount;
		error_reporting(E_ALL);
	}
	ksort($moneyflow);
	foreach (array("Egenkapital","Indtægter","Udgifter","Aktiver","Passiver","Fejlkonto") as $curgroup) {
		$sum = 0;
		//style="white-space:nowrap;"
		$rv .= "<h3>$curgroup</h3>";
		$rv .= "<table style='white-space:nowrap' width=600 class=table>";
		$rv .= "<td width=550>Konto</td><td width=100><p align=right>Ind/Ud</p></td><td><p align=right>Nulkontrol</p></td></tr>";
		foreach ($moneyflow as $key => $val) {
			if (substr($key,0,strlen($curgroup)) == $curgroup) {
				$key = substr($key,strlen($curgroup)+1);
				$pval = number_format($val,0,",",".");
				$totalsum += $val;
				$ptotal = number_format($totalsum,0,",",".");
				$rv .="<tr><td width=550>$key</td><td width=100><p align=right>$pval</p><td><p align=right>$ptotal</p></td></td>";
				$sum += $val;
				$psum = number_format($sum,0,",",".");
			}
		}
		$rv .="<tr><td width=550><b><u>Total $curgroup</u></b></td><td width=100><b><u><p align=right>$psum</p></u></b></td><td><p align=right>$ptotal</p></td>";
		$rv .= "</table><br>";
	}
	$rv .= moneyspec();
	$rv .= "</center>";


	return $rv;
	}
	function moneyspec() {
	global $totalsum;
		$rv .= "<br><h3>Specifikation</h3>";
	$rv .= "<table style='white-space:nowrap' width=600 class=table>";
		$rv .= "<td width=550>Konto</td><td width=100><p align=right>Ind/Ud</p></td><td><p align=right>Nulkontrol</p></td></tr>";
	ob_start();
	system("color=none php /svn/svnroot/Applications/newl.php csv ^Aktiver:Likvider");
	$data = explode("\n",trim(ob_get_clean()));
	$moneyflow=array();
	foreach ($data as $curline) {
		$d = str_getcsv($curline);
		error_reporting(0);
		$acc = $d[3];
		$amount = -$d[5];
		error_reporting(0);
		$moneyflow[$acc] += $amount;
		error_reporting(E_ALL);
	}

	foreach ($moneyflow as $key => $val) {
		$totalsum += $val;
		$pval = number_format($val,0,",",".");
		$ptotal = number_format($totalsum,0,",",".");
		$rv .= "<tr><td width=550>$key</td><td width=100><p align=right>$pval</p></td><td><p align=right>$ptotal</p></td></tr>";
	}
	$rv .= "</table>";
	return $rv;
	}
?>
