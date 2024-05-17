<?php
require_once("proc_open.php");
function fzf($list,$header = "",$flags = "",$cols = false,$trim = true) {
	$op = posix_getpwuid(posix_geteuid())['name'];
	if ($trim) $list = trim($list);
	file_put_contents("/home/$op/tmp/list",$list);
	$cmd = "/usr/bin/echo -e \"" . $list;
	if (!$cols)
		$cmd .= "\"|fzf --header=\"$header\" --ansi $flags> /home/$op/tmp/fzf";
	else
		$cmd .= "\"|column -ts $'\t'|fzf --header=\"$header\" $flags> /home/$op/tmp/fzf";
	exec_app($cmd);
	file_put_contents("/home/$op/tmp/fejl",$cmd);
	//$d = explode("\n",file_get_contents("/home/$op/tmp/fzf"))[0]; // POTENTIAL BIG PRAWBLEM THIS USED TO WORK
	$d = file_get_contents("/home/$op/tmp/fzf");
	if ($trim) $d = trim($d);
	
//	unlink("/home/$op/tmp/fzf");
	return trim(removeNonPrintableCharacters($d));
}
function removeNonPrintableCharacters($string) {
    // Remove ANSI escape codes
    $string = preg_replace('/\033\[[\d;]*m/', '', $string);
    
    return $string;
}
?>
