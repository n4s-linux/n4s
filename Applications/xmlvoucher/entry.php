<?php
$op = $_SERVER['LOGNAME'];
date_default_timezone_set('Europe/Copenhagen');
require_once("menu.php");
require_once("history.php");
$regnskabsfil = selectregnskab();
heltforfra:
$xml = new DOMDocument(); 
$xml->load($regnskabsfil);

if (!$xml->schemaValidate('./xml.xsd')) { 
   die("invalid xml document");
} 
	$sxe = simplexml_load_file($regnskabsfil);
	$handle = fopen("php://stdin","r");
	echo "Dato: ";
	$dato = trim(str_replace("\n","",fgets($handle)));
	if ($dato == "")
		$dato = date("Y-m-d");
	echo "Bilagsnr: ";
	$bilagsnr = str_replace("\n","",fgets($handle));
	if ($bilagsnr == "")
		$bilagsnr = "9999";
	echo "Tekst: ";
	$tekst = str_replace("\n","",fgets($handle));
	$historisk_kontering = get_history($sxe,$tekst);
	//print_r($historisk_kontering);
	$voucher = $sxe->vouchers->addChild("voucher");
	$voucher->addAttribute('entrytime',date('YmdTG:H'));
	$voucher->addAttribute('operator',$op);
	$voucher->addAttribute('id',$sxe->count());
	$voucher->addAttribute('bilagsdato',$dato);
	$voucher->addAttribute('bilagsnr',$bilagsnr);
	$voucher->addAttribute('tekst',$tekst);
	onemore:
	$balance = 0;
	$start = 0;
	while (round(floatval($balance),2) <> 0 || $start == 0) {
		$currententry = $voucher->addChild("entry");
		$defaultkto = ($start == 0) ? $historisk_kontering['konto'] : $historisk_kontering['modkonto'];	
		$start++;
		if ($defaultkto == "")
			echo "Kontering: ";
		else
			echo "Kontering ($defaultkto): ";
		$currententry['kontering'] = str_replace("\n","",fgets($handle));
		if (($currententry['kontering'] == "." || $currententry['kontering'] == "") && $defaultkto != "")
			$currententry['kontering'] = $defaultkto;
		echo "Indtast beløb: (balance $balance)";
		$belob=str_replace(",",".",fgets($handle));
		if ($belob[0] == "." && $balance != 0) {
			$belob = -floatval($balance);
		}
		else {
			while (floatval($belob) != $belob ) {
					echo "Indtast beløb: (balance $balance)";
					$belob = str_replace(",",".",fgets($handle));
					if ($belob[0] == "." && $balance != 0) {
						$belob = -floatval($balance);
						break;
					}
			}
		}
		$belob = floatval($belob);
		$currententry['beløb'] = $belob;
		$defaultmoms = ($start == 1) ? $historisk_kontering['momskode'] : "";
		$defaultmoms2 = ($start == 2) ? $historisk_kontering['momskode2'] : "";
		$defaultmoms = $defaultmoms2 . $defaultmoms;
		if ($defaultmoms == "")
			echo "Momskode: ";
		else
			echo "Momskode ($defaultmoms): ";
		$currententry['momskode'] = str_replace("\n","",fgets($handle));
		if ($currententry['momskode'] == ".")
			$currententry['momskode'] = $defaultmoms;
		if ($currententry['momskode'] != "") {
			$momsentry = $voucher->addChild("entry");
			$momsentry->addAttribute('kode',"moms");
			$momsentry->addAttribute('tekst',$tekst . "(momspostering)");
			switch ($currententry['momskode']) {
				case "i": {
					$momsentry['kontering'] = "Indgående moms";
					$momsentry['beløb'] = floatval($currententry['beløb']) / 125 * 25;
					$balance += floatval($momsentry['beløb']);
					$currententry['beløb'] = floatval($currententry['beløb']) / 125 * 100;
					break;
				}
				case "u": {
					$momsentry['kontering'] = "Udgående moms";
					$momsentry['beløb'] = floatval($currententry['beløb']) / 125 * 25;
					$balance += floatval($momsentry['beløb']);
					$currententry['beløb'] = floatval($currententry['beløb']) / 125 * 100;
					break;
				}
				default: {
					die("Ukendt momskode");
				}
			}
		}
		$balance += floatval($currententry['beløb']);
		printf("balance: %f:\n", floatval($balance));
	}
	echo "bilag balancerer - indtast flere posteringer på bilag? (j/n): ";
	$yn = str_replace("\n","",fgets($handle));
	if ($yn == "j")
		goto onemore;
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($sxe->asXML());
$dom->save($regnskabsfil);
echo "skal vi taste flere bilag ind? (j/n): ";
	$yn = str_replace("\n","",fgets($handle));
	if ($yn == "j")
		goto heltforfra;
fclose($handle);
?>
