<?php
	session_start();
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	require_once("webmenu.php");
	require_once("header.php");
	require_once("accounts.php");
	require_once("accpick.php");
	renderAccountPicker();
	if (isset($_POST["tpath"])) {
		$_SESSION["tpath"] = $_POST["tpath"];
	}
	if (!isset($_SESSION["tpath"])) 
		die("No Account selected\n");
	else
	$tpath=$_SESSION["tpath"];
	require_once("pp.php");
	renderCompactPeriodPicker();	
	if (!isset($_SESSION["begin"]))
		die("set period plz\n");
	else {
		$begin = $_SESSION["begin"];
		$end = $_SESSION["end"];
	}
	topmenu();
	if ($_GET['page'] != "") {
		$p = $_GET['page'];
		$p();
	}
?>
