<?php
function whoami() {
	$processUser = posix_getpwuid(posix_geteuid());
	return $processUser['name'];
}
?>
