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
	echo "$line\n";continue; // DISABLED FOR NOW 2023-04-05T18:15 joo	
	if ($line == "# Bogføringsvejledning" || $line == "# Mapper" || $line == "# Logins" || $line == "# Stamdata") { $inblocked = true; continue; }
	if (!$inblocked)
		echo $line . "\n";
	else {
		if (substr($line,0,1) == "#") {
			$inblocked = false; continue;
		}
		else
			continue;
	}
}
?>
