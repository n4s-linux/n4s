<?php 
function lookup_acc($accounts,$bal,$alias = "") {
	global $path;
	$hist = "$path/.acchist";
    system("stty -icanon -echo");
	$curpos = $accounts;
	$accountstring = "";

	//system("clear");
	$v = $curpos;
	$keyz = print_array($curpos);
	$lvl = 0;
	exec_app("((echo NY; cat /svn/svnroot/Libraries/Kontoplan.txt ;LEDGER_DEPTH=999 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 ledger -f $path/curl accounts)|sed 's/^ *//;s/ *$//'|sed 's/\//\./g')|sort|uniq|fzf --history=\"$hist\" --preview-window=bottom --header=\"Vælg konto *** $alias balance *** =  $bal\" --preview=\"LEDGER_DEPTH=999 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31; echo Transactioner;ledger -f $path/curl r \"{}\$\"|tail -n10;   echo Kilde;ledger -f $path/curl r --related \"{}\"|tail -n10;echo Ugentligt;ledger -f $path/curl r \"{}\" -W|tail -n10\" > $path/.acclookup");
	$accountstring = trim(file_get_contents($path."/.acclookup"));
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
