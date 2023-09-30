<?php
require_once("xml.php");
date_default_timezone_set('Europe/Copenhagen');
require_once("menu.php");
require_once("kontokort.php");
require_once("balance.php");
$xml = new DOMDocument(); 
$regnskabsfil = "/home/joo/Regnskabsfil.xml";
$xml->load($regnskabsfil);
if (!$xml->schemaValidate('./xml.xsd')) { 
	libxml_display_errors();
   die("invalid xml document");
} 
$xml = simplexml_load_file($regnskabsfil);
switch ($argv[1]) {
	case 'balance': {
		$start = getdato("Indtast startdato");
		$slut = getdato("Indtast slutdato");
		if ($start == "")
			$start = "1986-12-25";
		if ($slut == "")
			$slut = date("Y-m-d");
		balance($regnskabsfil,$xml,0,99999999,$start,$slut);
		balance_html($xml,balance($regnskabsfil,$xml,0,99999999,$start,$slut),"$start-$slut");
		execute("w3m /svn/svnroot/tmp/rap.html");
		break;
	}
	case 'kontokort': {
		$start = getdato("Indtast startdato");
		$slut = getdato("Indtast slutdato");
		if ($start == "")
			$start = "1986-12-25";
		if ($slut == "")
			$slut = date("Y-m-d");
		balance($regnskabsfil,$xml,0,99999999,$start,$slut);
		kontokort_html(kontokort($xml,0,99999999,$start,$slut),"$start-$slut");
		execute("w3m /svn/svnroot/tmp/rap.html");
		break;
	}
	case 'complete': {
		$start = getdato("Indtast startdato");
                $slut = getdato("Indtast slutdato");
                if ($start == "")
                        $start = "1986-12-25";
                if ($slut == "")
                        $slut = date("Y-m-d");
                balance($regnskabsfil,$xml,0,99999999,$start,$slut);
		balance_html($xml,balance($regnskabsfil,$xml,0,99999999,$start,$slut),"$start-$slut");
		$bal = file_get_contents("/svn/svnroot/tmp/rap.html");
                kontokort_html(kontokort($xml,0,99999999,$start,$slut),"$start-$slut");	
		file_put_contents("/svn/svnroot/tmp/rap.html",$bal."\n\n".file_get_contents("/svn/svnroot/tmp/rap.html"));
		execute("w3m /svn/svnroot/tmp/rap.html");
		break;
	}
}
function execute($cmd) {
$descriptors = array(
array('file', '/dev/tty', 'r'),
array('file', '/dev/tty', 'w'),
array('file', '/dev/tty', 'w')
);
$process = proc_open("$cmd", $descriptors, $pipes);
proc_close($process);
}
function getdato($spm) {
	$fd = fopen("php://stdin","r");
	echo "$spm (YYYY-mm-dd): ";
	$retval = trim(str_replace("\n","",fgets($fd)));
	fclose($fd);
	return $retval;
}
