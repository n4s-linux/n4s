<?php
require_once("finddebtorname.php");
require_once("ansi-color.php");
if (isset($argv[1]) && $argv[1] == "print_cases") {
require_once("/svn/svnroot/Libraries/companies.php");
($GLOBALS["___mysqli_ston"] = mysqli_connect($config['dbhost'], $config['dbuser'], $config['dbpasswd'])) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
mysqli_select_db($GLOBALS["___mysqli_ston"], $config['dbname']) or die( mysqli_error($GLOBALS["___mysqli_ston"]));
	current_cases();
}
use PhpAnsiColor\Color;
if (!isset($op)) 
	$op = exec("whoami");
//error_log(Color::set("Success", "green+bold") . " Something was successful!");
function add_current_case($kundenr,$sagsnavn,$time='NOW()') {
    global $op;
    $q = "select * from timetable where kundenr=$kundenr and minutes > 0 and tidspunkt > now() - interval 5 minute";
    $res = mysqli_query($GLOBALS["___mysqli_ston"], $q) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
    if (mysqli_num_rows($res))
        return;
    $q = "select * from current_case where kundenr=$kundenr and sagsnavn='$sagsnavn';";
    $res = mysqli_query($GLOBALS["___mysqli_ston"], $q) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
    if (!mysqli_num_rows($res))
        mysqli_query($GLOBALS["___mysqli_ston"], "insert into current_case (kundenr,operator,sagsnavn,timestamp) values ($kundenr,'$op','$sagsnavn',$time);\n");
}
function current_case_delete($kundenr) {
    global $op;
    if (!isset($op)) 
        $op = exec("whoami");
    mysqli_query($GLOBALS["___mysqli_ston"], "delete from current_case where operator='$op' and kundenr=$kundenr") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}
function current_cases() {
global $op;
if (file_exists("/svn/svnroot/tmp/terminalsize_$op"))
	$oldsize = file_get_contents("/svn/svnroot/tmp/terminalsize_$op");
else
	$oldsize = 0;
if ($op != "joo")
    $res = mysqli_query($GLOBALS["___mysqli_ston"], "select * from current_case where operator='$op' and timestamp >= NOW() - interval 18 hour") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
else
    $res = mysqli_query($GLOBALS["___mysqli_ston"], "select * from current_case where timestamp >= NOW() - interval 18 hour") or die(mysqli_error($GLOBALS["___mysqli_ston"]));

    $i = 0;
    $length = 0;
    $more = false;
    while (($row = mysqli_fetch_assoc($res))) {
	if ($i == 0) { echo Color::set("IGANGVÆRENDE ARBEJDER LIGE NU:\n","yellow+underline"); }
	require_once("relative_time.php");
	$time = time2str(strtotime($row['timestamp']));
	$str = "\t" . $row['operator'] . " => " . finddebtorname($row['kundenr']) . " / $row[sagsnavn] ($time)\n";
	echo Color::set($str,"blue+italic");
	if (strlen($str) > $length)
	    $length = strlen($str);
        $i++;
    }
    if ($i != 0) {
    //    echo str_pad("",$length,"-") . "\t\n";
    }
    else
	echo Color::set("INGEN igangværende arbejder lige nu\n","yellow+underline");
    file_put_contents("/svn/svnroot/tmp/terminalsize_$op",$i +3);
    if ($oldsize != $i +3) {
	file_put_contents("/svn/svnroot/tmp/terminal_updated_$op",1);
    }
		

}
?>
