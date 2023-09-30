<?php
	$date = strtotime($argv[1]);
	$year = date("Y",$date);
	$month = date("m",$date);
	if ($month < 7) {
		$py = $year;
		$pm = 9;
	}	
	else {
		$py = $year +1;
		$pm = 3;
	}
	echo "$py-$pm-01";
?>

