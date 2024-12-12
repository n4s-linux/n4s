<?php 
date_default_timezone_set("Europe/Copenhagen");
$op = exec("whoami");
$uid = date("Y-m-d-H-i") . $op ;
$data["Filename"] = $uid . ".trans";
if (is_dir("/home/$op/regnskaber/vimtime")) {
	$fn = "/home/$op/regnskaber/vimtime/$data[Filename]";
	if (file_exists($fn)) die();
	touch("/home/$op/regnskaber/vimtime/.last");
	$fn = "/home/$op/regnskaber/vimtime/$data[Filename]";
	$data["Date"] = date("Y-m-d");
	$sess = trim(file_get_contents("/home/$op/tmp/.cws"));
	$data["Description"] = "@ " .date("H:i") . "$argv[1] @ $sess";
	$data["Transactions"][0]["Amount"] = 0.016667;
	$data["Transactions"][0]["Func"] = "";
	$data["Transactions"][0]["Account"] = "Time:" . $argv[1];
	$data["Transactions"][1]["Amount"] = -0.016667;
	$data["Transactions"][1]["Func"] = "";
	$data["Transactions"][1]["Account"] = "Fejlkonto:Ved ikke";
	$data["UID"] = $uid;
	$data["Reference"] = "AUTO";
	file_put_contents($fn,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	file_put_contents("/home/$op/regnskaber/vimtime/.last",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	file_put_contents("/home/$op/regnskaber/vimtime/.debug.log", date("Y-m-d H:i:s") . " - PID: " . getmypid() . "\n", FILE_APPEND);
}
