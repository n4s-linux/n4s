<?php
$data = "";
while ($line = fgets(STDIN)) {
	$data .= "$line";
}
$data = trim($data);

$inblocked = false;
$lines = explode("\n",$data);
$last = null;
$darray = array();
foreach ($lines as $line)  {
	$file = explode(":",$line)[0];
	$data = substr($line,strlen($file)+1);
	$darray[$data] = array('file'=> $file,'data'=>$data);
}
usort($darray,"bydate");
foreach ($darray as $curda) {
	$file = str_replace(" joo\t","|",$curda['file']);
	$ts = explode(" ",$file)[0];
	echo "|$curda[data]|$file|\n";

}
function bydate($a,$b) {
	$date = strtotime(explode(" ",$a['data'])[0]);
	$date2 = strtotime(explode(" ",$b['data'])[0]);
	return $date < $date2;
}
?>
