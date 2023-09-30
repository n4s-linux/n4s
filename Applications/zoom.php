   <?php
require_once("ansi-color.php");
$tpath = getenv("tpath");
if (isset($argv[1]) && $argv[1] == "preview") {
	$x = explode("⚡",$argv[2]);
	if (isset($x[7]))
		$fn = trim($x[7]);
	else
		$fn = null;
	if (file_exists($fn)) {
		$data = json_decode(file_get_contents("$tpath/$fn"),true);
		if (!isset($data['Comment'])) $data['Comment'] = "";
		if (!isset($data['Ref'])) $data['Ref'] = "";
		if (!isset($data['Reference'])) $data['Reference'] = "";
		$tc = 0;
		echo "\n";
		foreach ($data['Transactions'] as $curtrans) {
			foreach ($curtrans as $key =>$val) {
				if ($val == "") continue;
				if ($tc% 2 == 0) $color="yellow"; else $color="blue";
				echo set("$key => $val\n",$color);
			}
			$tc++;
		}
	}
	die(); // need to end here, below is main script
}
require_once("/svn/svnroot/Applications/fzf.php");
$fzf 	="";
$saldo = 0;
while ($line = fgetcsv(STDIN,null,",","\"","\\")) {
	$fn = explode("Filename: ",$line[7]);
	if (isset($fn[1]))
		$fn = explode("\\n",$fn[1])[0];
	else
		$fn = null;
	$date = $line[0]; $bilag=$line[1];$tekst=$line[2];$konto=$line[3];$belob=$line[5];
	$saldo += $belob;
	$bilag = substr($bilag,0,5);
	$tekst = substr($tekst,0,10);
	$nicebelob=str_pad(number_format($belob,2,",","."),15," ", STR_PAD_LEFT);
	$nicesaldo=str_pad(number_format($saldo,2,",","."),15," ", STR_PAD_LEFT);
	$nicekonto = shortacc($konto);
	$bg = getbg($fn);
	if (!isset($farvesaldo[$bg])) $farvesaldo[$bg] = $belob;else $farvesaldo[$bg] += $belob;
	$fzf .=set("⚡$date\t⚡$bilag\t⚡$tekst\t⚡$nicekonto\t⚡$nicebelob\t⚡$nicesaldo\t⚡$fn\t⚡$bg\n",$bg);
}
$farvesaldi = getfarvesaldi($farvesaldo);
$valg = fzf($fzf,"Vælg en transaktion eller flere (tryk tab) $farvesaldi","--ansi --multi --preview='echo {+}' --tac ",true);
if (trim($valg) == "") die();
$fns = array();
foreach (explode("\n",$valg) as $line) {
	if (!isset($line[3])) continue;
	$line = explode("⚡",$line);
	$fns[] = trim($line[7]);
        $date = $line[0]; $bilag=$line[1];$tekst=$line[2];$konto=$line[3];$belob=$line[5];
}

$whattodo = fzf("Rediger\nFarvekodning\n");
if ($whattodo == "Farvekodning") {
	$farve = pick();
	foreach ($fns as $curfn) {
		setcolor("$tpath/$curfn",$farve);
	}
}
else {
	$tpath=getenv("tpath");
	$s = "";
	foreach ($fns as $curfn) {
		$curfn = trim($curfn);	if (!strlen($curfn)) continue;
		$bn = basename("$tpath");
		$modus = fzf("split-window -h\nsplit-window\nnew-window","Vælg modus");
		exec_app("tmux $modus 'bash /svn/svnroot/Applications/start.bash edit \"$bn\" \"$curfn\"'&");
	}
}

function getfarvesaldi($saldi) {
	$retval = "";
	foreach ($saldi as $baggrund => $saldo) {
		if ($saldo != 0)
			$retval .= set($baggrund . number_format($saldo,2,".",","),$baggrund) . " ";
	}
	return $retval;
}
function formatPrint(string $text = "",$format) {
	return $text;
}
require_once("shortacc.php");
