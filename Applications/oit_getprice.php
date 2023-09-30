<?php
	$xml = simplexml_load_file($argv[1]);
	echo ($xml->commodities->commodity->annotation->price->quantity);
?>
