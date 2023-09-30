<?php
$op = exec("whoami");
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$histfile = getenv("HOME")."/tmp/journal_history";
system("bash /svn/svnroot/Applications/vthist.bash list|head -n9 > $histfile");

if (!isset($argv[1])) {
	$menu["Journaler (Sager)"] = array('key'=>'c','Text'=>'Journaler (sager)','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php CRM'");
	$menu["Regnskab"] = array('key'=>'r','Text'=>'Rapportering...','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Regnskab'");
	$menu["Terminal"] = array('key'=>'T','Text'=>'🖥Terminal','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Terminal'");
	$menu["Kommunikation"] = array('key'=>'K','Text'=>'Komm','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Kommunikation'");
	$menu["Tid"] = array('key'=>'t','Text'=>'⌚ Tid','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Time'");
	$menu["Statistik"] = array('key'=>'s','Text'=>'Stats','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Stats'");
	$menu["Manual"] = array('key'=>'m','Text'=>'Manual','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Manual'");
	$menu["Lommeregner"] = array('key'=>'C','Text'=>'python','cmd'=>"split-window 'ipython3 --no-banner'");
	if ($op == "joo") $menu['Review'] = array('key'=>'R','Text'=>'Review','cmd'=>"new-window -n \#review 'bash /svn/svnroot/Applications/reviewtransactions.bash' ");
	if ($op == "joo") $menu['RTMail'] = array('key'=>'a','Text'=>'mail','cmd'=>"new-window -n \#rtmails 'vi ~/tmp/rt.ls' ");
	//if ($op == "joo") $menu['Book'] = array('key'=>'W','Text'=>'mail','cmd'=>"display-popup -w 115 -E 'bash /svn/svnroot/Applications/start.bash cal' ");
	//if ($op == "joo") $menu['Kalender'] = array('key'=>'C','Text'=>'mail','cmd'=>"display-popup -w 115 -E 'bash /svn/svnroot/Applications/start.bash calshow' ");
	if ($op == "joo") $menu['Log'] = array('key'=>'L','Text'=>'log','cmd'=>"new-window -n logview 'bash /svn/svnroot/Applications/log.bash' ");
//	if ($op == "joo") $menu['Ugeoverblik'] = array('key'=>'E','Text'=>'mail','cmd'=>"new-window 'tpath=~/regnskaber/kalender/ LEDGER_BEGIN=1970/1/1 LEDGER_END=$(date +%Y-%m-%d --date=+8\ weeks) php /svn/svnroot/Applications/key.php ledger print -S date> ~/tmp/kal.ledger;hledger -f ~/tmp/kal.ledger b -W Opgaver|less' ");
	if ($op == "joo") $menu['Ugeoverblik2'] = array('key'=>'E','Text'=>'mail','cmd'=>"new-window 'tpath=/data/regnskaber/regnskabsdeadlines/ LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger r opgaver -S date --account-width=15|less' ");
	if ($op == "joo") $menu['AddTime⌛'] = array('key'=>'w','Text'=>'emoji','cmd'=>"rename-window '#W⌛'");
	if ($op == "joo") $menu['RemTime⌛'] = array('key'=>'W','Text'=>'emoji','cmd'=>"run-shell 'bash /svn/svnroot/Applications/remwm.bash'");



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
	$menu["Ændre outputmetode"] = array('key'=>'m','Text'=>'Metode','cmd'=>"send-keys 'changetermcmd' Enter");
	$menu["Opret postering"] = array('key'=>'p','Text'=>'Python - regnemaskine','cmd'=>"send-keys 'e' Enter");
	$menu["Regnskabsviser"] = array('key'=>'r','Text'=>'Viser','cmd'=>"send-keys 'bash /svn/svnroot/Applications/csvdatakey.bash' Enter");
	$menu["Indlæs CSV"] = array('key'=>'c','Text'=>'CSV','cmd'=>"send-keys 'csv' Enter");
	$menu["Åbne poster"] = array('key'=>'å','Text'=>'Afstemning','cmd'=>"send-keys 'reconcile' Enter");
	$menu["Juster Periode"] = array('key'=>'P','Text'=>'Ændre periode','cmd'=>"send-keys 'dp' Enter");
	$menu["Juster Sortering"] = array('key'=>'P','Text'=>'Ændre periode','cmd'=>"send-keys 'ds' Enter");
	$menu["Debitorafstemning"] = array('key'=>'d','Text'=>'debtorafstemning','cmd'=>"send-keys 'debtorreport' Enter");
}
else if ($argv[1] == "Budget") {
	$menu["Rediger budget"] = array('key'=>'R','Text'=>'Edit','cmd'=> "send-keys 'bash /svn/svnroot/Applications/budget.bash' Enter ");
	$menu["Gem budget"] = array('key'=>'G','Text'=>'SAve','cmd'=> "send-keys 'bash /svn/svnroot/Applications/budget.bash print > \$tpath/budget.ledger' Enter");

}
else if ($argv[1] == "rmenu") {
	$menu["Bogføring"] = array('key'=>'b','Text'=>'Bogføring','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Bogføring' ");
	$menu["Budget"] = array('key'=>'u','Text'=>'Budget','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Budget' ");
	$menu["Rapportering"] = array('key'=>'e','Text'=>'Regnskab...','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Rapportering'");
	$menu["Automatisering"] = array('key'=>'a','Text'=>'Automatisering','cmd'=>"run-shell 'php /svn/svnroot/Applications/tmux/menu.php Automatisering' ");
	$menu["Fejlkonto"] = array('key'=>'f','Text'=>"Fejlkonto",'cmd'=>"send-keys 'php /svn/svnroot/Applications/fk.php' Enter");
	$menu["Årsafslutning / afstemning"] = array('key'=>'å','Text'=>'Periodeafslutning','cmd'=>"send-keys 'bash /svn/svnroot/Applications/yearend.bash' Enter");
}
else if ($argv[1] == "Manual" ) {
	$menu["LICENS"] = array('key'=>'l','Text'=>'LICENS','cmd'=>"new-window 'vi /svn/svnroot/LICENSE'");
	$menu["n4s - Bogføringssystem"] = array('key'=>'t','Text'=>'Tmux - Vinduesystem','cmd'=>"new-window 'vi /svn/svnroot/MANUAL'");
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
	$menu["Søgning"] = array('key'=>'M','Text'=>'mail','cmd'=>"display-popup -E -w 140 'bash /svn/svnroot/Applications/sogmail.bash'");
	$menu["Mine seneste åbne journaler"] = array('key'=>'s','Text'=>'Seneste opgaver','cmd'=>"new-window 'bash /svn/svnroot/Applications/vthist.bash'");
	$menu["Mine opgaver"] = array('key'=>'o','Text'=>'Vis mine opgaver','cmd'=>"new-window  'bash /svn/svnroot/Applications/start.bash mine'");                                                                                                                               
	$menu["Søg Journal"] = array('key'=>'J','Text'=>'Søg Journal','cmd'=>"new-window 'bash /svn/svnroot/Applications/start.bash grepsearchtag'");                                                                                                                             
	//$menu["Blip"] = array('key'=>'b','Text'=>'Lav et hurtigt blip på et tag 🕊','cmd'=>"split-window -p25  'bash /svn/svnroot/Applications/blip.bash'");

}
$cmd = 'tmux display-menu -T "#[align=middle fg=brown]n4s $bn" ';
foreach ($menu as $key => $ar) {
	error_reporting(0);
	$key['cmd'] = trim($key['cmd']);
	error_reporting(E_ALL);
	$nice = str_pad($key,20);
	$cmd .= "\"$nice\" $ar[key] \"$ar[cmd]\" ";
}
	system("$cmd");
?>
