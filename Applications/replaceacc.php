<?php
$repl = array();
$repl['Passiver:Moms:Salgsmoms'] = "Passiver:Moms:Primo";
$repl['Passiver:Moms:Købsmoms:IV'] = "Passiver:Moms:Primo";
$repl['Passiver:Moms:Købsmoms:IY'] = "Passiver:Moms:Primo";
$repl['Passiver:Moms:Momsafregning'] = "Passiver:Moms:Primo";
$repl['Passiver:Moms:Købsmoms'] = "Passiver:Moms:Primo";
$repl['Passiver:Moms:Elafgift'] = "Passiver:Moms:Primo";
/*Passiver:Moms:Moms af varekøb udland    -13408.24  178957.4865
                                             Passiver:Moms:Moms af ydelser udland   -18158.486  160799.00
*/
$repl['Passiver:Moms:Moms af varekøb udland'] = "Passiver:Moms:Primo";
$repl['Passiver:Moms:Moms af ydelser udland'] = "Passiver:Moms:Primo";
$repl['Egenkapital:Årets hævninger'] = "Egenkapital:Overført resultat";
$repl['Egenkapital:Periodens resultat'] = "Egenkapital:Overført resultat";
$repl['Egenkapital:Øvrige hævninger'] = "Egenkapital:Overført resultat";
$repl['Egenkapital:Årets indskud'] = "Egenkapital:Overført resultat";
$repl['Egenkapital:B-skat'] = "Egenkapital:Overført resultat";
$repl['Egenkapital:Pension'] = "Egenkapital:Overført resultat";
$repl['Årets tilgang'] = "Tilgang primo";
$repl['Årets afskrivninger'] = "Afskrivninger primo";

$stdin = fopen("php://stdin", "r");

// Perform replacements on each line and output the modified lines
while (($line = fgets($stdin)) !== false) {
	foreach ($repl as $search => $replace) {
		$line = str_replace($search, $replace, $line);
	}
	echo $line;
    }
?>
