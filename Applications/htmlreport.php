<?php
$op = exec("whoami");
$tpath = getenv("tpath");
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$realend = date("Y-m-d", strtotime("$end -1 day"));
printheader();
$notes = array();
$notenames = array();
$notecount = 0;
$sectionsres = array('Indtægter','Udgifter','Resultatdisponering');
$sectionsbal = array('Aktiver','Egenkapital','Passiver','Fejlkonto');
$darray = getdata($begin,$end);
//if (hasfejl($darray)) showfejlkonto();
echo "<center><h2>Resultatopgørelse $begin - $realend</center></h2><br>";
foreach ($sectionsres as $cursect) 
	printsection($darray,$cursect);
echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
echo "<center><h2>Balance $begin - $realend</h2></center><br>";
foreach ($sectionsbal as $cursect) 
	printsection($darray,$cursect);
echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
printnotes();
unlink("/home/$op/tmp/kontokort.html");
foreach (array('Indtægter','Udgifter','Aktiver','Egenkapital','Passiver','Fejlkonto') as $curf) {
	echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
	echo printfullspec($darray,$curf);
}
function hasfejl($darray) {
	$saldo = 0;
	foreach ($darray as $curkonto)
		if (stristr($curkonto['Konto'],'fejl')) $saldo += $curkonto['Beløb'];
	if (intval($saldo) == 0) return true; else return false;

}
function pagebreak() {
?>
<P style="page-break-before: always"> 
<center><h2>Noter</h2></center>
<?php
}
function printfullspec($darray,$filter) {
	global $op;
	global $begin; global $realend;
	global $end;
	ob_start();
	if ($filter == "Indtægter") printheader("Kontokort","landscape");
	echo "<center><h2>Kontospecifikationer $filter - $begin - $realend</h2></center>";
	echo "<table class=\"table table-striped table-sm\">";
	$cols = array("Date","Reference","Tekst","Header","Subacc","Subsub","Beløb");
	$saldo = 0;
	$ksaldo = array();
	echo "<tr>";
	foreach ($cols as $curcol) echo "<td><b>$curcol</b></td>";
	echo "<td><b><p align=right>Konto</b></td><td><b><p align=right>Total</p></b></td>";
	echo "</tr>";
	$firstfejl = true;
	foreach ($darray as $curtrans) {
		if ($curtrans['Header'] != $filter) continue;
		echo "<tr>";
		$orgb = $curtrans['Beløb'];
		foreach ($cols as $curcol) {
			echo "<td>";
			//if ($curcol == "Date") $curtrans[$curcol] = date("d-m",strtotime($curtrans[$curcol]));
			if ($curcol == "Beløb") $curtrans[$curcol] = prettynum($curtrans[$curcol]);
			if ($curcol == "Tekst") $curtrans[$curcol] = substr($curtrans[$curcol],0,10);
			if ($curcol == "Reference") $curtrans[$curcol] = substr($curtrans[$curcol],0,6);
			if ($firstfejl == true && stristr($curtrans['Konto'],'fejl')) {echo "<a name=fejl>";}
			echo $curtrans[$curcol];
			if ($firstfejl == true && stristr($curtrans['Konto'],'fejl')) {echo "</a>";$firstfejl = false;}
			echo "</td>";
		}
		$saldo += $orgb;
		$ksaldo[$curtrans['Konto']] += $orgb;
		$pksaldo = prettynum($ksaldo[$curtrans['Konto']]);
		$psaldo = prettynum($saldo);
		echo "<td>$pksaldo</td>";
		echo "<td>$psaldo</td>";
		echo "</tr>";
	}

	echo "</table>";
	$data = ob_get_clean();
	file_put_contents("/home/$op/tmp/kontokort.html",$data,FILE_APPEND);
	return "";
	return $data;
}
function printnotes() {
	global $notes;
	global $notenames;
	$first = true;
	foreach ($notes as $key => $val) {
		if ($first) {pagebreak();$first = false;}
		$nn = $notenames[$key];
		echo "<a name='note$key'><h3>$key - $nn </h3></a>\n";
		$sum = 0;
		foreach ($val as $curnote) {
			echo "<table class=\"table table-striped\" width=800>";
			foreach ($curnote as $key => $val) {
				if (intval($val) == 0) continue; // dont print blank note lines
				$sum += $val;
				$pv = prettynum($val);
				echo "<tr><td>$key</td><td>$pv</td></tr>";
			}
			$sum = prettynum($sum);
			echo "<tr><td style='background: white;'><b><u>$nn total</u></b></td><td style='background:white'><b><u>$sum</u></b></td></tr>";
			echo "</table><br><br>";
		}
	}
}

