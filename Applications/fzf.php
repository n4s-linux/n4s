<?php
require_once("proc_open.php");
function fzf($list,$header = "",$flags = "",$cols = false) {
	$op = posix_getpwuid(posix_geteuid())['name'];
	$list = trim($list);
	file_put_contents("/home/$op/tmp/list",$list);
	$cmd = "echo \"" . str_replace("\n","\\n",$list);
	if (!$cols)
		$cmd .= "\"|fzf --header=\"$header\" --ansi $flags> /home/$op/tmp/fzf";
	else
		$cmd .= "\"|column -ts $'\t'|fzf --header=\"$header\" $flags> /home/$op/tmp/fzf";
	exec_app($cmd);
	file_put_contents("/home/$op/tmp/fejl",$cmd);
	//$d = explode("\n",file_get_contents("/home/$op/tmp/fzf"))[0]; // POTENTIAL BIG PRAWBLEM THIS USED TO WORK
	$d = trim(file_get_contents("/home/$op/tmp/fzf"));
//	unlink("/home/$op/tmp/fzf");
	return trim($d);
}
?>
