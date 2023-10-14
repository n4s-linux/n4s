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
$sectionsbal = array('Aktiver','Egenkapital','Passiver');
$darray = getdata($begin,$end);
//if (hasfejl($darray)) showfejlkonto();
echo "<center><h5>Resultatopgørelse $begin - $realend</center></h5><br>";
foreach ($sectionsres as $cursect) 
	printsection($darray,$cursect);
if (hasfejl($darray))
	printsection($darray,"Fejlkonto");

echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
echo "<center><h5>Balance $begin - $realend</h5></center><br>";
foreach ($sectionsbal as $cursect) 
	printsection($darray,$cursect);
echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
printnotes();
unlink("/home/$op/tmp/kontokort.html");
file_put_contents("/home/$op/tmp/nøgletal.html",getnøgletal($darray));


foreach (array('Indtægter','Udgifter','Aktiver','Egenkapital','Passiver') as $curf) {
	echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
	echo printfullspec($darray,$curf);
}
if (hasfejl($darray)) {
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
	echo "<center><h3><font style='background:yellow'>Kontospecifikationer $filter - $begin - $realend</font></h3></center>";
	echo "<table class=\"table table-based table-sm\">";
	$cols = array("Date","Reference","Tekst","KontoN1","KontoN2","Beløb");
	$saldo = 0;
	$ksaldo = array();
	echo "<tr>";
	foreach ($cols as $curcol) {
		$w = getw($curcol);
		echo "<td width=$w><b>$curcol</b></td>";
	}
	echo "<td><b><p align=right>Konto</b></td><td><b><p align=right>Total</p></b></td>";
	echo "</tr>";
	$firstfejl = true;
	foreach ($darray as $curtrans) {
		$c = "white";
		if (substr($curtrans['Reference'],0,4) == "CSV-") {
			$c = "gray";$curtrans["Reference"] = "BANK";
		}
		else $curtrans[$curcol] = substr($curtrans[$curcol],0,8);
		if ($curtrans['Header'] != $filter) continue;
		echo "<tr>";
		$orgb = $curtrans['Beløb'];
		foreach ($cols as $curcol) {
			$w = getw($curcol);
			echo "<td width=$w style='background-color:$c;'>";
			//if ($curcol == "Date") $curtrans[$curcol] = date("d-m",strtotime($curtrans[$curcol]));
			if ($curcol == "Beløb") $curtrans[$curcol] = prettynum($curtrans[$curcol]);
			if ($curcol == "Tekst") $curtrans[$curcol] = substr($curtrans[$curcol],0,25);
			if ($firstfejl == true && stristr($curtrans['Konto'],'fejl')) {echo "<a name=fejl>";}
			echo $curtrans[$curcol];
			if ($firstfejl == true && stristr($curtrans['Konto'],'fejl')) {echo "</a>";$firstfejl = false;}
			echo "</td>";
		}
		$saldo += $orgb;
		$ksaldo[$curtrans['Konto']] += $orgb;
		$pksaldo = prettynum($ksaldo[$curtrans['Konto']]);
		$psaldo = prettynum($saldo);
		echo "<td style='background-color:$c'>$pksaldo</td>";
		echo "<td style='background-color:$c'>$psaldo</td>";
		echo "</tr>";
	}

	echo "</table><br>";
	echo pb();
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
		echo "<div><a name='note$key'><h3>$key - $nn </h3></a>\n";
		$sum = 0;
		foreach ($val as $curnote) {
			echo "<table class=\"table table-based \" width=800>";
			foreach ($curnote as $key => $val) {
				if (intval($val) == 0) continue; // dont print blank note lines
				$sum += $val;
				$pv = prettynum($val);
				echo "<tr><td>$key</td><td>$pv</td></tr>";
			}
			$sum = prettynum($sum);
			echo "<tr><td style='background: white;'><b><u>$nn total</u></b></td><td style='background:white'><b><u>$sum</u></b></td></tr>";
			echo "</table></div>";
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
			$d['KontoN1'] = explode(":",$data[3])[1];
			error_reporting(0);$d['KontoN2'] = explode(":",$data[3])[2];error_reporting(E_ALL);
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
			if ($curd['KontoN1'] == $key && isset($curd['KontoN2']))
				$curnote[$curd['KontoN1']][$curd['KontoN2']] += $curd['Beløb'];
			else if ($curd['KontoN1'] == $key)
				$curnote[$curd['KontoN1']][$key] += $curd['Beløb'];
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
			$bal[$curd['KontoN1']] += $curd['Beløb'];
			error_reporting(E_ALL);
		}
		echo "<table class=\"table table-based \" width=800>";
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
				$note = "\n<a href=#note$note> - Note $note</a>";
			echo "<tr><td width=150>$key $note </td><td width=50>$val</td></tr>";
		}
		$ptotal = prettynum($total);
		echo "<tr><td style='background: white;'><b><u>$header i alt</b></u></td><td style='background:white'><b><u>$ptotal</u></b></td></tr>";
		echo "</tbody></table>";
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
<html charset=utf8>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
@media print {
<?php if ($parameter == "Saldobalance") {?>
	div {
    break-inside: avoid;
}
	table {
    break-inside: avoid;
	}
<?php }?>
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
<?php }
function getw($col) {
	if ($col == "KontoN1")
		return 200;
	if ($col == "Beløb")
		return 50;
	if ($col == "Date")
		return 100;
	if ($col == "Reference")
		return 100;
	if ($col == "Tekst")
		return 400;
	return 100;
}
function pb() {
return "<p style=\"page-break-after: always;\">&nbsp;</p>";
}
function getnoteicon() {
	system("cp /svn/svnroot/png/edit.png ~/tmp/note.png");
	return "<img width=25 height=25 src=note.png>";
}

