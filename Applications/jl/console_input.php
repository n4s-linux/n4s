<?php
function console_input($d = "") {
		if (strlen($d))
			echo $d . ":";
		$fd = fopen("PHP://stdin","r");
		$cmd = trim(explode("\n",fgets($fd))[0]);
		fclose($fd);
		return $cmd;
}

?>
