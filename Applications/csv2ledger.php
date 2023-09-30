<?php 
include("csv_to_array.php");
include("xmlvoucher/CLInput.php");
$op = $_SERVER['LOGNAME'];
date_default_timezone_set('Europe/Copenhagen');
unset($argv[0]);
foreach ($argv as $curarg) {
	$csvdata = csv_to_array($curarg,"\t");
	$curarg = str_replace(".csv","",$curarg);
	foreach ($csvdata as $row) {
		$row = $row[0];
		$row['Beløb'] = str_replace(",",".",$row['Beløb']);
		echo date("Y-m-d", strtotime($row['Dato'])) . " $row[Tekst] (BA)\n\tBankafstemning  $row[Beløb]\n\t$curarg\n\n";
	}
}
die();

















die();
$header = $csvdata['header'];
$rows = $csvdata['rows'];
$mapningsmuligheder = array("Dato","Beloeb","Tekst","Ingen");
$i = 0;
$handle = fopen ("php://stdin","r");
$mapning['Bankkonto'] = "";
while ($mapning['Bankkonto'] == "") {
	echo "Indtast bankkontoens reference: ";
	$mapning['Bankkonto'] = trim(explode("\n",fgets($handle))[0]);
}
fclose($handle);
foreach ($header as $kolonne) {
	if (count($mapningsmuligheder) <= 1)
		break;
	$input = new CLInput("Definer kolonnen '" . utf8_decode( $kolonne) . "'" ,'Tryk Ctrl-C for at quitte');
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
isset($mapning['Beloeb'])
	or die("Beløbskolonne er obligatorisk");
isset($mapning['Tekst'])
	or die("Tekstkolonne er obligatorisk");
foreach ($rows as $row) {
	$dato = date("Y/m/d",strtotime($row[$mapning['Dato']]));
	$tekst = $row[$mapning['Tekst']];
	$row[$mapning['Beloeb']] = str_replace(".","",$row[$mapning['Beloeb']]);
	$belob = str_replace(",",".",$row[$mapning['Beloeb']]);
	file_put_contents($argv[1].".ledger","$dato $tekst\n\t$mapning[Bankkonto]  $belob\n\tBankmodkonto\n\n",FILE_APPEND);
	

}

?>
