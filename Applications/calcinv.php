<?php
$lines = explode("\n",file_get_contents($argv[1]));

$ln = 0;
$updated = "Dato\tTekst\tAntal\tStkpris\tLinietotal\n";
$total = 0;
foreach ($lines as $line ) {
	if ($ln++ == 0) continue;
	$line = explode("\t",$line);
	if (!isset($line[1])) continue;
	$line[4] = $line[2] * $line[3];
	$total += $line[4];
	foreach ($line as $linepart)
		$updated .= $linepart."\t";
	$updated .= "\n";
}
file_put_contents($argv[1],$updated);

?>