function getdata($begin,$end) {
	$op = exec("whoami");
	$cmd = "LEDGER_BEGIN=$begin LEDGER_END=$end php /svn/svnroot/Applications/key.php ledger csv -S account,date,payee > /home/$op/htmlreport.csv";
	exec($cmd);
	$row = 1;
	$darray = array();
	if (($handle = fopen("/home/$op/htmlreport.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$d['Date'] = $data[0];
			$d['Reference'] = $data[1];
			$d['Tekst'] = $data[2];
			$d['Konto'] = $data[3];
			$d['Beløb'] = $data[5];
			$d['Header'] = explode(":",$data[3])[0];
			$d['Subacc'] = explode(":",$data[3])[1];
			error_reporting(0);$d['Subsub'] = explode(":",$data[3])[2];error_reporting(E_ALL);
			array_push($darray,$d);
		}
	fclose($handle);
	}
	return $darray;
}
	function getnote($d,$key) {
		global $notes;
		global $notecount;
		global $notenames;
		$curnote = array();
		error_reporting(0);
		foreach ($d as $curd) {
			if ($curd['Subacc'] == $key && isset($curd['Subsub']))
				$curnote[$curd['Subacc']][$curd['Subsub']] += $curd['Beløb'];
			else if ($curd['Subacc'] == $key)
				$curnote[$curd['Subacc']][$key] += $curd['Beløb'];
		}
		error_reporting(E_ALL);
		if (count(reset($curnote)) > 1) {
			$notecount++;
			$notes[$notecount] = $curnote;
			$notenames[$notecount] = key(($curnote));
			return $notecount;
		}
		else 
			return false;
	}
	function printsection($d,$header) {
		global $curnote;
		$bal = array();
		foreach ($d as $curd) {
			if ($curd['Header'] != $header) continue;
			error_reporting(0);
			$bal[$curd['Subacc']] += $curd['Beløb'];
			error_reporting(E_ALL);
		}
		echo "<table class=\"table table-striped\" width=800>";
		$upper = mb_strtoupper($header);
		echo "<thead><tr><th style='background: white'><p align=left>$upper</p></th><th style='background: white'><p align=right>Beløb</p></th></th></tr>";
		echo "<tbody>";
		$total = 0;
		foreach ($bal as $key => $val) {
			if (intval($val) == 0) continue;
			$total += $val;
			$val = prettynum($val);
			$note = getnote($d,$key);
			if ($note != false)
				$note = "<a href=#note$note>*$note</a>";
			echo "<tr><td width=150>$key $note</td><td width=50>$val</td></tr>";
		}
		$ptotal = prettynum($total);
		echo "<tr><td style='background: white;'><b><u>$header i alt</b></u></td><td style='background:white'><b><u>$ptotal</u></b></td></tr>";
		echo "</tbody></table><br>";
	}
	function prettynum($a) { return "<p align=right>" . number_format($a,0,",",".") . "</p>";}
?>
<?php
function printheader($parameter = "Saldobalance",$orientation="landscape") {
global $tpath;
global $begin;
global $end;
global $realend;
?><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<body style="font-family: Courier" size=8>
<html charset=utf8>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
@media print {
    table tbody tr td:before,
    table tbody tr td:after {
        content: "";
        height: 4px;
        display: block;
    }
@media print{@page {size: <?php echo $orientation;?>}}
</style>
<?php 

echo "<p align=right>";
echo "København " . date("Y-m-d") . "</p><center><br>";
for ($i = 0;$i<12;$i++)echo "<br>";
require_once("/svn/svnroot/Applications/prettyname.php");
echo "<br><b>$parameter</b><br>" . prettyname($tpath) . "</center>";
echo "<center><br><b>Periode:</b><br>";
$realend = date("Y-m-d", strtotime("$end -1 day"));
echo "$begin - $realend";
echo "</center>";
echo "<p style=\"page-break-after: always;\">&nbsp;</p>";

}
function shorten($s) { return $s;}
?>
<?php
function showfejlkonto() {?>
<div class="alert alert-danger" role="alert">
  Der er poster på fejkontoen - det betyder at vi mangler oplysninger se venligst kontospecifikationerne
</div>
<?php }?>
