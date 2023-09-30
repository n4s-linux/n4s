<?php
global $op;
function sticky($kundenr,$sagsnavn,$html = true) {
	global $op;
		$path = getenv("HOME");
	if (!strlen($path))
		$path = "/svn/svnroot/tmp/";
	$cache_sticky = array();
	if (file_exists("$path" . "/sticky_cache_$op.json") && time()-filemtime($path . "/sticky_cache_$op.json") < 25000) {
		$cache_sticky = unserialize(file_get_contents($path . "/sticky_cache_$op.json"));
	}
	if (isset($cache_sticky[$kundenr]) && isset($cache_sticky[$kundenr][$sagsnavn]) && isset($cache_sticky[$kundenr][$sagsnavn][intval($html)])) {
		return ($cache_sticky[$kundenr][$sagsnavn][intval($html)]);
	}
	$res = mysqli_query($GLOBALS["___mysqli_ston"], "select tekst,kundenr,id,memo from todo use index (memo_index) where kundenr=$kundenr and length(memo) and memo like '%***%' ") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$retval = "";
if ($html == true)
	$retval = "<table border=1><tr><td width=70%><b>Sticky</b></td><td></b><b>Kilde</b></td></tr>";
if (!mysqli_num_rows($res)) {
	$retval = "";
}
$count = 0;
while ($row = mysqli_fetch_assoc($res)) {
	if (strtolower(trim(explode(" ",$row['tekst'])[0])) == strtolower(trim(explode(" ",$sagsnavn)[0]))) {
	$memo = $row['memo'];
	$memo = explode("\n",$memo);
	foreach ($memo as $line) {
		$split = explode("***",$line);
		if (isset($split[1])) { // hvis der står *** er det betydende at der er en sticky
			if ($html == true)
				$retval .= "<tr><td>" . $split[1] . "</td> <td><a href=webinterface.php?kundenr=$row[kundenr]&sagid_f=$row[id]&contents=1>$row[tekst]</a></td></tr>\n";
			else {
				if ($count != 0)
					$retval .= "\n";
				$retval .=  $split[1] . " (fra $row[tekst] (sagsnr. $row[id]))";
			}
			$count++;
		}
	}
	}
}
if ($count > 0) {
	if ($html == true)
		$retval .= "</table>";
}
else
	$retval = "";

$cache_sticky[$kundenr][$sagsnavn][intval($html)] = $retval;
file_put_contents($path . "/sticky_cache_$op.json",serialize($cache_sticky));
system("chmod 777 '".$path . "/sticky_cache_$op.json'");
return $retval;
}
?>
