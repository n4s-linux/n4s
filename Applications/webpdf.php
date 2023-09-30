<?php
$fields = array("dato","hvem","hvad","beløb","momskode","forfald","eksternref");
$op = exec("whoami");
$dropbox = "/home/joo/Dropbox";



if (isset($_SERVER['QUERY_STRING'])) $q = $_SERVER['QUERY_STRING']; else $q = "";
if ($q == "") serveindex();
else if (stristr($q,"pdf") && !stristr($q,"&bel")) {
	$q = urldecode($q);
	$n = normalize($q);
	header("Content-type: application/pdf");
	header("Content-Disposition: inline; filename=pdf");
	echo readfile(($q));
}
else  {
	parse_str($q,$q);
	$q['fn'] = urldecode($q['fn']);
	$bn = basename($q['fn']);
	$dir = dirname($q['fn']);
	$hvem = trim(strtolower($q['hvem']));
	$newfn = "#læst $hvem";
	foreach ($q as $key => $val) {
		if ($key == "fn") continue;
		$newfn .= normalize("__$val");
	}
	$oldfn = str_replace(".pdf","",$bn);
	$oldfn = str_replace(".PDF","",$bn);
	$oldfn = substr($oldfn,0,25);
	$newfn .= "_$oldfn.pdf";
	$cmd = "cd \"$dir\";mv \"$bn\" \"$newfn\"";
	system("$cmd");
	header('Location: /');
die();

	die();
}




function handlefile($fn) {
global $fields;

echo "<center><input style='width:0600' type=text name=fnshow disabled value=\"$fn\">";
echo "<div><table width=1000 border=1><tr><td width=150>";
echo "<form action=/?postfile METHOD=GET>";
$ufn = urlencode($fn);
echo "<input type=hidden name=fn value=\"$ufn\"></center><br>";

foreach ($fields as $field) {
	echo "$field<br><input type=text name=$field value=" . getfield($field,$fn) . "><br>";
}
echo "<input type=submit></form>";
$efn = urlencode($fn);
echo "</td><td width=980 height=650>";
echo "<iframe src=\"?$efn#view=FitV\" width=\"100%\" style=\"height:100%\"></iframe>";
echo "</td></tr></table>";
die();
}
function getfield($field,$fn) {
	if ($field == "dato")
		return date("Y-m-d",filemtime($fn));
	else
		return "";
}
function filelist() {
	global $dropbox;
	global $op;
	$cmd = "ls ~/Dropbox/*/Bilag/Incoming/*|grep -i .pdf|grep -v \#læst";
	system("$cmd > ~/tmp/bilag.list");
	$data = trim(file_get_contents("/home/$op/tmp/bilag.list"));
	return explode("\n",$data);
	
}
function bogføringsvejledning() {
	echo "<center>Ide kundespecifik bogføringsvejledning fx husk elafgift, husk at notere hvilke biler der er købt med regnr</center><br>";
}
function serveindex() {
	$fl = filelist();
	$count = count($fl);
	echo "$count tilbage";
	usort($fl,"sortcustomer");
	foreach ($fl as $unhandledfile) {
		bogføringsvejledning($unhandledfile);
		handlefile($unhandledfile);
	}
	die();
}

function normalize($str = '')
{
	return $str;
    $str = strip_tags($str); 
    $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
    $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
    $str = strtolower($str);
    $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
    $str = htmlentities($str, ENT_QUOTES, "utf-8");
    $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
    $str = str_replace(' ', '-', $str);
    $str = rawurlencode($str);
    $str = str_replace('%', '-', $str);
    return $str;
}



?>
