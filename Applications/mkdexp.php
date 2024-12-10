    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<style>
body{
  -webkit-print-color-adjust:exact !important;
  print-color-adjust:exact !important;
}</style>


<?php
$file = "/data/regnskaber/transactions_crm/.tags/olsensrevision";
ob_start();
system("bash /svn/svnroot/Applications/mkd.bash \"$file\" sections");
$sections = explode("\n",trim(ob_get_clean()));
foreach ($sections as $cursection) {
	system("bash /svn/svnroot/Applications/mkd.bash \"$file\" getsection \"$cursection\">~/tmp/cursection.txt");
	$op = exec("whoami");
	$section = file_get_contents("/home/$op/tmp/cursection.txt");
	$section = substr($section,strpos($section,"\n")+1); //remove first line which is the header
	if ($cursection != "Stamdata" && $cursection != "Logins" && $cursection != "Mapper" && $cursection != "Pligter")
		echo nicesection($cursection,$section);
}
function nicesection($header,$section) {
	ob_start();
	echo "<h3>$header</h3><br><table class=table>";
	$lines = explode("\n",$section);
	foreach ($lines as $curline) {
		$curline = trim($curline);
		$cols = explode("    ",$curline);
	//	if (!isset($cols[1])) continue;

		echo "<tr>";
		$ts = explode(" ",$cols[0])[0];
		$curcol = $cols[1];
		$done = explode("✔",$curcol);
		if (isset($done[1])) {
			$color="green"; 
			$done[1] = "✔ $done[1]";
		}
		else {
			$color="gray";
			$done[1] = "...";
		}
		if ($color == "white" && stristr($done[0],"vente"))
			$color = "yellow";
		echo "<td style='background: $color' width=50>$ts</td>";
		echo "<td style='background: $color' width=350>$done[0]</td>";
		echo "<td style='background: $color' width=150>$done[1]</td>";
		echo "</tr>";
	}
	echo "</table>";
	return ob_get_clean();
}
?>
