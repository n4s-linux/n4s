<?php
echo "|Dato|Tekst|Konto|Beløb\n---|---|---|---\n";
while($f = fgetcsv(STDIN)){
	$f[2] = str_replace("|","&vert;",$f[2]);
	echo "$f[0]|$f[2]|$f[3]|$f[5]\n";

}
echo "\n";
?>
