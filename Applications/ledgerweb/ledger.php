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

function renledgerMultiPeriods($datafile = null,$call = 'bal', $periods = array(),$depth=3)  {
//	$begin= '2018-05-01', $end = '2038-05-31',$beginytd='2018-01-01',$endytd='2038-05-31
	$call = str_replace("/",'"\/"',$call);
	$retval = array();
	foreach ($periods as $k => $period) {
		$begin = $period[0];
		$end = $period[1];

		$cmd = ("LEDGER_BEGIN=$begin LEDGER_END=$end LEDGER_DEPTH=$depth test=1 ledger --depth=$depth --recursive-aliases -B -f \"$datafile\" $call --no-total --begin '$begin' --end '$end' --register-format=\"%(date) %(payee) |||||%(display_amount)|||||%(note)|||||\\n\" --balance-format=\"%(account)|||||%(display_total)\\n\" > /tmp/loutput\n");
		system_print($cmd);
		$data['period'] = explode("\n",file_get_contents("/tmp/loutput"));
		foreach ($data['period'] as  $p) {

/*

transactions_olsensrevision / ledgerweb]$ ledger --recursive-aliases -B  --no-total --register-format"=%(date) %(payee) |||||%(display_amount)|||||%(note)|||||\n" --balance-format="%(account)|||||%(display_total)\n" -f /home/joo/regnskaber/transactions_olsensrevision/curl b aktiver
Aktiver|||||879987.26
Aktiver:Anlægsaktiver:Immaterielle anlægsaktiver:Tilgang|||||258421.09
Aktiver:Driftsmidler:Primo|||||51486.17
Aktiver:Forudbetalte poster|||||5775.74
Aktiver:Indretning|||||26226.87
Aktiver:Indretning:Primo|||||25180.71
Aktiver:Indretning:Tilgang|||||1046.16
Aktiver:Likvider:Bank|||||1844.91
Aktiver:Mellemregning med holding|||||195145.27
Aktiver:Omsætningsaktiver|||||319397.21
Aktiver:Omsætningsaktiver:Debitorer:2019|||||48729.66
Aktiver:Omsætningsaktiver:Depositum Rosengården|||||32340
Aktiver:Omsætningsaktiver:Igangværende arbejder|||||50000
Aktiver:Omsætningsaktiver:Varelager|||||188327.55
Aktiver:Sygedagpenge tilgode|||||21690


*/
			$x = explode("|||||",$p);
			if (!isset($x[1])) // if no amount or zero amount skip
				continue;
//$x[0] = explode(":",$x[0])[1];
			$retval[$x[0]][$k] += $x[1];
		}

	}

	unlink("/tmp/loutput");

//	$retval = array();


file_put_contents("/tmp/ledger_output.json",json_encode($retval,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	return $retval;

}


function browse($datafile,$call,$begin= '2018-05-01', $end = '2018-05-31',$beginytd='2018-01-01',$endytd='2018-05-31',$tpath) {


	$tpath= urlencode($tpath);
	//$call = str_replace("/","\\/",$call); -- DO IT SOMEREWHERE ELSE WILL FUCK UP REGEX
        //$begin = $_COOKIE['begin'];$end = $_COOKIE['end']; $beginytd= $_COOKIE['beginytd'];$endytd = $_COOKIE['endytd'];
	$nldelim = "-------!!!!!!!!----";

	switch ($_GET['page']) {
		case "browse_period":
			$cmd = ("LEDGER_DEPTH=99 LEDGER_SORT=account,date LEDGER_BEGIN=$begin LEDGER_END=$end test=3 ledger --depth=5 --recursive-aliases -B --no-total -f \"$datafile\" $call --begin '$begin' --end '$end' --register-format=\"%(date)|||||%(payee) |||||%(display_amount)|||||%(note)|||||%(total)|||||%(account)|||||%(tag('FilesAttached'))$nldelim\" --balance-format=\"%(account)|||||%(display_total)$nldelim\" > /tmp/loutput");
			system_print($cmd);
			$d = explode($nldelim,file_get_contents("/tmp/loutput"));
			break;
		case "browse_ytd":
			$cmd = ("LEDGER_DEPTH=99 LEDGER_SORT=account,date LEDGER_BEGIN=$beginytd LEDGER_END=$endytd test=4 ledger --depth=5 --recursive-aliases -B --no-total -f \"$datafile\" $call --begin '$beginytd' --end '$endytd' --register-format=\"%(date)|||||%(payee) |||||%(display_amount)|||||%(note)|||||%(total)|||||%(account)|||||%(tag('FilesAttached'))$nldelim\" --balance-format=\"%(account)|||||%(display_total)$nldelim\" > /tmp/loutput");
			system_print($cmd);
			$d = explode($nldelim,file_get_contents("/tmp/loutput"));
			break;
		case 'browse_all':
			$beginTotal='1970-01-01'; $endTotal='2099-12-31';
			$cmd = ("LEDGER_DEPTH=99 LEDGER_SORT=account,date LEDGER_BEGIN=$beginTotal LEDGER_END=$endTotal test=4 ledger --depth=5 --recursive-aliases -B --no-total -f \"$datafile\" $call --begin '$beginTotal' --end '$endTotal' --register-format=\"%(date)|||||%(payee) |||||%(display_amount)|||||%(note)|||||%(total)|||||%(account)|||||%(tag('FilesAttached'))$nldelim\" --balance-format=\"%(account)|||||%(display_total)$nldelim\" > /tmp/loutput");
			system_print($cmd);
			$d = explode($nldelim,file_get_contents("/tmp/loutput"));
			break;
	}

/*	echo "<table style='position: fixed; display:block' border=1>";
        echo "<tr><td>Date</td><td>payee</td><td>Amount</td><td>Total</td>";
	echo "</table>";
*/
	echo "<table class=\"table table-striped table-dark table-bordered transactions-table\">";
	echo "<tr><td>Account</td><td>Date</td><td>payee</td><td>Amount</td><td>Total</td></tr>";
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
//			$link = "<a class=\"modal-link\" href=\"key_html.php?tpath=$tpath&page=edit&fn=$fn_url\">";


			//open transaction in new/old form
//			if(!$development){

			$link = "<a class=\"modal-link\" tabindex=\"-1\"  data-toggle=\"modal\" data-target=\"#windowEditModal\" data-url=\"key_html.php?tpath=$tpath&page=edit&fn=$fn_url\">";
//			} else {
//				$link = "<a class=\"modal-link\" tabindex=\"-1\"  data-toggle=\"modal\" data-target=\"#windowEditModalNew\" data-url=\"key_html.php?tpath=$tpath&page=edit&fn=$fn_url\">";
//			}


			$linkend = "</a>";
		}
		$columns[2] = pv($columns[2]);
		$columns[4] = pv($columns[4]);
		if ($columns[2] == 0) continue;

		$fileIcon = "";
		if(isset($columns[6]) && $columns[6] > 0){
			$fileIcon = "<span class=\"file-zip-icon\" aria-hidden=\"true\"></span>";
		}

		echo "<tr><td>$link $columns[5] $linkend $fileIcon</td><td>$link $columns[0] $linkend</td><td>$link $columns[1] $linkend</td><td><p align=right>$link $columns[2] $linkend</p></td><td><p align=right>$link $columns[4] $linkend</p></td></tr>\n";
	}
	echo "</table>";
}
function toplevels($datafile,$begin = '2018-05-01',$end = '2030-01-01') {
echo $datafile;
	$cmd = ("ledger --depth=3 --recursive-aliases -B -f $datafile bal --depth 1 --begin '1970/1/1' --end '2099/12/31' --balance-format='%(account)\n'|grep -v Resultatoverførsel> /tmp/loutput\n");
	system_print($cmd);
	$retval = explode("\n",file_get_contents("/tmp/loutput"));
	unlink ("/tmp/loutput");
	usort($retval,"tla_sort");
	return ($retval);
}
function tla_sort($a,$b) {
	$values = array('Indtægter'=>1,'Udgifter'=>2,'Resultatdisponering'=>3,'Aktiver'=>4,'Passiver'=>5,'Egenkapital'=>6);
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
