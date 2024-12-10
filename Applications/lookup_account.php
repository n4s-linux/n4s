<?php 
require_once("/svn/svnroot/Applications/print_array.php");
function lookup_acc($accounts,$bal,$alias = "",$multi = "--multi") {
	$path = getenv("tpath");
	$af = "$path/aliases";
	if (file_exists($af)) $af = json_decode(file_get_contents($af),true); else $af = array();
	$accountstring = "";
	ob_start();
	$aout="";
	if (!empty($af)) {
	foreach ($af as $curalias => $curacc) {
		$aout .= "🔥 $curalias => $curacc\n";
	}
	}
	$op = exec("whoami");
	require_once("/svn/svnroot/Applications/proc_open.php");
	file_put_contents("/home/$op/tmp/aliases.out",$aout);
	$l = getenv("lang");
	if ($l == "");
	$cmd = ("((cat ~/tmp/aliases.out;updatealiases= noend=1 LEDGER_BEGIN=1900-01-01 LEDGER_END=2099-12-31 color=none noemoji=1 php /svn/svnroot/Applications/newl.php accounts;cat /svn/svnroot/Libraries/Kontoplan.txt|/svn/svnroot/Applications/bookify.bash;cat ~/tmp/aliases.out)|sort|uniq|grep :;echo 🌠MANUAL;echo '🤔Fejlkonto:Ved Ikke')|fzf --bind 'Alt-e:execute(php /svn/svnroot/Applications/AccGuide.php Edit {} </dev/tty)' --ansi --tac --header-first --header=\"$alias\" --scrollbar=* $multi --border --preview-label='Details' --preview-window 50%:top --preview 'guide=\"{}\" tpath=$path php '/svn/svnroot/Applications/AccGuide.php' {} |tac'");
	$cmd = ("((cat ~/tmp/aliases.out;updatealiases= noend=1 LEDGER_BEGIN=1900-01-01 LEDGER_END=2099-12-31 color=none noemoji=1 php /svn/svnroot/Applications/newl.php accounts;cat /svn/svnroot/Libraries/Kontoplan_$l.txt|/svn/svnroot/Applications/bookify.bash;cat ~/tmp/aliases.out)|sort|uniq|grep :;echo 🌠MANUAL;echo '🤔Fejlkonto:Ved Ikke')|fzf --bind 'Alt-e:execute(php /svn/svnroot/Applications/AccGuide.php Edit {} </dev/tty)' --ansi --tac --header-first --header=\"$alias\" --scrollbar=* $multi --border --preview-label='Details' --preview-window 50%:top --preview 'guide=\"{}\" tpath=$path php '/svn/svnroot/Applications/AccGuide.php' {} |tac'");
	system($cmd);
	$accountstring = trim(ob_get_clean());
	$accountstring = str_replace("🔥","",$accountstring);
	$accountstring = str_replace("📑","",$accountstring);
	$accountstring = str_replace("🌠","",$accountstring);
	$accountstring = str_replace("🤔","",$accountstring);
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
