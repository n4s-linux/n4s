<?php
echo "|dato|tekst|fn|\n|---|---|---|\n";
$lines = array();
	while ($line = fgetcsv(STDIN,null,"\t","\"","\\")) {
		if ($line[2] == "Opened")  continue;	
		if (strstr($line[2],"---")) continue;
		if (strstr($line[2],"***")) continue;
		if (!isset($line[3])) continue;
		$fn = str_replace(":","",str_replace(".diff","",basename($line[0])));
		$date = explode(" ",trim(str_replace("+","",$line[3])))[0];
		if (strlen($date) < 8) continue;
		if (isset($line[5])) $xtra = $line[5]; else $xtra = "";
		if (strlen($xtra)) $xtra = "\n$xtra";
		if (isset($line[4])) $tekst = $line[4]; else continue;
		$lines[$date] = "|$date|$tekst $xtra|$fn|\n";
	}
ksort($lines);
foreach ($lines as $line) echo $line;
?>
