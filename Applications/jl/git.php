<?php
function git_diff() {
	global $bookspath;
	global $filnavn_short;
				save(true);
				$hash = console_input("Indtast commit nummer, eller tryk enter for aktuel diff");
				if (!strlen($hash))
					exec_app("cd '$bookspath';git diff '$filnavn_short'|less");
				else {
					echo "git diff $hash '$filnavn_short'\n";
					exec_app("cd '$bookspath';git diff $hash '$filnavn_short'|less");
				}
}
function glog() {
	global $bookspath;
	global $filnavn_short;
	echo "git log '$filnavn_short'\n";
	exec_app("cd '$bookspath';git log '$filnavn_short'|less");
}
function pull() {
	global $bookspath;
	exec_app("cd '$bookspath';git pull");
}
function commit() {
	save(true);
	global $bookspath;
	global $filnavn_short;
	$besked = console_input("Commit besked");
	if (!strlen($besked)) {
		echo "Blev ikke committet p.g.a. manglende besked";
	}
	$op = exec("whoami");
	exec_app("cd '$bookspath';git config --local credential.helper \"cache --timeout=3600\";git config --local user.name $op;git config --local user.email $op@olsensrevision.dk;git add '$filnavn_short';git commit '$filnavn_short' -m '$besked';git push");
}
?>
