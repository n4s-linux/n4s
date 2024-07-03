<?php
$fzf_menu = getenv("fzf_menu");
require_once("/svn/svnroot/Applications/fzf.php");
$op = exec("whoami");
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$histfile = getenv("HOME")."/tmp/journal_history";
system("bash /svn/svnroot/Applications/vthist.bash list|head -n9 > $histfile");

if (!isset($argv[1])) {
	$menu["💵 Accounting"] = array('key'=>'r','Text'=>'Rapportering...','cmd'=>"new-window 'fzf_menu=$fzf_menu php /svn/svnroot/Applications/tmux/menu.php Regnskab'");
	$menu["📑 Journalz"] = array('key'=>'c','Text'=>'Journaler (sager)','cmd'=>"new-window 'fzf_menu=$fzf_menu php /svn/svnroot/Applications/tmux/menu.php CRM'");
	$menu["🤔 Manual"] = array('key'=>'m','Text'=>'Manual','cmd'=>"new-window 'fzf_menu=$fzf_menu php /svn/svnroot/Applications/tmux/menu.php Manual'");
}
else if ($argv[1] == "Vim") {
	$menu["🤔 Headmenu"] = array('key'=>'H','Text'=>'Manual','cmd'=>"send-keys 'hm' Enter");
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
	$menu["🤔 Headmenu"] = array('key'=>'H','Text'=>'Manual','cmd'=>"send-keys 'hm' Enter");
	$menu["Materialeindkaldelse"] = array('key'=>'m','Text'=>'materiale','cmd'=>"new-window 'php /svn/svnroot/Applications/materiale.php'");
}
else if ($argv[1] == "Automatisering") {
	$menu["Ny regel"] = array('key'=>'r','Text'=>'Ny regel','cmd'=>"send-keys 'rule' Enter");
	$menu["Kopier regel"] = array('key'=>'R','Text'=>'Kopier regel','cmd'=>"send-keys 'rule_copy' Enter");
	$menu["Kør regler"] = array('key'=>'u','Text'=>'Kør regler','cmd'=>"send-keys 'logic' Enter");
	$menu["Enable script"] = array('key'=>'i','Text'=>'Scrips','cmd'=>"send-keys 'scrips' Enter");
}
else if ($argv[1] == "Rapportering") {
	$menu["Balance [Account]"] = array('key'=>'r','Text'=>'Viser','cmd'=>"send-keys 'l b ' ");
	$menu["Register [Account]"] = array('key'=>'u','Text'=>'Register','cmd'=>"send-keys 'l r ' ");
	$menu["CSV [Account]"] = array('key'=>'c','Text'=>'CSV','cmd'=>"send-keys 'l csv ' ");
}
else if ($argv[1] == "Ledger") {
	$menu["Register $begin - $end"] = array('key'=>'r','Text'=>'Kontokort','cmd'=>"send-keys 'l r ' Enter");
	$imorgen = date("Y-m-d",strtotime("tomorrow"));
	$menu["Register 1970-01-01 - $imorgen"] = array('key'=>'r','Text'=>'Kontokort','cmd'=>"send-keys 'll r ' Enter");
}
else if ($argv[1] == "Bogføring") {
	$menu["New Posting"] = array('key'=>'p','Text'=>'Python - regnemaskine','cmd'=>"send-keys 'e' Enter");
	$menu["Adjust account balance"] = array('key'=>'a','Text'=>'Python - regnemaskine','cmd'=>"send-keys 'adjust' Enter");
	$menu["Balance Painter"] = array('key'=>'F','Text'=>'Farvelægning Balance','cmd'=>"send-keys 'colorize ' ");
	$menu["Account Painter"] = array('key'=>'F','Text'=>'Kontokort','cmd'=>"send-keys 'colorizetransactions ' ");
	$menu["Book / Verify Postings"] = array('key'=>'B','Text'=>'Bogføring af poster','cmd'=>"send-keys 'book' Enter");
	$menu["Beta viewer"] = array('key'=>'b','Text'=>'Beta regnskabsviser','cmd'=>"send-keys 'nmenu' Enter");
	$menu["Export (HTML/PDF)"] = array('key'=>'x','Text'=>'html','cmd'=>"send-keys 'html' Enter");
	$menu["Import data (CSV)"] = array('key'=>'c','Text'=>'CSV','cmd'=>"send-keys 'csv' Enter");
	$menu["Open ENtries"] = array('key'=>'å','Text'=>'åbne','cmd'=>"send-keys 'åbneposter' Enter");
	$menu["Adjust Period"] = array('key'=>'P','Text'=>'Ændre periode','cmd'=>"send-keys 'dp' Enter");
	$menu["Adjust Sort"] = array('key'=>'S','Text'=>'Ændre periode','cmd'=>"send-keys 'ds' Enter");
	$menu["Forecasting"] = array('key'=>'f','Text'=>'forecasting','cmd'=>"send-keys 'updatebudget' Enter");
	$menu["Reconciliation account vs statement"] = array('key'=>'r','Text'=>'forecasting','cmd'=>"send-keys 'reconcile' Enter");
}
else if ($argv[1] == "rmenu") {
	$menu["Bookkeeping"] = array('key'=>'b','Text'=>'Bogføring','cmd'=>"send-keys 'php /svn/svnroot/Applications/tmux/menu.php Bogføring' Enter");
	$menu["Reporting"] = array('key'=>'e','Text'=>'Regnskab...','cmd'=>"send-keys 'php /svn/svnroot/Applications/tmux/menu.php Rapportering' Enter");
	$menu["Automation"] = array('key'=>'a','Text'=>'Automatisering','cmd'=>"send-keys 'php /svn/svnroot/Applications/tmux/menu.php Automatisering' Enter");
	$menu["🤔 Headmenu"] = array('key'=>'H','Text'=>'Manual','cmd'=>"send-keys 'hm' Enter");
}
else if ($argv[1] == "Manual" ) {
	$menu["n4s"] = array('key'=>'t','Text'=>'Tmux - Vinduesystem','cmd'=>"new-window 'tag=README bash /svn/svnroot/Applications/start.bash business'");
	$menu["LICENS"] = array('key'=>'l','Text'=>'LICENS','cmd'=>"new-window 'vi /svn/svnroot/LICENSE'");
	$menu["Vim - Editor"] = array('key'=>'v','Text'=>'Vim - Editor','cmd'=>"new-window 'vimtutor'");
	$menu["tmux - Vinduesystem"] = array('key'=>'u','Text'=>'Tmux - Vinduesystem','cmd'=>"new-window 'man tmux'");
}
else if ($argv[1] == "Terminal" ) {
	$menu["Åbn Terminal"] = array('key'=>'t','Text'=>'Tag screenshot','cmd'=>"new-window bash /svn/svnroot/Applications/start.bash shell");
	$menu["Tag Screenshot"] = array('key'=>'s','Text'=>'Tag screenshot','cmd'=>"new-window 'bash /svn/svnroot/Applications/start.bash screenshot'");
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
	$menu['📁 Open Account'] = array('key'=>'r','Text'=>'Åbn regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash regnskab');
	$menu['💾 New Account'] = array('key'=>'n','Text'=>'Åbn regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash nyregnskab');
	$menu['🔥 Quick Transaction'] = array('key'=>'q','Text'=>'Åbn regnskab','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash anyentry');
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
	$menu['📁 Open / Create'] = array('key'=>'j','Text'=>'Åbn Journal','cmd'=>'new-window bash /svn/svnroot/Applications/start.bash business');
	$menu["🔍 Search"] = array('key'=>'s','Text'=>'Søg','cmd'=>"new-window -n Søgning 'bash /svn/svnroot/Applications/newsearchgrep.bash momskvartal'");

}
if (getenv("fzf_menu") == "1") {
	$fzf = "";
	$cmdz = array();
	foreach ($menu as $key => $ar) {
		$nice = str_pad($key,20);
		$cmdz[trim($nice)] = $ar["cmd"];
		$fzf .= "$nice\n";
	}
	$version = trim(file_get_contents("/home/$op/tmp/.n4sversion"));
	$valg = fzf($fzf,"🦎 n4s v$version - MENU🦎",true,"--margin 2% --padding 2% --border=sharp");
	if ($valg == "")die();
	$cmd = $cmdz[$valg];
	system("export fzf_menu=1; tmux $cmd");
}
else {
	$cmd = 'tmux display-menu -T "#[align=middle fg=brown]n4s $bn" ';
	foreach ($menu as $key => $ar) {
		$nice = str_pad($key,20);
		$cmd .= "\"$nice\" $ar[key] \"$ar[cmd]\" ";
	}
	//	fwrite(STDERR,$cmd."\n");
		system("$cmd");
}
?>
