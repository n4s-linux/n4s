<?php
	$cmd = "grep \"#ng\" /data/regnskaber/transactions_crm/.tags/*|grep -v .diff > ~/tmp/ngers.txt";
	system($cmd);
	$ngers = explode("\n",file_get_contents("/home/joo/tmp/ngers.txt"));
	foreach ($ngers as $curng) {
		$line = explode(":",$curng);
		if (!isset($line[1])) continue;
		$fn = basename($line[0]);
		$ng = explode(" ",$line[1]);
		$tag = trim($ng[0]);
		$deadline = $ng[2];
		if (strlen($deadline) != strlen("YYYY-mm-dd")) continue;
		$estimate = getestimate($tag,$fn);
		echo "$deadline $fn - $tag\n\tOpgaver:$deadline:$tag:$fn  $estimate\n\tSkyldig\n\n";
	}
	function getestimate($tag,$fn) {
		return 1;
	}
?>
