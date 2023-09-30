<?php
	$fd =fopen("PHP://stdin","r");
	while ($str = fgets($fd)) {
		$str = explode("✔",$str)[0];
		if (isset($str[1]))
		echo "<font color=green>$str</font>";
		else
		echo $str;
	}

?>
