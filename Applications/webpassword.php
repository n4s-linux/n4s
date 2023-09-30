<?php
	if (!isset($argv[1]))
		echo "Kræver kundenr som param";
	else {
		echo md5(strrev($argv[1]) . "1337" . $argv[1]);
	}
?>
