<?php
$data = "";
while ($line = fgets(STDIN)) {
	$data .= "$line";
}
$data = trim($data);

$inblocked = false;
$lines = explode("\n",$data);
$last = null;
foreach ($lines as $line)  {
	if (stristr($line,"✔")) continue;
	echo "$line\n";
}
