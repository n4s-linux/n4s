<?php
$op = exec("whoami");
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$histfile = getenv("HOME")."/tmp/journal_history";
system("bash /svn/svnroot/Applications/vthist.bash list|head -n9 > $histfile");

if (!isset($argv[1])) {
	$menu["Regnskab"] = array('key'=>'r','Text'=>'Rapportering...','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Regnskab'");
	$menu["Journaler (Sager)"] = array('key'=>'c','Text'=>'Journaler (sager)','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php CRM'");
	$menu["Terminal"] = array('key'=>'T','Text'=>'🖥Terminal','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Terminal'");
	$menu["Lommeregner"] = array('key'=>'C','Text'=>'python','cmd'=>"split-window 'ipython3 --no-banner'");

	$menu["Manual"] = array('key'=>'m','Text'=>'Manual','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Manual'");
	if ($op == "joo") $menu['Ugeoverblik2'] = array('key'=>'E','Text'=>'mail','cmd'=>"new-window 'tpath=/data/regnskaber/regnskabsdeadlines/ LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger r opgaver -S date --account-width=15|less' ");
	$menu["Session"] = array('key'=>'S','Text'=>'newsession','cmd'=>"display-popup -h 4 -E 'bash /svn/svnroot/Applications/newsesv2.bash");
	$menu["Ompriortering session"] = array('key'=>'o','Text'=>'ommpriortering','cmd'=>"display-popup -h 8 -E 'bash /svn/svnroot/Applications/rename-session.bash");
//     display-popup [-BCE] [-b border-lines] [-c target-client] [-d start-directory] [-e environment] [-h height] [-s border-style] [-S style] [-t target-pane] [-T title] [-w width] [-x position] [-y position] [shell-command]
//                   (alias: popup)




}
else if ($argv[1] == "Vim") {
	$menu["Account1"] = array('key'=>'a','Text'=>'Account1','cmd'=>"send-keys \"escape\" gg/Account ENTER wwwå");
	$menu["Account2"] = array('key'=>'A','Text'=>'Account1','cmd'=>"send-keys \"escape\" gg/Account ENTER n wwwå");
	$menu["Func1"] = array('key'=>'f','Text'=>'Func1','cmd'=>"send-keys \"escape\" gg/Func ENTER wwli");
	$menu["Func2"] = array('key'=>'F','Text'=>'Func2','cmd'=>"send-keys \"escape\" gg/Func ENTER n wwli");
	$menu["Ændre dato"] = array('key'=>'d','Text'=>'Dato','cmd'=>"send-keys \"escape\" gg/Date ENTER wwlR");
	$menu["Ændre tekst"] = array('key'=>'t','Text'=>'Tekst','cmd'=>"send-keys \"escape\" gg/Description ENTER wwlå");
	$menu["Ændre bilag"] = array('key'=>'b','Text'=>'Tekst','cmd'=>"send-keys \"escape\" gg/Reference ENTER wwlå");
	$menu["Ændre kommentar"] = array('key'=>'k','Text'=>'Kommentar','cmd'=>"send-keys \"escape\" gg/Comment ENTER wwlå");
	$menu["Periodisering Start"] = array('key'=>'p','Text'=>'Pstart','cmd'=>"send-keys \"escape\" gg/P-Start ENTER wwwwli");
	$menu["Periodisering Slut"] = array('key'=>'P','Text'=>'Pslut','cmd'=>"send-keys \"escape\" gg/P-End ENTER wwwwli");
}
else if ($argv[1] == "Kommunikation") {
	$menu["Materialeindkaldelse"] = array('key'=>'m','Text'=>'materiale','cmd'=>"new-window 'php /svn/svnroot/Applications/materiale.php'");
}
else if ($argv[1] == "Automatisering") {
	$menu["Ny regel"] = array('key'=>'r','Text'=>'Ny regel','cmd'=>"send-keys 'rule' Enter");
	$menu["Kopier regel"] = array('key'=>'R','Text'=>'Kopier regel','cmd'=>"send-keys 'rule_copy' Enter");
	$menu["Kør regler"] = array('key'=>'u','Text'=>'Kør regler','cmd'=>"send-keys 'logic' Enter");
	$menu["Enable script"] = array('key'=>'i','Text'=>'Scrips','cmd'=>"send-keys 'scrips' Enter");
}
else if ($argv[1] == "Rapportering") {
	$menu["Balance [konti]"] = array('key'=>'r','Text'=>'Viser','cmd'=>"send-keys 'l b ' ");
	$menu["Udskrift [konti]"] = array('key'=>'u','Text'=>'Register','cmd'=>"send-keys 'l r ' ");
	$menu["CSV [konto]"] = array('key'=>'c','Text'=>'CSV','cmd'=>"send-keys 'l csv ' ");
}
else if ($argv[1] == "Ledger") {
	$menu["Kontokort $begin - $end"] = array('key'=>'r','Text'=>'Kontokort','cmd'=>"send-keys 'l r ' Enter");
	$imorgen = date("Y-m-d",strtotime("tomorrow"));
	$menu["Kontokort 1970-01-01 - $imorgen"] = array('key'=>'r','Text'=>'Kontokort','cmd'=>"send-keys 'll r ' Enter");
}
else if ($argv[1] == "Bogføring") {
	$menu["Balance Painter"] = array('key'=>'F','Text'=>'Farvelægning Balance','cmd'=>"send-keys 'farvelæg ' ");
	$menu["Konto Painter"] = array('key'=>'F','Text'=>'Farvelægning Kontokort','cmd'=>"send-keys 'farvelægkontokort ' ");
	$menu["Bogfør kladdeposter"] = array('key'=>'B','Text'=>'Bogføring af poster','cmd'=>"send-keys 'bookledger' Enter");
	$menu["Beta regnskabsviser"] = array('key'=>'b','Text'=>'Beta regnskabsviser','cmd'=>"send-keys 'nmenu' Enter");
	$menu["Opret postering"] = array('key'=>'p','Text'=>'Python - regnemaskine','cmd'=>"send-keys 'e' Enter");
	$menu["Eksporter til HTML/PDF"] = array('key'=>'x','Text'=>'html','cmd'=>"send-keys 'html' Enter");
	$menu["Indlæs CSV"] = array('key'=>'c','Text'=>'CSV','cmd'=>"send-keys 'csv' Enter");
	$menu["Åbne poster"] = array('key'=>'å','Text'=>'åbne','cmd'=>"send-keys 'åbneposter' Enter");
	$menu["Juster Periode"] = array('key'=>'P','Text'=>'Ændre periode','cmd'=>"send-keys 'dp' Enter");
	$menu["Juster Sortering"] = array('key'=>'S','Text'=>'Ændre periode','cmd'=>"send-keys 'ds' Enter");
	$menu["Forecasting"] = array('key'=>'f','Text'=>'forecasting','cmd'=>"send-keys 'updatebudget' Enter");
}
else if ($argv[1] == "rmenu") {
	$menu["Bogføring"] = array('key'=>'b','Text'=>'Bogføring','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Bogføring' ");
	$menu["Rapportering"] = array('key'=>'e','Text'=>'Regnskab...','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Rapportering'");
	$menu["Automatisering"] = array('key'=>'a','Text'=>'Automatisering','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Automatisering' ");
}
else if ($argv[1] == "Manual" ) {
	$menu["n4s - Bogføringssystem"] = array('key'=>'t','Text'=>'Tmux - Vinduesystem','cmd'=>"new-window 'vi /svn/svnroot/MANUAL'");
	$menu["LICENS"] = array('key'=>'l','Text'=>'LICENS','cmd'=>"new-window 'vi /svn/svnroot/LICENSE'");
	$menu["Vim - Editor"] = array('key'=>'v','Text'=>'Vim - Editor','cmd'=>"new-window 'vimtutor'");
	$menu["tmux - Vinduesystem"] = array('key'=>'u','Text'=>'Tmux - Vinduesystem','cmd'=>"new-window 'man tmux'");
}
else if ($argv[1] == "Terminal" ) {
	$menu["Åbn Terminal"] = array('key'=>'t','Text'=>'Tag screenshot','cmd'=>"new-window bash /svn/svnroot/Applications/start.bash shell");
	$menu["Tag Screenshot"] = array('key'=>'s','Text'=>'Tag screenshot','cmd'=>"run-shell 'bash /svn/svnroot/Applications/start.bash screenshot'");
	$menu["Se Screenshots"] = array('key'=>'S','Text'=>'Tag screenshot','cmd'=>"new-window 'bash /svn/svnroot/Applications/start.bash screenshots'");
}
else if ($argv[1] == "Time" ) {
	$menu["Vis Timere"] = array('key'=>'i','Text'=>'Vis timeree  - måling af tid','cmd'=>"new-window  'bash /svn/svnroot/Applications/timer.bash vis'");
	$menu["Start Timer"] = array('key'=>'I','Text'=>'Start timer- måling af tid','cmd'=>"new-window 'bash /svn/svnroot/Applications/timer.bash vis NY'");
	$menu["Tidsregistrering"] = array('key'=>'t','Tekst'=>'Tidsregistrering','cmd'=>"new-window 'bash /svn/svnroot/Applications/start.bash igangv'");
}
else if ($argv[1] == "Stats" ) {
	$menu["Dagsrapport"] = array('key'=>'d','Text'=>'Vis dagsrapport','cmd'=>"new-window 'bash /svn/svnroot/Applications/start.bash igangvbal'");
	if ($op == "joo") {
		$menu["Tidsforbrug joo"] = array('key'=>'j','Text'=>'tidsforbrugjoo','cmd'=>"display-popup -E 'bash /svn/svnroot/Applications/start.bash jootidsbal");
		$menu["Ændret tidsstatsjournal"] = array('key'=>'t','Text'=>'ændretid','cmd'=>"display-popup -E 'vi /data/regnskaber/stats/joo.stats");
	}
	$menu["Halvårsrapport"] = array('key'=>'h','Text'=>'Halvår','cmd'=>"new-window 'bash /svn/svnroot/Applications/stats.bash'");
	$menu["2 månedsrapport"] = array('key'=>'2','Text'=>'Uge','cmd'=>"new-window 'bash /svn/svnroot/Applications/stats.bash uge'");
	$menu["Kundestatistik"] = array('key'=>'k','Text'=>'Halvår','cmd'=>"new-window ' bash /svn/svnroot/Applications/stats_kunder.bash'");
}
else if ($argv[1] == "Regnskab") {
	$menu['Åbn Regnskab'] = array('key'=>'r','Text'=>'Åbn regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash regnskab');
	$menu['Nyt Regnskab'] = array('key'=>'n','Text'=>'Åbn regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash nyregnskab');
	$menu['Hurtigtransaktion'] = array('key'=>'h','Text'=>'Åbn regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash anyentry');
//	$menu['Vis Regnskab'] = array('key'=>'v','Text'=>'vis regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash hlui');
	//$menu['Bogfǿr'] = array('key'=>'b','Text'=>'Bogfǿr regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash finalize');
}

else if ($argv[1] == "history" ) {
	$home=getenv("HOME");
	if (file_exists("$home/tmp/journal_history")) {
		$i = 1;
		foreach (array_reverse(explode("\n",file_get_contents("$home/tmp/journal_history"))) as $row) {
			$col = explode("_",$row);
			$row = explode(" ",$row)[1];
			$cmd = "bash ~/tmp/vitouch/$row";
			if (!isset($col[1])) continue;
			$menu["$col[0] / $col[1]"] = array('key'=>$i++,'Text'=>"$col[0] / $col[1]",'cmd'=>"new-window  '$cmd'");                                                                                                                               
		}
	}
}
else if ($argv[1] == "CRM" ) {
	$menu['Åbn Journal'] = array('key'=>'j','Text'=>'Åbn Journal','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash business');
	$menu["Søgning"] = array('key'=>'s','Text'=>'Søg','cmd'=>"new-window -n Søgning 'bash /svn/svnroot/Applications/newsearchgrep.bash momskvartal'");

}
$cmd = 'tmux display-menu -T "#[align=middle fg=brown]n4s $bn" ';
foreach ($menu as $key => $ar) {
	$nice = str_pad($key,20);
	$cmd .= "\"$nice\" $ar[key] \"$ar[cmd]\" ";
}
//	fwrite(STDERR,$cmd."\n");
	system("$cmd");
?>
