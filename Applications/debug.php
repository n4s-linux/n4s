<?php
require_once("slack.php");
require_once("current_case.php");
function debug($txt,$debug_level = 0,$tag = "",$kundenr = 0,$sagsnavn = "",$time="NOW()") {
	global $op;
    if (!isset($op))
	$op = exec("whoami");
error_reporting(0);
slack("$op, " . debug_backtrace()[1]['function'] . ": " . $txt . "($kundenr) ($sagsnavn)","#debug");
error_reporting(E_ALL);
	$path = getenv("HOME");
	if (!strlen($path))
		$path = "/svn/svnroot/tmp/";
//	$path = "/svn/svnroot/Documents/Kunder";
	$fp = $path."/.olsen_$op.log";
	if (file_exists($fp))
		file_put_contents($fp,date("Y-m-d H:i") . " $op D_$debug_level: ".$txt . "\n",FILE_APPEND);
	else
		file_put_contents($fp,date("Y-m-d H:i") . " $op D_$debug_level: ".$txt . "\n");
	error_reporting(0);
	$sqlt = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $txt) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$sqltag = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $tag) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $sagsnavn = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $sagsnavn) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$cursql = "insert into kdb.debug (tidspunkt,operator,text,debug_level,tag) values ($time,\"$op\",\"$sqlt\",$debug_level,\"$sqltag\");\n";
    if ($kundenr != 0) 
        add_current_case($kundenr,$sagsnavn,$time);
    $cursql = trim($cursql);
	file_put_contents("/$path/debug_upload_$op.txt","$cursql\n",FILE_APPEND);
	if (!mysqli_ping($GLOBALS["___mysqli_ston"])) 
			return false;
	error_reporting(E_ALL);
	if (file_exists("/$path/debug_upload_$op.txt")) {
        $rows = explode("\n",file_get_contents("/$path/debug_upload_$op.txt"));
        foreach ($rows as $row)
            if (strlen(trim($row)))
                mysqli_query($GLOBALS["___mysqli_ston"], $row) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
		if (file_exists("/$path/debug_upload_$op.txt"))
            system("rm -f /$path/debug_upload_$op.txt");
	}
}
?>
