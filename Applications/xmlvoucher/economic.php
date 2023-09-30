<?php include("../regnskab2.0/economic.php");
include("/svn/svnroot/Libraries/companies.php");
$client = economic_connect(222393);
$op = $_SERVER['LOGNAME'];
date_default_timezone_set('Europe/Copenhagen');
require_once("menu.php");
require_once("history.php");
$regnskabsfil = selectregnskab();
$xml = new DOMDocument();
$xml->load($regnskabsfil);
$kontonumre = [];
if (!$xml->schemaValidate('./xml.xsd')) {
   die("invalid xml document");
}
$sxe = simplexml_load_file($regnskabsfil);
$accounts = $client->Account_GetAll();
foreach ($accounts->Account_GetAllResult->AccountHandle as $account) {
$type = $client->Account_GetType(array('accountHandle' => $account))->Account_GetTypeResult;
if ($type == "ProfitAndLoss" || $type == "Status") { 
	$alias = $sxe->aliases->addChild("kontoalias");
	$alias->addAttribute("aliasnavn",kontoopslag($client,$account->Number));
	$alias->addAttribute("kontonr",$account->Number);
	$kontonavn = $sxe->kontonavne->addChild("kontonavn");
	$kontonavn->addAttribute("navn",kontoopslag($client,$account->Number));
	$kontonavn->addAttribute("nr",$account->Number);
$entries = $client->Account_GetEntriesByNumber(
        array('accountHandle' => array('Number' => $account->Number),
                'minNumber' => 0,
                'maxNumber' => 999999999));
		//$entries = $client->Entry_GetDataArray(array('EntityHandles' => $entries->Account_GetEntriesByNumberResult));
		if (isset($entries->Account_GetEntriesByNumberResult->EntryHandle)) {
			$entries2 = $client->Entry_GetDataArray(array('entityHandles' => $entries->Account_GetEntriesByNumberResult))->Entry_GetDataArrayResult->EntryData;
			if (isset($entries2->Handle)) {
				$entries3[0] = $entries2;
				unset($entries2);
				$entries2 = $entries3;
			}
			foreach ($entries2 as $entry) {
if ($entry->Type != 'OpeningEntry' && $entry->Type != 'TransferredOpeningEntry') {
				$voucher = $sxe->vouchers->addChild("voucher");
				$voucher->addAttribute('operator',$op);
				$voucher->addAttribute('entrytime',date('YmdTG:H'));
				$voucher->addAttribute('id',$sxe->count());
				$dato = strtotime($entry->Date);
				$dato = date('Y-m-d',$dato);
				$voucher->addAttribute('bilagsdato',$dato);
				$voucher->addAttribute('bilagsnr',$entry->VoucherNumber);
				if (isset($entry->Text))
					$voucher->addAttribute('tekst',utf8_encode($entry->Text));
				$the_entry = $voucher->addChild("entry");
				$the_entry->addAttribute("kontering", kontoopslag($client,$entry->AccountHandle->Number));
				$the_entry->addAttribute("beløb", $entry->AmountDefaultCurrency);
				$the_entry->addAttribute("momskode", isset($entry->VatAccountHandle->VatCode) ? $entry->VatAccountHandle->VatCode : "");
}
else {
	//Primopostering kan vi ikke bruge til noget
}				
			}
	
		}
}
else if ($type == "TotalFrom") {
	$from  = $client->Account_GetData(array('entityHandle' =>array('Number' => $account->Number)))->Account_GetDataResult->TotalFromHandle->Number;
	$til = $account->Number -1;
	$sum = $sxe->summeringer->addChild("summering");
	$sum->addAttribute("navn",kontoopslag($client,$account->Number));
	$sum->addAttribute("nr_fra",$from);
	$sum->addAttribute("nr_til",$til);
	$sum->addAttribute("nr_sum",$account->Number);
}
else if ($type == "Heading") {
	// Heading bruger vi ikke til noget !
}
else {
	echo "Unimplemented type: $type\n";
}
}
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($sxe->asXML());
$dom->save($regnskabsfil);

function kontoopslag($client,$konto) {
global $kontonumre;
	if (isset($kontonumre[$konto])) {
		return $kontonumre[$konto];
}
	$navn = $client->Account_GetData(array('entityHandle' => array('Number' => $konto)))->Account_GetDataResult->Name;
	$kontonumre[$konto] = $navn;
	return $navn;
}
?>
