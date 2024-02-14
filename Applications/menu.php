<?php
require_once("/svn/svnroot/Applications/ansi-color.php");
$op = exec("whoami");
$mode = screensizemode();
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
if (getenv("tpath") == "") die("Kræver tpath\n");
$hovedmenu = array("Saldobalance","Kontokort","Indstillinger","Udskrifter","Import","Eksport");

if (!isset($argv[1]) ||$argv[1] == "") {
	menu($hovedmenu,"n4s","Sneakpreview",""," --height=10");
}
else if ($argv[1] == "Saldobalance") {
	$a = getaccounts();
	menu($a,"Regnskabstal $begin - $end","Væsentlige poster","php /svn/svnroot/Applications/menu.php Væsentligeposter $begin $end {}");
	
}
else if ($argv[1] == "Preview") {
	if ($argv[2] == "Saldobalance")
		echo getpivot();
}
else if ($argv[1] == "Væsentligeposter") {
	$konto = trim($argv[4]);
	$x = explode(" ",$konto);
	if (count($x) > 2) {
		unset($x[0]);unset($x[1]);
	}
	else
		$konto = trim($argv[4]);

	$konto = implode(" ",$x);
	$konto = str_replace(" ",".",$konto);
	$konto = str_replace("&","\\&",$konto);
	$konto = str_replace("%",".",$konto);
	getepictrans($konto);
}
else
	echo "$argv[1] unhandled\n";





function menu($options,$header,$previewlabel,$previewcommand = "php /svn/svnroot/Applications/menu.php Preview {}",$xtra = "") {
$uid = md5($header) ;//. time();
$str = "";
foreach ($options as $curoption) {
	$str .= "$curoption\n";
}
if ($previewcommand != "")
	$cmd = "echo \"$str\" > ~/tmp/.mymenu_$uid;cat ~/tmp/.mymenu_$uid|fzf  --ansi --preview='$previewcommand' --header-first --header=\"$header\" --scrollbar=* --margin 2% --padding 1% --border --preview-label='$previewlabel' --preview-window 65%:right $xtra --bind 'load:reload-sync(sleep 1;cat ~/tmp/.mymenu_$uid)+unbind(load)'";
else
	$cmd = "echo \"$str\" > ~/tmp/.mymenu_$uid;cat ~/tmp/.mymenu_$uid|fzf  --ansi --header-first --header=\"$header\" --scrollbar=* --margin 2% --padding 1% --border $xtra --bind 'load:reload-sync(sleep 1;cat ~/tmp/.mymenu_$uid)+unbind(load)'";

system($cmd);
}
function getaccounts() {
	$tlas = gettlas();
	ob_start();
	$uid = time();
	global $op;
	system("php /svn/svnroot/Applications/newl.php print > ~/tmp/curl_$uid");
	foreach ($tlas as $curtla) {
		echo set("$curtla:","inverse");
		echo "\n";
		system("ledger -f ~/tmp/curl_$uid balance -E --flat ^\"$curtla\"");
		echo "\n";
	}
	unlink("/home/$op/tmp/curl_$uid");
	return array_reverse(explode("\n",ob_get_clean()));
}
function gettlas() { // tla = top level account
	ob_start();
	system("php /svn/svnroot/Applications/newl.php print |hledger --depth=1 -f /dev/stdin accounts -E");
	$r = array_filter(array_reverse(explode("\n",ob_get_clean())));
	require_once("/svn/svnroot/Applications/tlasort.php");
	usort($r,"tlasort");
	return $r;
}
function getepictrans($konto) {


	echo set("$konto:\n","inverse");
	echo set("Posteringer:\n","inverse");
	$cmd =("LEDGER_SORT=account,date LEDGER_ACCOUNT_WIDTH=16 LEDGER_PAYEE_WIDTH=22 php /svn/svnroot/Applications/newl.php register ^\"$konto\" ");
	system($cmd);
	echo "\n";
	echo set("Summasummarum:\n","inverse");
	$cmd =("LEDGER_SORT=account,date LEDGER_ACCOUNT_WIDTH=16 LEDGER_PAYEE_WIDTH=22 php /svn/svnroot/Applications/newl.php balance ^\"$konto\" ");
	echo "\n";
	system($cmd);
	echo "\n";

	echo set("Kvartalsvis:\n","inverse");
	$cmd =("LEDGER_SORT=account,date LEDGER_ACCOUNT_WIDTH=16 LEDGER_PAYEE_WIDTH=22 php /svn/svnroot/Applications/newl.php register ^\"$konto\" --quarterly");
	system($cmd);
	echo "\n";

	echo set("Slægtninge:\n","inverse");
	$cmd =("LEDGER_SORT=account,date LEDGER_PAYEE_WIDTH=22 php /svn/svnroot/Applications/newl.php balance ^\"$konto\" --flat --related ");
	system($cmd);
	echo "\n";




}

function getpivot() {
	global $begin; global $end;
	global $mode;
	ob_start();
	$cmd =("php /svn/svnroot/Applications/newl.php print|hledger --depth=2 --begin=$begin --end=$end -f /dev/stdin balance $mode");
	system($cmd);
	return ob_get_clean();
	$r = array_filter(array_reverse(explode("\n",ob_get_clean())));
	return $r;
}
function screensizemode() {
	$cols = exec("tput cols");
	if ($cols < 80)
		return "--yearly";
	else if ($cols < 180) 
		return "--quarterly";
	else
		return "--monthly";
}
