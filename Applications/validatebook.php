<?php
	require_once("/svn/svnroot/Applications/bookingfuncs.php");
        $tpath = getenv("tpath");
	if ($tpath == "") die("booking validation requires tpath set\n");
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
	$fnumber = 1;
	$count = count($data);
	foreach ($data as $fn => $j) {
		if (isset($j["HashOfPreviousLyBookedFiles"])) {
		$lowest_id = lowestid($j);
		$hash = gethash($lowest_id);
			$hashfile = $j["HashOfPreviousLyBookedFiles"];
			if ($hash != $hashfile) {
				$id = $lowest_id-1;
				die("Account corruption detected at TransactionNo $id\n");
			}
			else fprintf(STDERR,"[$fnumber / $count] Validated $fn ✔\n");
		}
		$fnumber++;
	}
	echo "Book Validated !\n";

?>
