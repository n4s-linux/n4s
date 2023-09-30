<?php
function plot($tpath,$account,$begin,$end,$beginytd,$endytd,$page) {
if ($page == "browse_ytd") {
	$begin = $beginytd;
	$end = $endytd;
}
else if ($page == "browse_all") {
	$begin = "1970-01-01";
	$end = "2099-12-31";
}
system("LEDGER_BEGIN=$begin LEDGER_END=$end /svn/svnroot/Applications/key_plot_web.bash \"$tpath/curl\" \"$account\"> /svn/svnroot/Applications/plog.png");
echo "<img width=100% src=plog.png>";
}
?>
