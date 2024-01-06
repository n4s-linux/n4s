<?php
$startdate = date("Y-m-d");
$startdate = getenv("budgetstart");
$bankkonto = "Aktiver:Likvider:RevolutDKK";
$lookback = getenv("gobackmonths");
echo "; lookback = $lookback\n";
$budgetmonths=getenv("forecast");
ob_start();
$lastday = date("Y-m-t");
$lastday = date("Y-m-d",strtotime($lastday ." + 1 day"));
$cmd = ("noend=1 budget=0 LEDGER_BEGIN=$(date +%Y-%m-%d --date='-$lookback months') LEDGER_END=$lastday php /svn/svnroot/Applications/newl.php csv");
system($cmd);
$data = ob_get_clean();
$t = array();
$x = str_getcsv($data,"\n");
foreach ($x as $curx) {
	$t[] = str_getcsv($curx);
}   
$months = array();
$momspayments = getmomspayments($t);
foreach ($t as $curt) {
	if (!isset($curt[3])) continue;
	$month = date("m",strtotime($curt[0]));
	$year= date("Y",strtotime($curt[0]));
	$account = $curt[3];
	$amount = $curt[5];
	error_reporting(0);
	$months[$year . "-" .$month][$account] += $amount;
	error_reporting(E_ALL);
}
$avg = average($months);
function average($monthsstats) {
	global $startdate;
	global $budgetmonths;
	global $bankkonto;
	global $momspayments;
	$total = array();
	foreach ($monthsstats as $curmonth) {
		foreach ($curmonth as $account => $val) {
			error_reporting(0);
			$total[$account] += $val;
			if ($total[$account] == 0) unset( $total[$account]);
			error_reporting(E_ALL);
		}
	}
	$average = array();
	foreach ($total as $curtotal => $curval) {
		$average[$curtotal] = $curval / count($monthsstats);
	}
	$nt = array();
	$momspayments_new = array();
	for ($i = 0;$i<$budgetmonths;$i++) {
		foreach ($average as $curavg => $curval) {
			$curval = round($curval,2);
			if (stristr($curavg,"Indtægter:") || stristr($curavg,"Udgifter:" ) && !stristr($curavg,"Afskrivninger")) { // afskrivninger bør være skrevet ud i fremtiden via levetid
				echo "; $curavg => $curval\n";
				error_reporting(0);
				$value = $curval - $monthsstats[date("Y-m",strtotime("$startdate +$i months"))][$curavg];
				if (abs($value) < 50) continue; // bagatelgrænse
				$total[$curavg] += $curval;
				$total[$bankkonto] -= $curval;
				$value = $curval - $monthsstats[date("Y-m",strtotime("$startdate +$i months"))][$curavg];
				array_push($nt,array('month'=>date("Y-m",strtotime("$startdate +$i months")),'Account'=>$curavg,'Amount'=>$value));
				$dato = date("Y-m-d",strtotime("$startdate +$i months"));
				if (stristr($curavg,"Indtægter:")) $momstype="Salgsmoms"; else $momstype="Købsmoms";
				if (!momsfritaget($curavg)) {
					array_push($nt,array('month'=>date("Y-m",strtotime("$startdate +$i months")),'Account'=>"Passiver:Moms:$momstype",'Amount'=>($value*0.25)));
					$momspayments_new[momsduedate($dato,"halfyear")] += ($value* -0.25);
				}
				error_reporting(E_ALL);
			}
			else {
				echo "; Unhandled $curavg => $curval\n";
			}
		}
		foreach ($total as $curtot => $curval) {
			if (stristr($curtot,"Passiver:Kreditorer:")) {
				if ($total[$bankkonto] > $curval) {
					if (intval($curval) != 0) {
						array_push($nt,array('month'=>date("Y-m",strtotime("$startdate +$i months")),'Account'=>$curtot,'Amount'=>-$curval));
						$total[$curtot] -= $curval;
						$total[$bankkonto] += $curval;
					}
				}
			}
		}
	}
	foreach ($momspayments as $dato => $betaling) {
		$betaling = $betaling *-1;
		echo "$dato [Budget] Momsbetaling\n\tPassiver:Moms:Momsafregning  $betaling\n\t$bankkonto\n\n";	
	}
	error_reporting(0);
	foreach ($momspayments_new as $dato => $betaling) {
		echo "$dato [Budget] Momsbetaling\n\tPassiver:Moms:Momsafregning  $betaling\n\t$bankkonto\n\n";	
	}
	error_reporting(E_ALL);
	foreach ($nt as $curt) {
		echo "$curt[month]-01 [Budget] $curt[Account]\n\t$curt[Account]  $curt[Amount]\n\t$bankkonto\n\n";
	}
	foreach ($nt as $curt) { // Periodens resultat medtages af budgetterede indtægts og udgiftskonti
		if (!stristr($curt['Account'],"Indtægter:") && !stristr($curt['Account'],"Udgifter:")) continue;
		echo "$curt[month]-01 [BudgetRes] Periodens budgetterede resultat\n\tEgenkapital:Periodens resultat\t$curt[Amount]\n\tResultatdisponering:Periodens resultat\n\n";
		//echo "$curt[month]-01 [Budget] $curt[Account]\n\t$curt[Account]  $curt[Amount]\n\t$bankkonto\n\n";
	}
}
function getmomspayments($t,$period = "halfyear") {
	$nt = array();
	foreach ($t as $curt) {
		if (!isset($curt[3])) continue;
		if (stristr($curt[3],"Passiver:Moms:Salgsmoms") || stristr($curt[3],"Passiver:Moms:Købsmoms")) {
			error_reporting(0);
			$dd = momsduedate($curt[0],$period);
			if (strtotime($dd) > time())
				$nt[$dd] += $curt[5];
			error_reporting(E_ALL);
		}
	}
	return $nt;
}
function momsduedate($dato,$period) {
	if ($period == "halfyear") {
		switch (date("m",strtotime($dato))) {
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			return date("Y",strtotime($dato)) . "-09-01";
			default:
			return date("Y",strtotime($dato. " + 1 year")) . "-03-01";
		}
	}
}
function momsfritaget($konto) {
	if (stristr($konto,"Udgifter:Personale")) return true;
	if (stristr($konto,"Regulering")) return true;
	if (stristr($konto,"Gebyrer")) return true;
	if (stristr($konto,"Rente")) return true;
	return false;
}
?>
