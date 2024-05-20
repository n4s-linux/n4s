<?php 
require_once("/svn/svnroot/Applications/print_array.php");
function lookup_acc($accounts,$bal,$alias = "",$multi = "--multi") {
	$path = getenv("tpath");
	$accountstring = "";
	ob_start();
	$cmd = ("((updatealiases= noend=1 color=none php /svn/svnroot/Applications/newl.php accounts;cat /svn/svnroot/Libraries/Kontoplan.txt)|sort|uniq|grep :;echo MANUAL;echo 'Fejlkonto:Ved ikke')|fzf --ansi --tac --header-first --header=\"$alias\" --scrollbar=* $multi --border --preview-label='Seneste posteringer' --preview-window 50%:bottom  --preview 'LEDGER_PAYEE_WIDTH=20 LEDGER_ACCOUNT_WIDTH=15 updatealiases= color=none php /svn/svnroot/Applications/newl.php register ^\"{}\" |tac'");
	system($cmd);
	$accountstring = trim(ob_get_clean());
	if ($accountstring == "") die("Lookupaccount Interrupted - Aborting...\n");
	if ($accountstring=="MANUAL") {
		$accountstring = "";
		while ($accountstring == "") {
			echo "\033[48;5;226mEnter Manual Account - Full Account string: \033[0m";
			system("stty sane");
			$fd = fopen("PHP://Stdin","r");
			$accountstring = trim(explode("\n",fgets($fd))[0]);
			fclose($fd);
		}
	}
	return $accountstring;
}
