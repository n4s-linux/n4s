<?php
// 2023-03-21T17:53 joo	not sure i remember what this does
$data = stream_get_contents(STDIN);
$lastfile = "";
foreach (explode("\n",$data) as $curline) { // foreach line piped into this program
	$file = explode(".diff",explode(":",$curline)[0])[0];
	if (trim($file) == "") continue;
	$prefix = getenv("prefix");
	if ($file != $lastfile) {
		echo "\n## $file ($prefix)\n\t$curline\n";
		$lastfile = $file;
	}
	else
		echo "\t$curline\n";
}
?>
