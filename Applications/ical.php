<?php
	$op = system("whoami");
	$fn = "/home/$op/tmp/ical";
	$data = file_get_contents("$fn");
	$events=explode("BEGIN:VEVENT",$data);
	$entries = array();
	$i = 0;
	foreach ($events as $event) {
		$lines = explode("\n",$event);
		foreach ($lines as $line) {
			$key = explode(":",$line)[0];
			$val = explode(":",$line)[1];
			$entries[$i] = array("key"=>$key,"val"=>$val);
		}
		$i++;
	}
	print_r($entries);
?>
