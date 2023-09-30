<?php
$path = "/home/joo/transactions";
if (strlen(getenv("tpath")))
		$path = getenv("tpath");
$datafile =  "$path/curl";

if (!isset($argv[1]))
	$period = "month";
else
	$period = $argv[1];
if ($period == "month") {
        $beginytd = date("Y") . "-01-01";
        $endytd = date("Y-m-d",strtotime("tomorrow"));
        $begin = date("Y-m") . "-01";
        $end = date("Y-m-d",strtotime("tomorrow"));
}
if ($period == "week") {
        $beginytd = date("Y") . "-01-01";
        $endytd = date("Y-m-d",strtotime("tomorrow"));
        $begin = date("Y-m-d",strtotime("-1 week"));
        $end = date("Y-m-d",strtotime("tomorrow"));
}
if ($period == "day") {
        $beginytd = date("Y") . "-01-01";
        $endytd = date("Y-m-d",strtotime("tomorrow"));
        $begin = date("Y-m-d");
        $end = date("Y-m-d",strtotime("tomorrow"));
}



$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$beginytd = date("Y") . "-01-01";
$endytd = date("Y-m-d",strtotime("tomorrow"));
?>
