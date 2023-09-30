<?php
	$ticketarray = array();
	require_once("proc_open.php");
	$op = exec_app_output("whoami");
	$tickets = explode("\n",exec_app_output("rt ls -f id,Created,subject"));
	$fzf = "";
	foreach ($tickets as $ticket) {
		$ticket = explode("\t",$ticket);
		if (!isset($ticket[2])) continue;
		if ($ticket[2] == "Created") continue;
		$ticketno = $ticket[0];
		$subject = "foobar"; //substr(trim($ticket[1]),0,20);
		$created = date("Y-m-dTH:m",strtotime($ticket[2]));
		$cache = "/home/$op/tmp/rt_$ticketno";
		$data = "";
		if (file_exists($cache))
			$data = file_get_contents($cache);
		else {
			echo "Getting $ticket\n";
			$data = exec_app_output("rt show $ticket");
			file_put_contents($cache,$data);
		}
		$from = explode("Sag oprettet af ",$data)[1];
		$from = explode(" on",$from)[0];
		array_push($ticketarray,array('from'=>$from,'id'=>$ticketno,'subject'=>$subject,'created'=>$created,'contents'=>$data));
		$fzf .= "$ticketno\t$from\t$created\t$subject\n";
	}
	require_once("fzf.php");
	fzf($fzf,"Vælg ticket");
?>
