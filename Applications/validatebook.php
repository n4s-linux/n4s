<?php
	require_once("/svn/svnroot/Applications/bookingfuncs.php");
        $tpath = getenv("tpath");
        ob_start();
        system("find $tpath/ -name \*.trans");
        $files = trim(ob_get_clean());$files = explode("\n",$files);
        $data = array();
        foreach ($files as $curfile) {
		if ($curfile == "") continue;
                $bn = basename($curfile);
                $data[$bn] = json_decode(file_get_contents("$curfile"),true);
		if ($data[$bn] == null) unset($data[$bn]);
                if (!isset($data[$bn]["Status"]) || $data[$bn]["Status"] != "Locked") unset($data[$bn]);
        }
	$corrupted = array();
	foreach ($data as $fn => $j) {
		fprintf(STDERR,"Validating $fn...\n");
		$lowest_id = lowestid($j);
		$hash = gethash($lowest_id);
		echo "got hash $hash\n";
		if (isset($j["HashOfPreviousLyBookedFiles"])) {
			$hashfile = $j["HashOfPreviousLyBookedFiles"];
			echo "hashfile=$hashfile\n";
			if ($hash != $hashfile) {
				array_push($corrupted,$lowest_id -1);
			}
		}
	}
	if (!empty($corrupted)) {
		$id = min($corrupted);
		echo "Account corruption detected at TransactionNo $id\n";
	}
	else echo "Book Validated\n";

?>
