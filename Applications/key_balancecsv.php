<?php
require_once("ansi-color.php");
$op = exec("whoami");
$darray = array();
$fd = fopen("/home/$op/tmp/key_balance.tmp","r");
while ($line = fgetcsv($fd,null,",","\"","\\")) {
	$nt =  array('date'=>$line[0],'bilag'=>$line[1],'tekst'=>$line[2],'konto'=>$line[3],'belob'=>$line[5]);
	array_push($darray,$nt);
error_reporting(0);
error_reporting(E_ALL);
}


if (isset($argv[1]) && $argv[1] == "preview") {
	$konto = trim(explode("🔥",$argv[2])[0]);
	echo getsaldi($konto);
	die();
}
else if (isset($argv[1]) && $argv[1] == "viewtla" ) {
	viewtla($argv[2]);
	die();
}

require_once("fzf.php");

$tla = gettla($darray);
uksort($tla,"tlasort");
print_r($tla);
$fzf = "";
require_once("niceval.php");
foreach ($tla as $curtla => $saldo) {
	$nvsaldo = str_pad(niceval($saldo),15," ",STR_PAD_LEFT);
	$fzf .= "$curtla\t🔥" . "$nvsaldo\n";
}
$valg = trim(explode("🔥",fzf($fzf,"vælg tla","--ansi --tac --preview='php /svn/svnroot/Applications/key_balancecsv.php preview {}'",true))[0]);
system("php /svn/svnroot/Applications/key_balancecsv.php viewtla $valg");
function viewtla($konto) {
	global $darray;
	$sumz = array();
	foreach ($darray as $curtrans) {
		if (substr($curtrans ['konto'],0,strlen($konto)) == $konto) {
			$x = explode(":",$curtrans['konto']);
			if (isset($x[1]))
				$l2 = $x[1];
			else
				$l2 = "TOM";
			error_reporting(0);
			$sumz[$l2] += $curtrans['belob'];
			error_reporting(E_ALL);
		}
	}
	foreach ($sumz as $curkonto => $cursaldo) {
		$bal += $cursaldo;
		$curkonto = str_pad($curkonto,35," ");
		$cursaldo = str_pad($cursaldo,15," ",STR_PAD_LEFT);
		$fzf .= "$curkonto$cursaldo\n";
	}
	require_once("fzf.php");
	$valg = fzf($fzf,"vælg underkonto","--ansi --tac",true);
	echo "valg='$valg'\n";
			
}
function getsaldi($a) {
	global $darray;
	foreach ($darray as $curtrans) {
		if (substr($curtrans ['konto'],0,strlen($a)) == $a) {
			$l2 = explode(":",$curtrans['konto'])[1];
			error_reporting(0);
			$sumz[$l2] += $curtrans['belob'];
			error_reporting(E_ALL);
		}
	}
	$bal = 0;
	foreach ($sumz as $curkonto => $cursaldo) {
		$bal += $cursaldo;
		$curkonto = str_pad($curkonto,35," ");
		$cursaldo = str_pad($cursaldo,15," ",STR_PAD_LEFT);
		echo "$curkonto$cursaldo\n";
	}
	echo "\n";
	echo str_pad("Balance",35," ");
	echo str_pad($bal,15," ", STR_PAD_LEFT);
}
function gettla($darray) {
	foreach ($darray as $curtrans) {
		$toplevel = explode(":",$curtrans['konto'])[0];
		$retval[$toplevel] += floatval($curtrans['belob']);
	}
	$rv = array();
	foreach ($retval as $key => $val) {
		if ($val != 0)
			$rv[$key] = $val; 
	}
	return $rv;
}
function tlasort($a,$b) {
	$score = array('Indtægter'=>0,'Udgifter'=>1,'Resultatdisponering'=>2,'Aktiver'=>4,'Egenkapital'=>5,'Passiver'=>6);
	if (isset($score[$a]))
		$as = $score[$a];
	else
		$as = 7;
	if (isset($score[$b]))
		$bs = $score[$b];
	else
		$bs = 7;
	return ($as > $bs);
}
