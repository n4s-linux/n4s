<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<style>
@media print{@page {size: landscape}}</style>
<?php
require_once("/svn/svnroot/Applications/uc_odata.php");
function handletrans($t) {
	$b = array();
	foreach ($t as $curt) {
		if (!isset($b[$curt["Account"]])) $b[$curt["Account"]] = 0;
		$b[$curt["Account"]] += $curt["Amount"];
	}
}
$months = 3;
if (strtotime("-3 months") < strtotime("2024-09-02")) $date = "2024-09-02"; else $date = date("Y-m-d",strtotime("-$months months"));
$filter= "Date ge datetime'$date'";
$data = getData("GLTransClient","$filter");
$missing = array();
foreach ($data['value'] as $curval) {
	if (!isset($curval['Date'])) continue;
	if ($curval['HasVoucher']) continue;
	$v = $curval["Voucher"]; $d = $curval["Date"];
	if (!isset($asorted[$d][$v])) $asorted[$d][$v] = array();
	error_reporting(0);
	array_push($asorted[$d][$v],$curval);
	error_reporting(E_ALL);
	
}
$color = "black";
usort($asorted,"bydebit");
echo "<form method=post>";
foreach ($asorted as $cursort) {
	$i = 0;
	foreach ($cursort as $key => $val) {
		$v = $key; // v = voucher

		$hash = md5($v . json_encode($val));
		$t = "<h3>$hash</h3><br>\n<table width=800 border=1 class=table>\n";
		foreach ($val as $ct) {
			if (!has_accs(array(5820,3628,5821,5822,5823,5825,5840,5830,6860,5845,5850,4310,6948,4465,6805),$val)) continue;
			if (is_bal($val)) continue;
			echo "$t";$t = "";
			echo "<tr>";
			$pdate = date("Y-m-d",strtotime($ct["Date"]));
			echo "<td width=50><font color=$color>$v</font></td><td width=50><font color=$color>$pdate</font></td><td width=50><font color=$color>$ct[Account]</font></td>";
			$na = number_format($ct["Amount"],2,",",".");
			echo "<td width=200><font color=$color>$ct[Account]</font></td><td width=200><font color=$color>$ct[Name]</font></td><td width=200><font color=$color><p align=right>$ct[Text]</p></fon
t></td><td width=200><font color=$color><p align=right>$na</p></font></td>";
			echo "</tr>\n";
		}
		if ($t == "") {
			echo "</table>";
			echo "<table><tr><td><input type=checkbox name=ignore$hash>Ignore</td>\n";
			echo "<td><input type=checkbox name=ask$hash>Ask</td></tr></table>\n";
			echo "<br><br>\n";
		}

	$i++;
	}

}
echo "<input type=submit submit></form>\n";
function is_bal($t) {
	$baltypes = array("Short-term debt","Bank","Liquid assets","Long Term Debt","Liability","Active");
	$pltypes = array("Other Income","Expenses","Revenue","Cost","Good Sold","Cost of Goods Sold","Depreciation");
	foreach ($t as $curt) {
		if (in_array($curt["AccountType"],$baltypes)) 
			continue;
		else if (in_array($curt["AccountType"],$pltypes)) 
			return false;
		else
			die("Unhandled type '$curt[AccountType]'\n");
	}
	return true;
}
function has_accs($accounts,$t) {
	$rv = false;
	$matches = array();
	foreach ($t as $curt) {
		foreach ($accounts as $curacc) {
			if ($curacc == $curt["Account"]) $matches[$curacc] = true;
		}	
	}
	return count($matches) != count($t);
}
function getsum($t) {
	$sum = 0;
	foreach ($t as $curt => $curval) {
		foreach ($curval as $curtrans) {
			$sum += $curtrans["Debit"];
		}
	}
	return $sum;
}
function bydebit($a,$b) {
	$suma = getsum($a);
	$sumb = getsum($b);
	if ($suma < $sumb) return 0; else return 1;
}
