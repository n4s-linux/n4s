<?php 
require_once("/svn/svnroot/Applications/print_array.php");
function lookup_acc($accounts,$bal,$alias = "",$multi = "--multi") {
	$path = getenv("tpath");
	$hist = "$path/.acchist";
    system("stty -icanon -echo");
	$curpos = $accounts;
	$accountstring = "";

	//system("clear");
	$v = $curpos;
	$keyz = print_array($curpos);
	$lvl = 0;
	ob_start();
	$cmd = ("(php /svn/svnroot/Applications/newl.php accounts;cat /svn/svnroot/Libraries/Kontoplan.txt)|sort|uniq|fzf --header='vælg konto for $alias' --scrollbar=* $multi --margin 1% --padding 1% --border --preview-label='Seneste posteringer' --preview-window 55%:bottom --preview 'LEDGER_SORT=date LEDGER_PAYEE_WIDTH=15 php /svn/svnroot/Applications/newl.php register ^\"{}\" |tac'");
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
	else {
		$curpos = $accountstring;
		$lvl++;
	}

		$v = $curpos;
		//echo "$accountstring\n";

	


    system("stty sane");
	return $accountstring;
}
