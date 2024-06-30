<?php 
require_once("/svn/svnroot/Applications/print_array.php");
function lookup_acc($accounts,$bal,$alias = "",$multi = "--multi") {
	$path = getenv("tpath");
	$af = "$path/aliases";
	if (file_exists($af)) $af = json_decode(file_get_contents($af),true); else $af = array();
	$accountstring = "";
	ob_start();
	$aout="";
	foreach ($af as $curalias => $curacc) {
		$aout .= "🦎 $curalias => $curacc\n";
	}
	$op = exec("whoami");
	file_put_contents("/home/$op/tmp/aliases.out",$aout);
	$cmd = ("((cat ~/tmp/aliases.out;updatealiases= noend=1 LEDGER_BEGIN=1900-01-01 color=none php /svn/svnroot/Applications/newl.php accounts;cat /svn/svnroot/Libraries/Kontoplan.txt;cat ~/tmp/aliases.out)|sort|uniq|grep :;echo MANUAL;echo 'Fejlkonto:Ved ikke')|fzf --ansi --tac --header-first --header=\"$alias\" --scrollbar=* $multi --border --preview-label='Seneste posteringer' --preview-window 50%:bottom  --preview 'LEDGER_PAYEE_WIDTH=20 LEDGER_ACCOUNT_WIDTH=15 updatealiases= color=none php /svn/svnroot/Applications/newl.php register ^\"{}\" |tac'");
	system($cmd);
	$accountstring = trim(ob_get_clean());
	$accountstring = str_replace("🦎","",$accountstring);
	$accountstring = trim(explode("=>",$accountstring)[0]);
	if ($accountstring == "") die("Lookupaccount Interrupted - Aborting...\n");
	if ($accountstring=="MANUAL") {
		$valg = fzf("Finans\nDebitor\nKreditor","Pick Account Type");
		if ($valg == "Kreditor") $prefix = "Passiver:Kreditorer:";
		else if ($valg == "Debitor") $prefix = "Aktiver:Omsætningsaktiver:Debitorer:";
		else if ($valg == "") die("Terminated during account picking\n");
		else $prefix = "";

		$accountstring = "";
		while ($accountstring == "") {
			system("stty sane");
			echo "\nEnter Manual Account - Full Account string:\n";
			$fd = fopen("PHP://Stdin","r");
			$accountstring = $prefix . trim(explode("\n",fgets($fd))[0]);
			fclose($fd);
		}
	}
	return $accountstring;
}
