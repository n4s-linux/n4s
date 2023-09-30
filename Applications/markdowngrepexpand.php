<?php 
expand();

function expand() {
	$output = getenv("mdoutput");
	if ($output == "") $output = "terminal";
	global $argv;
echo "output=$output";
	$h3 = "";
	$h2 = "";
	$h1 = "";
	$fzf = "";
	$i = -1;
	$buf = "";
	if ($output == "markdown") {
		$buf = "|curline|arg|h1|h2|h3|\n|---|---|---|---|---|\n";
	}
	else
		die("else");
	foreach (explode("\n",file_get_contents("PHP://stdin")) as $curline) {
		$curline = trim($curline);
		if (substr($curline,0,3) == "###") {
			$h3 = substr($curline,4);
			continue;
		}
		else if (substr($curline,0,2) == "##") {
			$h2 = substr($curline,3);
			$h3 = "";
			continue;
		}
		else if (substr($curline,0,1) == "#")  {
			$h1 = substr($curline,2);
			$h2= "";
			$h3 = "";
			continue;
		}
		$curline = substr($curline,0,65);
		if ($output == "terminal") 
			echo "$curline\t💾$argv[1]💾\t$h1\t$h2\t$h3\n";
		else if ($output == "markdown") {
			$d = date("Y-m-d");
			if (substr($curline,0,strlen($d)) == $d) $curline = substr($curline,11,45); // 12 is where date "YYYY-mm-ddT" ends
			if (substr($curline,0,3) == "joo") $curline = substr($curline, 3);
			if ($buf != "") { echo $buf; $buf = ""; }
			echo "$curline|$argv[1]|$h1|$h2|$h3\n";
		}
	}

}
?>
