<?php
$u = uniqid();
error_reporting(E_ALL);
$fn = $_GET['fn'];
if (file_exists("/tmp/$u-converted.png"))
	unlink ("/tmp/$u-converted.png");
//header("Content-Type: Image/PNG");
session_start();
//if (isset($_SESSION['filez'][$_GET['fn']]))
 //       die($_SESSION['filez'][$_GET['fn']]);
system("convert \"$fn\" /tmp/$u-converted.png");
if (!file_exists("/tmp/$u-converted.png"))
	die("");
//$_SESSION['filez'][$_GET['fn']] = file_get_contents("/tmp/$u-converted.png");
if (file_exists("/tmp/$u-converted.png"))
	die(file_get_contents("/tmp/$u-converted.png"));
else
	die("shit");
