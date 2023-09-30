<?php
//system("echo -n db \"'\";echo -n $(python2 /svn/svnroot/Applications/dropbox.py status|cut -c1-20;echo -n \"' \");echo -n ' '");
//system("echo -n $(hostname)' '");
$curhour = date("H");
echo getdoshabyhour($curhour) . " ";
$sf = "/tmp/sessions";
$cmd = "screen -ls > $sf";
exec($cmd);
$sessions = explode("\n",file_get_contents("$sf"));
unlink("$sf");
foreach ($sessions as $sess) {
	if (!stristr($sess,"ched")) continue;
	$y = explode(" ",$sess)[0];
	$sess = explode(".",$y)[1];
	$sess = explode("\t",$sess)[0];
	echo "#" .$sess. " ";
}
function getdoshabyhour($hour) {
	if ($hour < 2)
		return "P (nat)";
	else if ($hour < 6)
		return "V (krea)";
	else if ($hour < 10)
		return "V (letaktiv)";
	else if ($hour < 14)
		return "P (action og måltid)";
	else if ($hour < 18)
		return "V (social, comm)";
	else if ($hour < 22)
		return "K (slowdown)";
	else
		return "P (sleep)";
	return "also impossible";
}	
?>
