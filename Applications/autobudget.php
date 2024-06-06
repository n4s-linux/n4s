<?php
$stop = date("Y-m-d",strtotime("+12 months"));
$tpath = getenv("tpath");
if ($tpath == "") die("autobudget requires tpath\n");
$budgetperiod = array("Daily","Monthly","Quarterly","Yearly","Once");
$csv = array();
foreach ($budgetperiod as $curbudgetperiod) {
	ob_start();
	system("ls $tpath/.autoacc/$curbudgetperiod");
	$csv[$curbudgetperiod] = explode("\n",trim(ob_get_clean()));
}
foreach ($csv as $curperiod=>$data) {
	runbudget($curperiod,$data);
}
function runbudget($curperiod,$data) {
	foreach ($data as $curdata) {
		if ($curdata == "") continue;
		$curperiod($data);
	}
}
function Daily($data) {
	ptrans($data,"days",1,"Daily");
}
function Weekly($data) {
	ptrans($data,"weeks",1,"Weekly");
}
function Monthly($data) {
	ptrans($data,"months",1,"Monthly");
}
function Quarterly($data) {
	ptrans($data,"months",3,"Quarterly");
}
function Yearly($data) {
	ptrans($data,"years",1,"Yearly");
}
function ptrans($data,$p,$num=1,$folder) {
	global $stop;
	global $tpath;
	foreach ($data as $cdata) {
		if ($cdata == "") continue;
		$j = json_decode(file_get_contents("$tpath/.autoacc/$folder/$cdata"),true);
		$now = date("Y-m-d",strtotime($j["Date"] . "+$num $p"));
		while (strtotime($now) <= strtotime($stop)) {
			$now = date("Y-m-d",strtotime(date("Y-m-d",strtotime($now)) . "+1 day"));
			mktrans($j,$now);
		}
	}
}
function mktrans($trans,$date) {
	if (!isset($trans["Filename"])) {
		print_r($trans);die();
	}
	$trans["Date"] = $date;
	$trans["SourceFile"] = $trans["Filename"];
	$trans["Filename"] = "budget_" . md5(json_encode($trans)) . "_$date";
	$trans["History"][] = array("date"=>date("Y-m-d H:i"),"op"=>exec("whoami"),"Desc"=>"Created budgetpost");
	if (strtotime($date) > time()) {
		unset($trans["Filename"]);
		mkvirt($trans);
	}
	else {
		mkreal($trans);
	}
}
function mkvirt($trans) {
	global $tpath;
	print_r($trans);
}
function mkreal($trans) {
	global $tpath;
	print_r($trans);
}
