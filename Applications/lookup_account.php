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
	$cmd = "tmux display-popup -E \"(ledger -f $path/curl accounts;cat /svn/svnroot/Libraries/Kontoplan.txt)|fzf > $path/.acclookup --header='vælg konto for $alias'\"";
	exec_app($cmd);
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
