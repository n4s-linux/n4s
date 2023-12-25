<?php
$tpath = getenv("tpath");
require_once("/svn/svnroot/Applications/fzf.php");
$map = getmap();
$stdkto = getstandardkontoplan();
$t = gettransactions();
$newtrans = rewritetrans($t);
bootstrap();
saldobalance($newtrans,$stdkto);
kontokort($newtrans,$stdkto);
function kontokort($newtrans,$stdkto) {
	echo "<h1>Kontokort</h1>";
	$konti = array();
	foreach ($newtrans as $nt) {
		$konti[$nt["Account"]][] = $nt;	
	}
	ksort($konti,SORT_NUMERIC);
	foreach ($konti as $curkonto => $transactions) {
		if (!is_numeric($curkonto)) continue;
		echo "<b>$curkonto - ";
		foreach ($stdkto as $c) {
			if ($curkonto == $c[0]) echo $c[2];
		}
		echo "</b><br>";
		echo "<table class='table-sm' border=1 width=700>";
		kk($curkonto,$transactions);
		echo "</table>";
	}
}
function kk($curkonto,$transactions) {
	$bal = 0;
	foreach ($transactions as $curtrans) {
		$bal += $curtrans['Amount'];
		$trimmed = substr($curtrans["Tekst"],0,25);
		$pretty=number_format($curtrans['Amount'],2,".",",");
		$prettybal=number_format($bal,2,".",",");
		echo "<tr><td width=70>$curtrans[Date]</td><td width=150>$trimmed</td><td width=70><p align=right>$pretty</p></td><td width=70><p align=right>$prettybal</p></td></tr>";
	}
}
function bootstrap() {?><meta charset=utf8><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"><?php }
$nulkontrol = 0;
function saldobalance($t,$stdkto) {
	echo "<h1>Saldobalance</h1>";
	global $nulkontrol;
	echo "<table border=1 class='table-sm' width=700>";
	$curheader="";
	foreach ($stdkto as $curkto) {
		if ($curkto[1] == "Overskrift") $curheader = printheader($curkto);
		else if ($curkto[1] == "" ) {
			$p = printaccbal($curkto);
			if ($p != "" ) {
				echo $curheader;
				$curheader = "";
				echo $p;
			}
		}
		else if (substr($curkto[1],0,4) == "Sum ") {
			$x = explode(" ",$curkto[1])[1];
			$x = explode("-",$x);
			$p = printsumbal($curkto,$x[0],$x[1]);
			if ($p != "") {
				echo $curheader;
				$curheader = "";
				echo $p;
			}
		}
		else {
			echo "Unhandled:\n";
			print_r($curkto);die();
		}
	}
	$prettynul=number_format($nulkontrol,2,".",",");
	echo "<tr><td>&nbsp;</td><td><b><u>Nulkontrol</u></b></td><td><b><u><p align=right>$prettynul</u></b></p></td></tr>";
	echo "</table>";
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
	ob_start();
	$konto = trim(explode("\t",$curkto[0])[0]);
	global $newtrans;
	$bal = 0;
	foreach ($newtrans as $curtrans) {
		if ($curtrans['Account'] == $konto) $bal += $curtrans['Amount'];
	}
	$nulkontrol += $bal;
	if (intval($bal) != 0) {
		$pretty = number_format($bal,2,".",",");
		echo "<tr><td>$konto</td><td>$curkto[2]</td><td><p align=right>$pretty</p></td></tr>\n";
	}
	return ob_get_clean();
}
function printheader($header) {
	return "<tr><td>&nbsp;</td><td><b>" . $header[2]."</b></td></tr>\n";
}
function rewritetrans($t) {
	global $map;
	$retval = array();
	foreach ($t as $curt) {
		if (!isset($curt[5])) continue;
		$newt = array();
		$newt['Date'] = $curt[0];
		$newt['Tekst'] = $curt[2];
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
		file_put_contents("$tpath/.standardmapping",json_encode($map,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		return $map[$acc];
	}
	else
		return $map[$acc];
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
	$valg = fzf($fzf,"Vælg konto for $acc");
	if ($valg == "") die("Afbrudt ved valg af konto\n");
	else return $valg;
}
function gettransactions() {
	ob_start();
	system("skipresult=1 php /svn/svnroot/Applications/newl.php csv");
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
	$mapfile = "$tpath/.standardmapping";
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

