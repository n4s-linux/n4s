<?php
$begin = getenv("LEDGER_BEGIN");
// Formål: Beregning af rente på baggrund af aktuel ledger
// Status: Ikke funktionel
echo "Opdaterer åbningsposter $begin...";
$accounts = "";
$saldo = array();
require_once("/svn/svnroot/Applications/short.php");
$op = exec("whoami");
$fd = fopen("PHP://stdin","r");
while ($line = fgetcsv($fd,null,",","\"","\\")) { // GET DATA 
	$date = $line[0];
	if (isset($line[1])) $bilag = $line[1]; else $bilag = "";
	if (isset($line[2])) $desc = $line[2]; else $desc = "";
	if (isset($line[3])) $konto = $line[3];else $konto = "";
	if (isset($line[7])) $fn = $line[7]; else $fn = "";
	if (substr($konto,0,strlen("Udgifter:")) == "Udgifter:") continue;
	if (substr($konto,0,strlen("Indtægter:")) == "Indtægter:") continue;
	if (substr($konto,0,strlen("Resultatdisponering:")) == "Resultatdisponering:") continue;
	if (substr($konto,0,strlen("Egenkapital:Periodens resultat")) == "Egenkapital:Periodens resultat") continue;
	if (isset($line[5]))
		$belob = floatval($line[5]);
	else
		$belob = 0;
	if (isset($saldo[$konto]))
		$saldo[$konto] += $belob;
	else
		$saldo[$konto] = $belob;
}
$s = "$begin Overført saldo\n";
$y = 0;
foreach ($saldo as $konto => $cursaldo) {
	$y += $cursaldo;
	if (intval($cursaldo) == 0) continue;
	$cursaldo = number_format($cursaldo,3,".","");
	$s .= "\t$konto  $cursaldo\n";
}
if ($y != 0)
	$s .= "\tEgenkapital:Overført resultat\n";
$s .= "\n";
$tpath = getenv("tpath");
file_put_contents("$tpath/.Åbning_$begin.ledger",$s);
?>
