<!-- Latest compiled and minified CSS -->
<meta charset=utf8>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
<style>
@media print{@page {size: landscape}}
@media print {
  div {
    break-inside: avoid;
  }
}
pre {
    white-space: pre-wrap;
}
@media print  
{
   div{
       page-break-inside: avoid;
    }
}
</style>
<h1>Aktindsigt</h1>

<?php
	assert(isset($argv[1]));
	$t = time();
	$of = "/tmp/of_$t";
	system("php $argv[1] > $of");
	$data = "\n".file_get_contents("$of");
	$sections = explode("\n#",$data);
	$section_num = 1;
	foreach ($sections as $section) {
		$subsection_num = 1;
		$sectionname = trim(explode("\n",$section)[0]);
		if ($sectionname == "Logins" || $sectionname == "Stamdata") continue;
		if ($section == "") continue;
		$subsections = explode("##",$section);
		echo "<div style='page-break-inside: avoid'><h2>$sectionname</h2>";
		foreach ($subsections as $subsection) {
			$subsection = trim($subsection);
			if ($subsection == "") continue;
			echo "<pre>$subsection</pre><br>";
			$subsection_num++;
		}
		$section_num++;
		echo "</div>";
		
	}
?>
