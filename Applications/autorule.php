<?php
	function autorule($diff) {
		print_r($diff);
		foreach ($diff as $key => $val) {
			file_put_contents("$key => $val\n","/tmp/autorule",FILE_APPEND);
		}
	}
?>
