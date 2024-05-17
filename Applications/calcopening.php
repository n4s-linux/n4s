<?php
$tpath = getenv("tpath");
$bn = basename($tpath);
$begin = getenv("LEDGER_BEGIN");
// Formål: Beregning af rente på baggrund af aktuel ledger
// Status: Ikke funktionel
// fwrite(STDERR, “$bn: Opdaterer åbningsposter $begin\n”);
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
$s = "";
foreach ($saldo as $konto => $cursaldo) {
	$orgkonto = $konto;
	$kkkeep = array("Egenkapital:Selskabskapital","Egenkapital:Mellemregning");
	if (intval($cursaldo) == 0) continue;
	$cursaldo = number_format($cursaldo,2,".","");
	$keep= true;
	if (substr($konto,0,strlen("Egenkapital:")) == "Egenkapital:") {
		if (!stristr($konto,"reserve") && !in_array($konto,$kkkeep)) {
			$keep= true;
		}
		else
			$keep = false;
	}
	if ($keep) {
	$s .= "$begin ☀ $orgkonto\n";
	$s .= "\t$konto  $cursaldo ; Filename: calcopening.php |||| TransID: virt |||| Status: Locked\n\tEgenkapital:Overført resultat  ; Filename: calcopening.php |||| TransID: virt |||| Status: Locked\n\n";
	}
}
file_put_contents("$tpath/.Åbning_$begin.ledger",$s);
fwrite(STDERR, "Åbning $begin genereret !\n");
?>
