<?php

if ($handle = opendir('.')) {
$str = $argv[1];
    /* This is the correct way to loop over the directory. */
	$filez = array();
    while (false !== ($entry = readdir($handle))) {
	if (stristr($entry,".pdf") === FALSE)
		continue;
        similar_text($str,$entry,$percent);
	if ($percent > 80 && $entry != $argv[1]) {
		$filez[filemtime($entry)] = $entry;
	}
    }
asort($filez);
$retval = date("Y-m-d",filemtime($argv[1])) . " " . $argv[1] . "\n";
if (count($filez) < 1) 
	die($retval);
$filez = end($filez);
exec("/svn/svnroot/Applications/editpdfmeta.bash \"$filez\" cat|ledger -f /dev/stdin accounts",$output);
$hentet = false;
foreach ($output as $o) {
	$hentet = true;
	$retval .= "\t$o\n";
}
if ($hentet)
	$retval .= "; Hentet konti fra fil med lignende filnavn " . ($filez) . "\n";	
else
	$retval .= "\t";
echo $retval;
}
?>
