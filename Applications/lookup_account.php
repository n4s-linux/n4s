<?php 
require_once("/svn/svnroot/Applications/print_array.php");
function lookup_acc($accounts,$bal,$alias = "",$multi = "--multi") {
	$path = getenv("tpath");
	$accountstring = "";
	ob_start();
	$cmd = ("((updatealiases= LEDGER_BEGIN=1970-01-01 LEDGER_END=2099-12-31 noend=1 color=none php /svn/svnroot/Applications/newl.php accounts;cat /svn/svnroot/Libraries/Kontoplan.txt)|sort|uniq|grep :;echo NY;echo 'Fejlkonto:Ved ikke')|fzf --ansi --tac --header-first --header=\"$alias\" --scrollbar=* $multi --margin 1% --padding 1% --border --preview-label='Seneste posteringer' --preview-window 35%:bottom --preview 'LEDGER_SORT=date LEDGER_PAYEE_WIDTH=15 updatealiases= color=none php /svn/svnroot/Applications/newl.php register ^\"{}\" |tac'");
	system($cmd);
	$accountstring = trim(ob_get_clean());
	if ($accountstring == "") die("Afbrudt lookup_account intet valgt");
	if ($accountstring=="NY") {
		echo "Ingen konto valgt ved opslag...\n";
		$accountstring = "";
		while ($accountstring == "") {
			echo "Indtast manuel konto: ";
			$fd = fopen("PHP://stdin","r");
			system("stty sane");
			$accountstring = trim(explode("\n",fgets($fd))[0]);
			fclose($fd);
		}
	}
	return $accountstring;
}
