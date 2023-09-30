<?php
$t = getcsvtrans("/home/joo/tmp/csv.csv");
$count = count($t);
echo "Indlæst $count transactioner\n";
echo "Indtast kommando: ";
while ($c = fgetc(STDIN)) {
	execcmd($c);
}
function execcmd($cmd) {
	echo "cmd: $cmd\n";
}
function getcsvtrans($fn) {
$transactions= array();
if (($handle = fopen("/home/joo/tmp/csv.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	array_push($transactions,$data);
    }
    fclose($handle);
}
	return $transactions;
}
?>
