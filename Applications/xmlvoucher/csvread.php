<?php 
$op = $_SERVER['LOGNAME'];
date_default_timezone_set('Europe/Copenhagen');
require_once("menu.php");
require_once("history.php");
$regnskabsfil = selectregnskab();
$xml = new DOMDocument();
$xml->load($regnskabsfil);

if (!$xml->schemaValidate('./xml.xsd')) {
   die("invalid xml document");
}
$sxe = simplexml_load_file($regnskabsfil);
if (!isset($argv[1]))
	die("Skal bruge CSV fil som inddata argv1\n");
$csvdata = csv_to_array($argv[1],",");
$header = $csvdata['header'];
$rows = $csvdata['rows'];
$mapningsmuligheder = array("Dato","Beløb","Tekst","Ingen");
$i = 0;
$handle = fopen ("php://stdin","r");
$mapning['Bankkonto'] = "";
while ($mapning['Bankkonto'] == "") {
	echo "Indtast bankkontoens reference: ";
	$mapning['Bankkonto'] = trim(fgets($handle));
}
fclose($handle);
foreach ($header as $kolonne) {
	$input = new CLInput("Definer kolonnen '" . utf8_encode( $kolonne) . "'" ,'Tryk Ctrl-C for at quitte');
	$valg = $input->select($mapningsmuligheder,"Kolonnen indeholder:");
	$input->done();
	if ($mapningsmuligheder[$valg] != "Ingen") {
		$mapning[$mapningsmuligheder[$valg]] = $kolonne;
		unset($mapningsmuligheder[$valg]);
		$mapningsmuligheder = array_values($mapningsmuligheder);
	}
}
isset($mapning['Dato'])
	or die("Datokolonne er obligatorisk");
isset($mapning['Beløb'])
	or die("Beløbskolonne er obligatorisk");
isset($mapning['Tekst'])
	or die("Tekstkolonne er obligatorisk");

$i = -1;
foreach ($rows as $row) {
	$i++;
	$voucher = $sxe->vouchers->addChild("voucher");
	$voucher->addAttribute('operator',$op);
	$voucher->addAttribute('id',$sxe->count()) +$i;
	$myDateTime = DateTime::createFromFormat('d-m-Y', $row[$mapning['Dato']]);
	$myDateTime or $myDateTime = DateTime::createFromFormat('d.m.Y', $row[$mapning['Dato']]);
	$myDateTime or die("Ukendt datoformat");
	$row[$mapning['Dato']] = $myDateTime->format('Y-m-d');
	$voucher->addAttribute('bilagsdato',$row[$mapning['Dato']]);
	$voucher->addAttribute('bilagsnr',9999);
	$voucher->addAttribute('tekst',utf8_encode($row[$mapning['Tekst']]));
	$voucher->addAttribute('entrytime',date('YmdTG:H'));
	$entry = $voucher->addChild("entry");
	$row[$mapning['Beløb']] = str_replace(",",".",$row[$mapning['Beløb']]);
	$entry->addAttribute("kontering", $mapning['Bankkonto']);
	$entry->addAttribute("beløb",floatval($row[$mapning['Beløb']]));
	$entry->addAttribute("momskode","");
	$entry = $voucher->addChild("entry");
	$entry->addAttribute("kontering", ($row[$mapning['Beløb']] < 0) ? "Kreditorbetaling" : "Debitorindbetaling");
	$entry->addAttribute("beløb",-floatval(($row[$mapning['Beløb']])));
	$entry->addAttribute("momskode","");
}
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($sxe->asXML());
$dom->save($regnskabsfil);

function csv_to_array($filename='', $delimiter=',') {
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;

	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE) {
		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
			if(!$header)
				$header = $row;
			else
				$data[] = array_combine($header, $row);
		}
		fclose($handle);
	}
	$retval['rows'] = $data;
	$retval['header'] = $header;
	return $retval;
}
?>
