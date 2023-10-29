<?php
$tag = "gammelholmcykler";
$op = exec("whoami");
$bn = "/data/regnskaber/transactions_crm/.tags";
$fn = "$bn/$tag";
require_once("/svn/svnroot/Applications/short.php");
$header = fgc("/data/regnskaber/transactions_crm/.tags/htmlheader");
$h = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">';
require_once("/svn/svnroot/Applications/proc_open.php");
exec_app("cd $bn;vim -c TOhtml -c zR -c wqa \"$fn\"");
$data = fgc("$fn.html");
unlink("$fn.html");
$h .= $data;
file_put_contents("/home/$op/tmp/out.html", $h);

?>
