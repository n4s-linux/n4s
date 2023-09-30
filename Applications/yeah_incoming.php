<?php
	$d = json_encode($_GET);
	echo file_put_contents("yealink.json",$d,FILE_APPEND);
?>
