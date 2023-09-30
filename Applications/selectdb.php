<?php
require_once("xmlvoucher/CLInput.php");
$input = new CLInput('Vælgdatabaser', 'Press Ctrl-C to quit');
require_once("../Libraries/PHP/db.php");
($GLOBALS["___mysqli_ston"] = mysqli_connect($dbhost, $dbuser, $dbpasswd)) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$res = mysqli_query($GLOBALS["___mysqli_ston"], "show databases");
$i = 0;
while ($row = mysqli_fetch_assoc($res)) {
	$thelist[$i++] = $row['Database'];
}
$option = $input->select($thelist,"chose db");
$input->done();
file_put_contents("/svn/svnroot/tmp/mysqldb",$thelist[$option]);
?>
