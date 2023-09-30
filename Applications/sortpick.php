<?php
	require_once("nicem.php");
	if (getenv("tpath") == "") die("ds Kræver du er i et regnskab\n");
	require_once("ansi-color.php");
	$oldsort = getenv("LEDGER_SORT");
	echo set("Udskifter sortering \t$oldsort","blue");
	require_once("fzf.php");
	$fzf = "";
	$fzf .= "date\namount\npayee";
	$sortmethod = fzf($fzf,"vælg sortering","--height=10 --tac --exact");
	$op = 	exec("whoami");
	file_put_contents("/home/$op/tmp/.sortpick","export LEDGER_SORT=$sortmethod\n");	
	echo set("Ny sortering:  $sortmethod\n","green");
?>

