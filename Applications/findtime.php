<?php
$dato = null;
$maxdays = 360;
$hoursperday = 4;
$fzf = "";
require_once("/svn/svnroot/Applications/fzf.php");
$res = array();
while ($line = fgetcsv(STDIN,null,",","\"","\\")) { // get ledger data through stdin, thats why we have to use tty further down 
	if (!isset($res[$line[0]] )) $res[$line[0]] = 0;
	$res[$line[0]] += $line[5];	
}
require_once("ansi-color.php");
for ($i = 2; $i<$maxdays;$i++) { // find available time, removing weekends, max hoursperday
	$date = date("Y-m-d",strtotime("+$i days"));
	$weekday = date("w",strtotime($date));
	if (isset($res[$date])) $reserveret = $res[$date]; else $reserveret = 0;
	
	$dagnavn = date('l', strtotime($date));
	$uge = getweek($date);

	$mystr = "$date\t$reserveret\t$dagnavn\t$uge\n";
	
	if (isweekend($weekday)) continue;
	else if ($reserveret > $hoursperday) continue;
	else if ($reserveret < 2) $mystr = set($mystr,"magenta");
	else $mystr = set($mystr,"green_bg");

	$fzf .= $mystr;
}
$day = explode("\t", fzf($fzf,"Vælg dag $hoursperday /dag $maxdays fremad)","--ansi ",true))[0];
$day = explode(" ",$day)[0];
$dato = date("Y-m-d",strtotime($day));
if (strlen($day) == 0) die("no date selected\n");
$timer = floatval(fzf("0.33\n0.5\n1\n2\n3\n4\n5\n6")); // select how many hours
if ($timer == 0) die("ingen timer valgt\n");
if (getenv("caltekst") == "") {
	echo "Indtast tekst: ";
	$fd = fopen("/dev/tty","r");
	$str = trim(fgets($fd));
	fclose($fd);
}
else
	$str = getenv("caltekst");

$tmuxline = trim(getenv("tmuxline"));
$tmuxline = str_replace("\\t","\t",$tmuxline);
require_once("/svn/svnroot/Applications/ansi-color.php");
echo set($tmuxline ,"green_bg") . "\n";
echo "Indtast kommentar (efterlad BLANK for at bruge ovenstående fra TMUX): ";
$fd = fopen("/dev/tty","r");
$comment= trim(fgets($fd));
if ($comment == "") $comment = $tmuxline;
$x = explode("\t",$comment);
if (isset($x[1])) {
	$comment = $x[1];
}
fclose($fd);

if (!strlen($str))
	die("aborting findtime.php - no strings for trans\n");
if (!isset(explode(" ",$str)[1]))
	$account = "Opgaver:Diverse";
else {
	$modstr = explode(" ", $str)[0];
	$account = "Opgaver:$modstr";
}
$acc2 = "Modkonto";
$amount = floatval($timer);

$uid = date("Y-m-d") ."-". uniqid();
$data = array('Transactions'=> array(array('Account'=>$account,'Amount'=>$amount,"Func"=>""),
					array('Account'=>$acc2,'Amount'=>-$amount,'Func'=>"")),
'Date'=>$dato,"Comment"=>"$comment",'Reference'=>$uid,'Description'=>$comment);
$uid = date("Y-m-d") ."-". uniqid();
$data['Filename'] = $uid . ".trans";
file_put_contents("/home/joo/regnskaber/kalender/" . $data['Filename'],json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)) or die("shit");

function getweek($ddate) {
$date = new DateTime($ddate);
$week = $date->format("W");
return $week;
}
function isweekend($a) { return ($a == 6 || $a==0);}
?>
