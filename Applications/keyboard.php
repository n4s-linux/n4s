<?php
function getcmd($key) {
	if (($key) == 218) // space
		return "/usr/bin/xterm";
	else
		return "undefined key $key";
}
while (true) {
	$keyboard = "/dev/input/event20";
	$fd = fopen($keyboard,"r");
	$char = (fread($fd,10));
	echo "char=$char\n";
	fclose($fd);
}

