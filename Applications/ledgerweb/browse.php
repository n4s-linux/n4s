<?php
require_once("header.php");

$acc = $_GET['acc'];
require_once("config.php");
require_once("ledger.php");
//function runledger($datafile = null,$call = 'bal', $begin= '2018-05-01', $end = '2018-05-31',$beginytd='2018-01-01',$endytd='2018-05-31') {
echo "<pre>";
print_r(browse($datafile,"reg \"$acc\""));
?>
