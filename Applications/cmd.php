<?php
	while (true) {
	$fzf = "";
	$menu = array("Bogføring"=> 
		array('Seneste','Søg','Ny','Periode','HTML',"CSV Ind","CSV UD"),
		"Program"=> array('Exit','Opdatering','Lommeregner'));
	
	foreach ($menu as $hovedmenu => $undermenu) {
		foreach ($undermenu as $um) {
			$fzf .= $hovedmenu . " - " . $um . "\n";
		}
	}
	require_once("fzf.php");
	$valg = fzf($fzf);
	if ($valg == "Program - Exit") die();
	}
?>
