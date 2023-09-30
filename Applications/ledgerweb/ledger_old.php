<?php
function runledger ($datafile = null,$call = 'bal', $begin= '2018-05-01', $end = '2038-05-31',$beginytd='2018-01-01',$endytd='2038-05-31',$depth=3) {
     //   $begin = $_COOKIE['begin'];$end = $_COOKIE['end']; $beginytd= $_COOKIE['beginytd'];$endytd = $_COOKIE['endytd'];
	$call = str_replace("/",'"\/"',$call);
	$cmd = ("LEDGER_BEGIN=$begin LEDGER_END=$end LEDGER_DEPTH=$depth test=1 ledger --depth=$depth --recursive-aliases -B -f \"$datafile\" $call --no-total --begin '$begin' --end '$end' --register-format=\"%(date) %(payee) |||||%(display_amount)|||||%(note)|||||\\n\" --balance-format=\"%(account)|||||%(display_total)\\n\" > /tmp/loutput\n");
	system_print($cmd);
/*
if (stristr($call,"indtægter")) {
echo "<pre>";
echo file_get_contents("/tmp/loutput");die();
}
*/

	$data['period'] = explode("\n",file_get_contents("/tmp/loutput"));
	$cmd = ("LEDGER_BEGIN=$beginytd LEDGER_END=$endytd LEDGER_DEPTH=$depth test=2 ledger --depth=$depth --recursive-aliases -B -f \"$datafile\" $call --no-total --begin '$beginytd' --end '$endytd' --register-format=\"%(date) %(payee) |||||%(display_amount)|||||%(note)|||||\\n\" --balance-format=\"%(account)|||||%(display_total)\\n\" > /tmp/loutput\n");
	system_print($cmd);
	$data['ytd'] = explode("\n",file_get_contents("/tmp/loutput"));
	unlink("/tmp/loutput");
	$retval = array();
error_reporting(0);
	foreach ($data['period'] as $period) {
		$x = explode("|||||",$period); 
		if (!isset($x[1]) || $x[1] == 0) // if no amount or zero amount skip
			continue;
//$x[0] = explode(":",$x[0])[1];
		$retval[$x[0]]['period'] += $x[1];
	}
	foreach ($data['ytd'] as $period) {
		$x = explode("|||||",$period);
		if (!isset($x[1]) || $x[1] == 0)
			continue;
//$x[0] = explode(":",$x[0])[1];
		$retval[$x[0]]['ytd'] += $x[1];
	}

error_reporting(E_ALL);
//echo "<pre>";print_r($retval);die();
return $retval;
}
function browse($datafile,$call,$begin= '2018-05-01', $end = '2018-05-31',$beginytd='2018-01-01',$endytd='2018-05-31',$tpath,$page) {
	$tpath= urlencode($tpath);
echo "lol";
	//$call = str_replace("/","\\/",$call); -- DO IT SOMEREWHERE ELSE WILL FUCK UP REGEX
        //$begin = $_COOKIE['begin'];$end = $_COOKIE['end']; $beginytd= $_COOKIE['beginytd'];$endytd = $_COOKIE['endytd'];
	$nldelim = "-------!!!!!!!!----";
        $cmd = ("LEDGER_DEPTH=99 LEDGER_SORT=date LEDGER_BEGIN=$begin LEDGER_END=$end test=3 ledger --depth=5 --recursive-aliases -B --no-total -f \"$datafile\" $call --begin '$begin' --end '$end' --register-format=\"%(date)|||||%(payee) |||||%(display_amount)|||||%(note)|||||%(total)|||||%(account)$nldelim\" --balance-format=\"%(account)|||||%(display_total)$nldelim\" > /tmp/loutput");
        system_print($cmd);
        $data['period'] = explode($nldelim,file_get_contents("/tmp/loutput"));
        $cmd = ("LEDGER_DEPTH=99 LEDGER_SORT=date LEDGER_BEGIN=$beginytd LEDGER_END=$endytd test=4 ledger --depth=5 --recursive-aliases -B --no-total -f \"$datafile\" $call --begin '$beginytd' --end '$endytd' --register-format=\"%(date)|||||%(payee) |||||%(display_amount)|||||%(note)|||||%(total)|||||%(account)$nldelim\" --balance-format=\"%(account)|||||%(display_total)$nldelim\" > /tmp/loutput");
        system_print($cmd);
        $data['ytd'] = explode($nldelim,file_get_contents("/tmp/loutput"));

        $cmd = ("LEDGER_DEPTH=99 LEDGER_SORT=date LEDGER_BEGIN='1970-01-01' LEDGER_END='2099-12-31' test=4 ledger --depth=5 --recursive-aliases -B --no-total -f \"$datafile\" $call --begin '1970-01-01' --end '2099-12-31' --register-format=\"%(date)|||||%(payee) |||||%(display_amount)|||||%(note)|||||%(total)|||||%(account)$nldelim\" --balance-format=\"%(account)|||||%(display_total)$nldelim\" > /tmp/loutput");
        system_print($cmd);
        $data['all'] = explode($nldelim,file_get_contents("/tmp/loutput"));



	if ($page == "browse_period")
		$d = $data['period'];
	else if ($page  == "browse_ytd")
		$d = $data['ytd'];
	else
		$d = $data['all'];
/*	echo "<table style='position: fixed; display:block' border=1>";
        echo "<tr><td>Date</td><td>payee</td><td>Amount</td><td>Total</td>";
	echo "</table>";
*/
	echo "<table class=\"table table-striped table-dark table-bordered\">";
	echo "<tr><td>Account</td><td>Date</td><td>payee</td><td>Amount</td><td>assTotal</td></tr>";
	foreach ($d as $row) {
		$columns = explode("|||||",$row);
/*		if (!isset($columns[4]))
			continue;
		if (!isset($columns[5]))
			$columns[5] = "N/A";
*/
		$link = "";
		$linkend = "";
		if (stristr($columns[3],"Filename:")) {
			$fn_url = urlencode($columns[3]);
			$link = "<a href=\"key_html.php?tpath=$tpath&page=edit&fn=$fn_url\">";
			$linkend = "</a>";
		}
		$columns[2] = pv($columns[2]);
		$columns[4] = pv($columns[4]);
		if ($columns[2] == 0) continue;
		echo "<tr><td>$link $columns[5] $linkend</td><td>$link $columns[0] $linkend</td><td>$link $columns[1] $linkend</td><td><p align=right>$link $columns[2] $linkend</p></td><td><p align=right>$link $columns[4] $linkend</p></td></tr>\n";
	}
	echo "</table>";
}
function toplevels($datafile,$begin = '2018-05-01',$end = '2030-01-01') {
	$cmd = ("ledger --depth=3 --recursive-aliases -B -f $datafile bal --depth 1 --begin '1970/1/1' --end '2099/12/31' --balance-format='%(account)\n'|grep -v Resultatoverførsel> /tmp/loutput\n");
	system_print($cmd);
	$retval = explode("\n",file_get_contents("/tmp/loutput"));
	unlink ("/tmp/loutput");
	usort($retval,"tla_sort");
	return ($retval);
}
function tla_sort($a,$b) {
	$values = array('Indtægter'=>1,'Udgifter'=>2,'Aktiver'=>3,'Passiver'=>4,'Egenkapital'=>5);
	if (isset($values[$a]))
		$avalue = $values[$a];
	else
		$avalue = 99;
	if (isset($values[$b]))
		$bvalue = $values[$b];
	else
		$bvalue = 99;
	return $avalue > $bvalue;
}
function system_print($cmd) {
//	echo "running: '$cmd'\n<br>";
	return system($cmd);
}
?>