function dækningsbidrag($darray) {
	$bal = 0;
	$i = "Indtægter:";
	$u = "Udgifter:Direkte";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i || substr($curtrans['Konto'],0,strlen($u)) == $u)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}
function resultat($darray) {
	$bal = 0;
	$i = "Indtægter:";
	$u = "Udgifter:";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i || substr($curtrans['Konto'],0,strlen($u)) == $u)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}


function egenkapital($darray) {
	$bal = 0;
	$i = "Egenkapital:";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}
function aktiver($darray) {
	$bal = 0;
	$i = "Aktiver:";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}

function kortfristetgæld($darray) {
	$bal = 0;
	$i = "Passiver:";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}

function omsætningsaktiver($darray) {
	$bal = 0;
	$i = "Aktiver:Omsætningsaktiver";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}
function omsætning($darray) {
	$bal = 0;
	$i = "Indtægter:";
	foreach ($darray as $curtrans) {
		if (substr($curtrans['Konto'],0,strlen($i)) == $i)
			$bal += $curtrans['Beløb'];
	}
	return $bal;
}
function getnøgletal($darray)  {
	ob_start();
	printheader("Nøgletal");
	echo "<table class=\"table\">";
	$omsætning = omsætning($darray);
	$resultat = resultat($darray);
	$ebit = prettynum($resultat / $omsætning * 100);
	$dækningsgrad = prettynum(dækningsbidrag($darray) / omsætning($darray) * 100);
	$likviditetsgrad = prettynum(omsætningsaktiver($darray) / kortfristetgæld($darray) * 100);
	$afkastningsgrad = prettynum(resultat($darray) * 100 / aktiver($darray));
	$soliditet =  prettynum(-egenkapital($darray) / aktiver($darray) * 100);
	$ekforrent = prettynum(resultat($darray) * 100 / egenkapital($darray));
	echo "<tr><td>EBIT (Overskudsgrad)<br>Resultat / Omsætning </td><td>$ebit</td></tr>";	
	echo "<tr><td>Dækningsgrad<br>Dækningsbidrag / Omsætning</td><td>$dækningsgrad</td></tr>";	
	echo "<tr><td>Likviditetsgrad<br>Omsætningsaktiver / kortfristet gæld</td><td>$likviditetsgrad</td></tr>";	
	echo "<tr><td>Soliditet<br>Egenkapital / Aktiver</td><td>$soliditet</td></tr>";	
	echo "<tr><td>Egenkapitalens forretning<br>Resultat / Egenkapital</td><td>$ekforrent</td></tr>";	
	
	echo "</table>";
	return ob_get_clean();
}
?>
