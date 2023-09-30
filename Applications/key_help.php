<?php
	$lines = explode("\n",file_get_contents($argv[1]));
	foreach ($lines as $line) {
		$x = explode(" ",$line);
		print_r($x);
	}
?>
