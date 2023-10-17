<?php
// Formål: Beregning af rente på baggrund af aktuel ledger
// Status: Ikke funktionel

$accounts = "";
$saldo = array();
require_once("/svn/svnroot/Applications/short.php");
$op = exec("whoami");
$fd = fopen("PHP://stdin","r");
while ($line = fgetcsv($fd,null,",","\"","\\")) { // GET DATA 
	$date = $line[0];
	$bilag = $line[1];
	$desc = $line[2];
	$konto = $line[3];
	$fn = $line[7];
	$belob = floatval($line[5]);
	$saldo[$konto] += $belob;
}
$begin = getenv("LEDGER_BEGIN");
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
echo $s;
file_put_contents("$tpath/.Åbning_$begin.ledger",$s);
$aktiver = "<table border=1>";
$passiver = "<table border=1>";
$egenkapital = "<table border=1>";
$fejlkonto = "<table border=1>";
foreach ($saldo as $konto => $cursaldo) {
	$y += $cursaldo;
	if (intval($cursaldo) == 0) continue;
	$sk = $konto;
	$cursaldo = number_format($cursaldo,2,",",".");
	if (substr($konto,0,strlen("Aktiver")) == "Aktiver") $aktiver .= "<tr><td>$sk</td><td><p align=right>$cursaldo</p></td>";
	else if (substr($konto,0,strlen("Aktiver")) == "Aktiver") $aktiver .= "<tr><td>$sk</td><td><p align=right>$cursaldo</p></td>";
	else if (substr($konto,0,strlen("Passiver")) == "Passiver") $passiver.= "<tr><td>$sk</td><td><p align=right>$cursaldo</p></td>";
	else if (substr($konto,0,strlen("Egenkapital")) == "Egenkapital") $egenkapital.= "<tr><td>$sk</td><td><p align=right>$cursaldo</p></td>";
	else if (substr($konto,0,strlen("Fejlkonto")) == "Fejlkonto") $fejlkonto.= "<tr><td>$sk</td><td><p align=right>$cursaldo</p></td>";
}
$aktiver .= "</table>";
$passiver .= "</table>";
$egenkapital .= "</table>";
$fejlkonto .= "</table>";
$ft = "<table border=1>";
$ft .= "<tr><td><b>Aktiver</b><br>$aktiver</td>";
$ft .= "<td><b>Passiver</b><br>$passiver</td></tr>";
$ft .= "<tr><td><b>Egenkapital</b><br>$egenkapital</td>";
$ft .= "<td><b>Fejlkonto</b><br>$fejlkonto</td></tr>";
$ft .= "</table>";
file_put_contents("/home/$op/tmp/ft.html",$ft);
system("w3m -dump ~/tmp/ft.html");
?>
