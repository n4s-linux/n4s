<?php
require_once("/svn/svnroot/Applications/accountheader.php");
echo "From Account (enter for all): ";
$fromacc = trim(fgets(STDIN));
if ($fromacc == "") $fromacc = 0;
echo "To Account (enter for all): ";
$toacc = trim(fgets(STDIN));
if ($toacc== "") $toacc= 9999999;
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$tpath = getenv("tpath");
if ($tpath == "") die("skatexport requires tpath\n");
require_once("/svn/svnroot/Applications/fzf.php");
$map = getmap();
$stdkto = getstandardkontoplan();
$t = gettransactions();
$newtrans = rewritetrans($t);
ob_start();
bootstrap();
$output = ob_get_clean();
ob_start();
printheader("Ekstern rapportering");
echo "<br>";
$output .= ob_get_clean();
$output .= saldobalance($newtrans,$stdkto);
if (getenv("nospecs") != 1){
	ob_start();
	kontokort($newtrans,$stdkto);
	$output.= "<p style=\"page-break-before: always\"></p>";
	$output .= ob_get_clean();
}
$op = exec("whoami");
file_put_contents("/home/$op/tmp/stdkto.html",$output);
function kontokort($newtrans,$stdkto) {
	global $output;
	$output.= "<h1><center>Kontokort</center></h1>";
	$konti = array();
	foreach ($newtrans as $nt) {
		$konti[$nt["Account"]][] = $nt;	
	}
	ksort($konti,SORT_NUMERIC);
	foreach ($konti as $curkonto => $transactions) {
		if (!is_numeric($curkonto)) continue;
		$output.= "<b>$curkonto - ";
		foreach ($stdkto as $c) {
			if ($curkonto == $c[0]) $output.= $c[2];
		}
		$output.= "</b><br>";
		$output.= "<table class='table table-striped' width=700>";
		kk($curkonto,$transactions);
		$output.= "</table>";
	}
}
function kk($curkonto,$transactions) {
	global $output;
	$bal = 0;
	foreach ($transactions as $curtrans) {
		$bal += $curtrans['Amount'];
		$trimmed = substr($curtrans["Tekst"],0,25);
		$pretty=number_format($curtrans['Amount'],2,",",".");
		$prettybal=number_format($bal,2,",",".");
		$output.= "<tr><td width=70>$curtrans[Date]</td><td width=70>$curtrans[Bilag]</td><td width=250>$trimmed</td><td width=70><p align=right>$pretty</p></td><td width=70><p align=right>$prettybal</p></td></tr>\n";
	}
}
function bootstrap() {?><meta charset=utf8><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"><?php }
$nulkontrol = 0;
function printheader_ktoplan($header) {
       return "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td><b>" . $header[2]."</b></td><td>&nbsp;</td></tr>\n";
}

function saldobalance($t,$stdkto) {
	$output = "";
	$output.= "<h1><center>Saldobalance</center></h1>";
	global $fromacc; global $toacc;
	global $nulkontrol;
	$output.= "<table class='table table-striped' width=700>";
	$curheader="";
	foreach ($stdkto as $curkto) {
		if ($curkto[0] < $fromacc || $curkto[0] > $toacc) continue;
		if ($curkto[0] == 99999) continue;
		if ($curkto[1] == "Overskrift") $curheader = printheader_ktoplan($curkto);
		else if ($curkto[1] == "" ) {
			$p = printaccbal($curkto);
			if ($p != "" ) {
				$output.= $curheader;
				$curheader = "";
				$output.= $p;
			}
		}
		else if (substr($curkto[1],0,4) == "Sum ") {
			$x = explode(" ",$curkto[1])[1];
			$x = explode("-",$x);
			$p = printsumbal($curkto,$x[0],$x[1]);
			if ($p != "") {
				$output.= $curheader;
				$curheader = "";
				$output.= $p;
			}
		}
		else {
			$output.= "Unhandled:\n";
			print_r($curkto);die();
		}
	}
	$prettynul=number_format($nulkontrol,2,".",",");
	$output.= "<tr><td>&nbsp;</td><td><b><u>Nulkontrol</u></b></td><td><b><u><p align=right>$prettynul</u></b></p></td></tr>";
	$output.= "</table>";
	return $output;
}
function printsumbal($curkto,$begin,$end) {
	ob_start();
	$konto = trim(explode("\t",$curkto[0])[0]);
	global $newtrans;
	$bal = 0;
	foreach ($newtrans as $curtrans) {
		if ($curtrans['Account']< $begin || $curtrans['Account']> $end) continue;
		$bal += $curtrans['Amount'];
	}
	if (intval($bal) != 0) {
		$pretty = number_format($bal,2,".",",");
		echo "<tr><td><b><u>$konto</b></u></td><td><b><u>$curkto[2]</b></u></td><td><b><u><p align=right>$pretty</p></b></u></td></tr>\n";
	}
	return ob_get_clean();
}
function printaccbal($curkto) {
	global $nulkontrol;
	$konto = trim(explode("\t",$curkto[0])[0]);
	global $newtrans;
	$bal = 0;
	$output="";
	foreach ($newtrans as $curtrans) {
		if ($curtrans['Account'] == $konto) $bal += $curtrans['Amount'];
	}
	$nulkontrol += $bal;
	if (intval($bal) != 0) {
		$pretty = number_format($bal,2,".",",");
		$output.= "<tr><td>$konto</td><td>$curkto[2]</td><td><p align=right>$pretty</p></td></tr>\n";
	}
	return $output;
}
function rewritetrans($t) {
	global $map;
	$retval = array();
	foreach ($t as $curt) {
		if (!isset($curt[5])) continue;
		$newt = array();
		$newt['Date'] = $curt[0];
		$newt['Tekst'] = $curt[2];
		$newt['Bilag'] = $curt[1];
		$newt['Account'] = trim(explode("\t",changeacc($curt[3]))[0]);
		$newt['Amount'] = $curt[5];
		$newt['Tags'] = $curt[7];
		$retval[] = $newt;
	}
	return $retval;
}
function changeacc($acc) {
	global $map;
	global $tpath;
	if (!isset($map[$acc])) {
		$map[$acc] = selectaccount($acc);
		file_put_contents("/data/regnskaber/.skatmap.dat",json_encode($map,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		return $map[$acc];
	}
	else {
		return $map[$acc];
	}
}
function selectaccount($acc) {
	global $stdkto;
	$fzf = "";
	$overskrift = "";
	foreach ($stdkto as $curkto) {
		if ($curkto[1] == "Overskrift") { $overskrift = $curkto[2];continue;}
		if (substr($curkto[1],0,4) == "Sum ") continue;
		$fzf .= $curkto[0] . "\t" . str_pad(substr($overskrift,0,55),55," ",STR_PAD_RIGHT) . "\t".$curkto[2] . "\n";
	}
	$valg = fzf($fzf,"Skatte-eksport - ÅRL Model: Vælg konto for $acc");
	if ($valg == "") die("Afbrudt ved valg af konto\n");
	else return $valg;
}
function gettransactions() {
	ob_start();
	system("color=none php /svn/svnroot/Applications/newl.php csv");
	$str = ob_get_clean();
	$x = str_getcsv($str,"\n");
	foreach ($x as $curx) {
		if (!isset($curx[3])) continue;
		$retval[] = str_getcsv($curx);
	}
	return $retval;
}
function getmap() {
	global $tpath;
	$mapfile = "/data/regnskaber/.skatmap.dat";
	if (!file_exists($mapfile)) {
		$map = array();
		file_put_contents($mapfile,json_encode(array()));
		return $map;
	}
	else {
		return json_decode(file_get_contents($mapfile),true);
	}
}
function getstandardkontoplan() {
	$standardkontoplan = array();
	if (($handle = fopen("/svn/svnroot/Libraries/2023-01-31-Standardkontoplan.csv", "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$standardkontoplan[] = $data;
	    }
	    fclose($handle);
	}
	return $standardkontoplan;
}
?>

