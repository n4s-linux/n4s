<?php
	$starthour = 8;
	$endhour = 23;
	$plandays = 30;
	$planlength = 14;
	if (!file_exists("tasks")) { 
		$tasks = array(
			array('desc'=>"Bogføring 2020H2","Deadline"=>'2021-05-01',"TimeLeft"=>420),
			array('desc'=>"Bogføring 2020H3","Deadline"=>'2021-06-01',"TimeLeft"=>420)

		);
		file_put_contents("tasks",yaml_emit($tasks,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	}
	else
		$tasks = (array)yaml_parse(file_get_contents("tasks"),0);
	usort($tasks,"sort_by_deadline");	
	$startdate = date("Y-m-d");
	$hour = date("G");
	$plan = array();
	for ($day = 0;$day < $plandays;$day++) {
		$curday = date("Y-m-d",strtotime("$startdate +$day days"));
		if ($day == 0)
			$start_hour = $hour;
		else
			$start_hour = $starthour;
		for ($curhour = $start_hour;$curhour<$endhour;$curhour++) {
			if (isset($plan[$curday]) && isset($plan[$curday][$curhour])) continue;
			foreach ($tasks as &$curtask) {
				if (!isset($curtask['TimeLeft']) || $curtask['TimeLeft'] <= 0) {
					continue;
				}
				$curtask['TimeLeft'] -= 60;
				$plan[$curday][$curhour] = $curtask;
				print_r($plan);
			}
		}	
	}
echo "<pre>";
die();
	$nt = array();
	foreach ($tasks as $task) {
		if (isset($task['TimeLeft']) && $task['TimeLeft'] > 0)
			array_push($nt,$task);
	}
	$tasks = $nt;
	function sort_by_deadline($a,$b) {
		return strtotime($a['Deadline']) > strtotime($b['Deadline']);
	}
	echo "<table border=1><tr>";
	for ($daycounter = 0;$daycounter<$planlength;$daycounter++) {
		echo "<td><b>$daycounter</b></td>";	
	}
	echo "</tr>";
	for ($i = $starthour;$i<$endhour;$i++) {
		echo "<tr><td>$i</td>";
		for ($daycounter = 0 ;$daycounter<=$planlength;$daycounter++) {
			echo "<td>";
			$curday = date("Y-m-d",strtotime("$startdate +$daycounter days"));
			if (isset($plan[$curday]) && isset($plan[$curday][$i])) {
				echo $plan[$curday][$i]['desc'];
			}
			else
				echo ".";
			echo "</td>";
		}	
		echo "</tr>";
	}
	echo "</table>";
	foreach ($plan as $day => $taskarray) {
		
	}
?>

