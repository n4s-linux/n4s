<?php
$op = exec("whoami");
require_once("/svn/svnroot/Applications/ansi-color.php");
$fzf = "";
$notecount = 1;
$notes = array();
while ($line = fgets(STDIN)) {
	$line = explode("\n",$line)[0];
	$md = md5(trim($line).getenv("LEDGER_BEGIN").getenv("LEDGER_END"));
	$inlinenote = "";
	if (file_exists("$tpath/.colorizer_$md")) {
		$color = trim(file_get_contents("$tpath/.colorizer_$md"));
		$notes[$color][trim($line)] = getnotes($md);
		foreach(explode("\n",$notes[$color][trim($line)]) as $curline) {
			$inlinenote .= "\n\t\t\t 🖋 " . trim($curline);
		}
	}
	else
		$color = "white";	
	$fzf .= set("$line\n",$color);
	echo colorize($line.$inlinenote,$color) . "\n";
}
foreach ($notes as $key => $val) {
	$color = $key;
	foreach ($val as $konto => $comment) {
		$commentlines = explode("\n",$comment);
		$konto = trim($konto);
		foreach ($commentlines as $curcommentline) {
			$curcommentline=trim($curcommentline);
			preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $curcommentline);
			//echo set("$curcommentline",$color);
			//echo set("\n","black");
		}
		//echo "\n";
	}
}
if (getenv("n4s_setcolors") == 1) {
	require_once("/svn/svnroot/Applications/fzf.php");
	$valg = fzf($fzf,"Vælg linie(r) til farvning","--ansi --tac --multi",false,false);
	$alle = explode("\n",$valg);
	$farve = pick();
	foreach ($alle as $valg) {
	$md = md5(trim($valg).getenv("LEDGER_BEGIN").getenv("LEDGER_END"));
	file_put_contents("$tpath/.colorizer_$md",$farve);
	$date=date("Y-m-d") . "T" .date("H:m") ;
	if (!file_exists("$tpath/.colorizer_$md.comment")) file_put_contents("$tpath/.colorizer_$md.comment","# Noter - $valg\n",FILE_APPEND);
	file_put_contents("$tpath/.colorizer_$md.comment","\t$date $op\tCOLORCHG '$valg' $farve\n",FILE_APPEND);
	exec_app("vi $tpath/.colorizer_$md.comment");
	}
}
$noteno = 1;
$notes = array();
die("");
while ($line = fgets(STDIN)) {

	$line = explode("\n",$line)[0];
	$md = md5(trim($line).getenv("LEDGER_BEGIN").getenv("LEDGER_END"));
	if (file_exists("$tpath/.colorizer_$md")) {
	die("foobar\n");
		$color = trim(file_get_contents("$tpath/.colorizer_$md"));
		//$commentgrep = "grep -v ^\#\ Noter|grep -v \") gjort '\"";
		array_push($notes,getnotes($md));
		print_r($notes);
		$line .= " * $noteno\n";
		$noteno++;

	}
	else
		$color = "cyan";	
	$fzf .= colorize("$line\n",$color);
	echo colorize($line,$color) . "\n";
}
function getnotes($md) {
	global $tpath;
	$note = file_get_contents("$tpath/.colorizer_$md.comment");
	$lines = explode("\n",$note);
	$retval = "";
	foreach ($lines as $curline) {
		if (stristr($curline,"COLORCHG")) continue;
		if (stristr($curline,"# Noter - ")) continue;
		$retval .= $curline  . "\n";
	}
	return trim($retval);
}

function colorize($line,$color) {
	if ($color == "")
		echo $line;
	else
		echo set($line,$color);
}
?>
