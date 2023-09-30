<?php
function showtime($time,$Tekst = "Fundet", $delimiter = "\n") {
	$r = "";
	foreach ($time as $tid) {
		$timer = $tid['interval'] / 60;
		$weekday = date("l",strtotime($tid['start']->format("Y-m-d")));
		$dato = $tid['start']->format("Y-m-d H:i");
		if ($timer > 0)
			$r .= "$Tekst $timer timer d. $dato ($weekday)$delimiter";
	}
	return $r;
}
?>
