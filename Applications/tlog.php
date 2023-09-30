<?php
$op = exec("whoami") or die ("whoami program eksisterer ikke");
require_once("/svn/svnroot/Libraries/companies.php");
require_once("/svn/svnroot/Applications/finddebtorname.php");
require_once("/svn/svnroot/Libraries/PHP/db.php");
($GLOBALS["___mysqli_ston"] = mysqli_connect($config['dbhost'], $config['dbuser'], $config['dbpasswd']));
mysqli_select_db($GLOBALS["___mysqli_ston"], kdb);
$res = mysqli_query($GLOBALS["___mysqli_ston"], "select * from timetable where operator != '$op' and unix_timestamp()-300 < unix_timestamp(tidspunkt)");
while ($row = mysqli_fetch_assoc($res)) {
	$row['kundenavn'] = finddebtorname($row['kundenr']);
	system("notify-send -a t \"$row[kundenavn]\" \"$row[operator]: $row[type] - $row[tekst] ($row[minutes])\"");
	sleep(5);
}
?>
