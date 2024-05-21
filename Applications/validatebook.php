<?php
	require_once("/svn/svnroot/Applications/bookingfuncs.php");
        $tpath = getenv("tpath");
	if ($tpath == "") die("booking validation requires tpath set\n");
        ob_start();
        system("find $tpath/ -name \*.trans");
        $files = trim(ob_get_clean());$files = explode("\n",$files);
	$tids = array();
        $data = array();
        foreach ($files as $curfile) {
		if ($curfile == "") continue;
                $bn = basename($curfile);
                $data[$bn] = json_decode(file_get_contents("$curfile"),true);
		if ($data[$bn] == null) unset($data[$bn]);
                if (!isset($data[$bn]["Status"]) || $data[$bn]["Status"] != "Locked") unset($data[$bn]);
		if (isset($data[$bn])) {
		foreach ($data[$bn]["Transactions"] as $curtrans) {
			$tids[$curtrans["TransactionNo"]] = $data[$bn];	
		}
		}
        }
	$corrupted = array();
	$fnumber = 1;
	$count = count($data);
	foreach ($data as $fn => $j) {
		if (isset($j["HashOfPreviousLyBookedFiles"])) {
		$lowest_id = lowestid($j);
		$hash = gethash($lowest_id);
			$hashfile = $j["HashOfPreviousLyBookedFiles"];
			if ($hash != $hashfile) {
				$id = $lowest_id-1;
				$data = $tids[$id];
				$fn = $data["Filename"];
				die("\033[38;5;9mAccount corruption detected @ $fn\033[0m\n");
			
			}
			else fprintf(STDERR,"\033[38;5;10[$fnumber / $count] Validated $fn ✔\033[0m\n");
		}
		$fnumber++;
	}
	echo "\033[38;5;10mBook Validated\033[0m!\n";

?>
