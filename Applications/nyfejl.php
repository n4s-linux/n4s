<?php
if (getenv("LEDGER_END") == "" || getenv("LEDGER_BEGIN") == "") die("kræver periode\n");
$op = exec("whoami");
$tpath = getenv("tpath");
$bn = basename($tpath);
$html = "<!doctype html>
<html lang='en'>
  <head>
<style>
div {
  text-align: center;
  border: 3px solid green;
}
hr {
  margin-top: 1rem;
  margin-bottom: 1rem;
  border: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
}
table {
white-space: nowrap;
}</style>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Fejlkonto $bn - " . date("Y-m-d") . "</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
	<meta charset=utf8>
  </head>
<body>
<center>
";
$date = date("Y-m-d");
$html .= "<h1>Uafklarede poster $bn $date</h1><br>";
ob_start();
system ("ls $tpath/*.trans");
$files = explode("\n",trim(ob_get_clean()));
foreach ($files as $curfile) {
	if (!file_exists($curfile)) continue;
	$data = json_decode(file_get_contents($curfile),true);
	$konti = json_encode($data["Transactions"]);
	if (strtotime($data["Date"]) < strtotime(getenv("LEDGER_BEGIN")) || strtotime($data["Date"]) > strtotime(getenv("LEDGER_END") . " -1 day")) continue;
	if (stristr($konti,"Fejlkonto:")) {
		$html .= gettrans($data,$curfile);
	}
}
file_put_contents("/home/$op/tmp/fk.html",$html);
system("html2ps ~/tmp/fk.html > ~/tmp/fk.ps");
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");

system(" google-chrome --headless ~/tmp/fk.html --print-to-pdf=Fejlkonto-$bn-$begin-$end.pdf;mv Fejlkonto-$bn-$begin-$end.pdf ~/n4s-export/");;

function gettrans($trans,$fn) {
	if (strtotime($trans["Date"]) <= strtotime(getenv("LEDGER_BEGIN")) || strtotime($trans["Date"]) >= getenv("LEDGER_END")) return "";
	if (!isset($trans["Comment"]) || $trans["Comment"] == "") {
		$trans["Comment"] = askcomment($trans);
		if ($trans["Comment"] == "") return "";;
		$trans["History"][] = array("Date"=>date("Y-m-d H:i"),"op"=>exec("whoami"),"Desc"=>"Tilføjet kommentar via nyfejl.php: $trans[Comment]");
		file_put_contents($fn,json_encode($trans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	}
	$r = "<h2><p align=center>$trans[Date] - $trans[Description]</p></h2><div align=center width=600><table border=1 width=600 class='table table-striped'>";
	foreach ($trans["Transactions"] as $curtrans) {
		$amount = number_format($curtrans["Amount"],2,".",",");
		$r .= "<tr><td width=200>$curtrans[Account]</td><td><p align=right>$amount</p></td><td>$curtrans[Func]</td></tr>";
	}
	$r .= "</table></div>";
	if (isset($trans["Comment"]) && $trans["Comment"] != "")
		$r .= "<br><div style='text-align: center;   border: 6px solid yellow;'>";
		$r .= "Kommentar/Spørgsmål: $trans[Comment]<br></div>\n";
		//$r .= "<textarea rows=4 cols=30 name=svar[\"$fn\"]></textarea>";
	return "<table border=1><tr><td>$r</td></tr></table><hr>";
}
function askcomment($trans) {
	require_once("/svn/svnroot/Applications/fzf.php");
	global $op;
	$table = "<table border=1><tr><td>Dato</td><td>$trans[Date]</td>";
	$table .= "<tr><td>Date</td><td>$trans[Date]</td></tr>";
	$table .= "<tr><td>Tekst</td><td>$trans[Description]</td></tr>";
	$table .= "<tr><td>Reference</td><td>$trans[Reference]</td></tr>";
	$table .= "<tr><td>Fil</td><td>$trans[Filename]</td></tr></table>";
	$table .= "<tr><td>Date</td><td>$trans[Date]</td></tr></table>";
	$table .= "<table border=1><tr><td>Transaktioner</td>";
	foreach ($trans["Transactions"] as $curtrans) {
		$table .= "<tr><td>$curtrans[Account]</td><td>$curtrans[Amount]</td><td>$curtrans[Func]</td></tr>";
	}	
	$table .= "</table>";
	file_put_contents("/home/$op/tmp/nyfejl.php.html",$table);
	system("w3m -dump ~/tmp/nyfejl.php.html");
	$valg = fzf("Hvad er det\nMangler bilag\nHvordan betalt\nAndet\nRediger\nSkip","Vælg kommentar/spørgsmål til fejlliste","--tac --height=9");
	if ($valg == "Skip") return "";
	else if ($valg == "Andet") {
		echo "Indtast kommentar/spørgsmål til kunde: ";
		$fd = fopen("/dev/tty","r");$valg = trim(fgets($fd)); fclose($fd);
	}
	else if ($valg == "Rediger") {
		require_once("/svn/svnroot/Applications/proc_open.php");
		exec_app("php /svn/svnroot/Applications/key.php search 1970-01-01 2099-12-31 \"$trans[Filename]\"");
		return "";
	}
	return $valg;

}
?>
