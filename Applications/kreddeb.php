<?php
	// Formål: (semi)automatisk afstemning af debitorer og kreditorer - findes posterne på fejllisten
	// Status: Ikke funktionel
?>


<?php
require_once("/svn/svnroot/Applications/fzf.php");
$maxdiff = 14;
$transactions = array(); 
$saldi = array();
$saldidate = array();
while ($line = fgetcsv(STDIN,null,",","\"","\\")) { // GET DATA 
	$date = $line[0];
	$bilag = $line[1];
	$desc = $line[2];
	$konto = $line[3];
	$fn = $line[7];
	$belob = floatval($line[5]);
	array_push($transactions,array('date'=>$date,'bilag'=>$bilag,'desc'=>$desc,'konto'=>$konto,'fn'=>$fn,'belob'=>$belob));
}
foreach ($transactions as &$curtrans) {
	$curtrans['matches'] = possiblematches($curtrans,$transactions);
}
print_r($transactions);
function possiblematches($searchtrans,$transactions) {
	$retval = array();
	foreach ($transactions as $curtrans) {
		if (floatval($curtrans['belob']) == floatval($searchtrans['belob']) * -1)
			array_push($retval,$curtrans);
	}
	return $retval;
}
