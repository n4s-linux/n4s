<?php
	require_once("/svn/svnroot/Applications/short.php");
	$lines = explode("\n",fgc($argv[1]));
	$curheading=basename($argv[1]);
	$cursubheading="";
	foreach ($lines as $curline) {
		$curline = trim($curline);
		if (substr($curline,0,1) == "#" && substr($curline,0,2) != "##") {
			$curheading = trim(substr($curline,1));
			$cursubheading="";
			continue;
		}
		else if (substr($curline,0,2) == "##") {
			$cursubheading = trim(substr($curline,2));
		}
		if (!strlen(trim($curline)))continue;
		$ch = ($cursubheading == "") ? $curheading : $curheading . " - $cursubheading";
		echo "[$ch]:\t$curline\n";
	}
?>
