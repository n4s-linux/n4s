<?php
	file_put_contents("/var/log/olsen",date("Y-m-d H:m") . " TLF " . trim(json_encode($_GET)) . "\n", FILE_APPEND);
?>
