<?php
if (isset($argv[1]) && $argv[1] == "preview") {
	$tpath = getenv("tpath");
	$fn = $tpath."/".getlastword($argv[3]);
	if (!file_exists($fn)) die("no preview\n");
	require_once("/svn/svnroot/Applications/ansi-color.php");
	$konto = $argv[2];
	$transactions = json_decode(file_get_contents($fn),true)['Transactions'];
	$i = 0;
	foreach ($transactions as $curtrans) {
	echo set("🌱 [$i] ","blue_bg");
		foreach ($curtrans as $key=>$val) {
			$val = trim($val);
			if (strlen($val)) { // && $curtrans['Account'] != $konto)
				//echo "$key" . "[$i] = $val ";
				if ($key == "Func") $bg = "yellow_bg"; 
				else if ($key == "Account") $bg = "blue_bg";
				else if ($key == "P-Start" || $key == "P-End") $bg = "yellow";
				else $bg="magenta";
				echo " " . set("($val)","$bg") . " ";
			}
		}
		echo " 🌱 ";
		$i++;
		echo "\n";
	}
	die();
}
$kontoid = array();
require_once("ansi-color.php");
require_once("niceval.php");
$tpath = getenv("tpath");
$bn = basename($tpath);
$data = "";
$saldi = array();
$transactions = array();
while ($line = fgetcsv(STDIN,null,",","\"","\\")) {
	$date = $line[0];
	$bilag = $line[1];
	$tekst = $line[2];
	$konto = trim($line[3]);
	$belob = round($line[5],2);
	if (isset($line[7])) $tags = $line[7]; else $tags = "";
	if ($tags != "") { 
		$tags = explode("\\n",explode("Filename: ",$tags)[1])[0];
	}
	$saldi[$konto] += $belob;
	if (round($saldi[$konto],2) == 0) unset($saldi[$konto]);
	array_push($transactions,array('date'=>$date,'bilag'=>$bilag,'tekst'=>$tekst,'konto'=>$konto,'belob'=>$belob,'tags'=>$tags));
}
ksort($saldi);
$fzf = "";
$i = 0;
$resultat = 0;
foreach (array("Indtægter","Udgifter","Resultatdisponering","Aktiver","Egenkapital","Passiver","Fejlkonto") as $curtla) {
	$tlares = 0;
	foreach ($saldi as $key => $cursaldo) {
		if (stristr($key,$curtla . ":")) {
			$kontoid[$i] = $key;
			$tlares += $cursaldo;
			if ($curtla == "Indtægter"||$curtla=="Udgifter") $resultat += $cursaldo;
			$key = substr($key,strlen($curtla)+1);
			$cursaldo = niceval($cursaldo);
			$fzf .= "$i\t$curtla\t$key\t$cursaldo\n";
			$i++;
		}
	}
	$ptlares = niceval($tlares,false);
	$fzf .=  set(" \t$curtla\tI alt\t$ptlares","blue_bg") . "\n";
}
require_once("fzf.php");
$valg = 1;
while ($valg != "") {
	$valg = fzf($fzf,"vælg konto","--tac --ansi --exact",true);
	$valg = intval(explode(" ",$valg)[0]);
	$konto = $kontoid[$valg];
	viskonto($konto);
}
function viskonto($konto) {
	global $transactions;
	$fzf = "";
	$saldo = 0;
	foreach ($transactions as $curtrans) {
		if ($curtrans['konto'] == $konto) {
			$ct = $curtrans;
			$saldo += $ct['belob'];
			$ns = niceval($saldo);
			$debit = ""; $credit = "";
			if ($ct['belob'] < 0) { $credit = niceval($ct['belob']); $debit = ""; }
			else { $debit = niceval($ct['belob']); $credit = ""; }
			$nb = niceval($ct['belob']);
			$ct['bilag'] = substr($ct['bilag'],0,10);
			$ct['tekst'] = substr($ct['tekst'],0,20);
			$fzf .= "$ct[date]\t$ct[bilag]\t$\t$ct[tekst]\t$debit\t$credit\t$ns\t$ct[tags]\n";
		}
	}	
	$valg = "1";
	while ($valg != "") { 
	$valg = fzf($fzf,"vælg transaktion","--tac --ansi --exact --preview-window=top:3 --preview='php /svn/svnroot/Applications/csvdatakey.php preview \"$konto\" {}'",true);

	edit(getlastword($valg));
	}
}
function getlastword($string) {
	preg_match('/[^ ]*$/', $string, $results);
	$last_word = $results[0]; // $last_word = PHP.
	return $last_word;
}
function edit($fn) {
	$tpath = getenv("tpath");
	$bn = basename($tpath);
	require_once("proc_open.php");
	exec_app("tmux popup -E /svn/svnroot/Applications/start.bash edit \"$bn\" \"$fn\"");
}


