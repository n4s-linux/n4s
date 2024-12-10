<?php 
$op = exec("whoami");
$uid = date("Y-m-d") . $op . "_". uniqid();
$data["Filename"] = $uid . ".trans";
if (is_dir("/home/$op/regnskaber/vimtime")) {
if (file_exists($file = "/home/" . trim(shell_exec('whoami')) . "/regnskaber/vimtime/.last") && (time() - filemtime($file)) < 60) die();
	$fn = "/home/$op/regnskaber/vimtime/$data[Filename]";
	$data["Date"] = date("Y-m-d");
	$sess = trim(file_get_contents("/home/$op/tmp/.cws"));
	$data["Description"] = "@ " .date("H:i") . "$argv[1] @ $sess";
	$data["Transactions"][0]["Amount"] = 0.0166;
	$data["Transactions"][0]["Func"] = "";
	$data["Transactions"][0]["Account"] = "Time:" . $argv[1];
	$data["Transactions"][1]["Amount"] = -0.0166;
	$data["Transactions"][1]["Func"] = "";
	$data["Transactions"][1]["Account"] = "Fejlkonto:Ved ikke";
	$data["UID"] = $uid;
	$data["Reference"] = "AUTO";
	file_put_contents($fn,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	file_put_contents("/home/$op/regnskaber/vimtime/.last",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
