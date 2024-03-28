<?php
$op = exec("whoami");
require_once("/svn/svnroot/Applications/ansi-color.php");
$fzf = "";
while ($line = fgets(STDIN)) {
	$line = explode("\n",$line)[0];
	$md = md5(trim($line).getenv("LEDGER_BEGIN").getenv("LEDGER_END"));
	if (file_exists("$tpath/.colorizer_$md"))
		$color = trim(file_get_contents("$tpath/.colorizer_$md"));
	else
		$color = "white";	
	$fzf .= set("$line\n",$color);
	echo colorize($line,$color) . "\n";
}
if (getenv("n4s_setcolors") == 1) {
	require_once("/svn/svnroot/Applications/fzf.php");
	$valg = fzf($fzf,"Vælg linie til farvning","--ansi --tac --multi",false,false);
	$alle = explode("\n",$valg);
	$farve = pick();
	foreach ($alle as $valg) {
	echo "valgt '$farve' for '$valg'\n";
	$md = md5(trim($valg).getenv("LEDGER_BEGIN").getenv("LEDGER_END"));
	file_put_contents("$tpath/.colorizer_$md",$farve);
	}
}
while ($line = fgets(STDIN)) {
	$line = explode("\n",$line)[0];
	$md = md5(trim($line).getenv("LEDGER_BEGIN").getenv("LEDGER_END"));
	if (file_exists("$tpath/.colorizer_$md"))
		$color = trim(file_get_contents("$tpath/.colorizer_$md"));
	else
		$color = "cyan";	
	$fzf .= colorize("$line\n",$color);
	echo colorize($line,$color) . "\n";
}


function colorize($line,$color) {
	if ($color == "")
		echo $line;
	else
		echo set($line,$color);
}
?>
