<?php
$tpath=getenv("tpath");
if ($tpath=="") die("no tpath\n");
require_once("key.php");
require_once("proc_open.php");
system("mkdir -p $tpath/.search;");
require_once("fzf.php");
$search = array();
while ($fn != "SØG") {
$fzf = "";
$fields = explode(" ", "Date Description T1Account F1Func T1Amount T2Account T2Amount T2Func Reference SØG");
$search = array();
foreach ($fields as $field) {
	$val = getval($field);
	$op = getval($field . ".op");
	if (strlen($val)) { $search[$field] = $val; }
	$field = str_pad($field,25);
	$op = str_pad($op,25);
	$fzf .= "$field\t$op\t$val\n";
}
$fn = trim(explode(" ",fzf($fzf,"Vælg søgefelt - eller SØG","--tac",true))[0]);
if ($fn == "SØG" ) break;
if ($fn == "") die();
$glo = getval($fn.".op");
$nyglo = fzf("Indeholder\nIndeholderIkke\nLig med\n","Vælg søgefunktion");
file_put_contents("$tpath/.search/$fn.op",$nyglo);

$gl = getval($fn);
echo "Ny værdi for $fn ($gl): ";
$fd = fopen("PHP://stdin","r");$nv = trim(fgets($fd));fclose($fd);
file_put_contents("$tpath/.search/$fn",$nv);
}
$withglo = array();
foreach ($search as $key => $val) {
	$glo = getval($key.".op");
	$withglo[$key] = array('operator'=>$glo,'key'=>$key,'val'=>$val);
}
$resultz = array();
foreach ($data as $curdata) {
	foreach ($withglo as $curcomp) {
		$res = compare($curdata,$curcomp);
		if ($res != null) array_push($resultz,$res);
	}	
}
$resultvim = "";
foreach ($resultz as $result) {
	$resultvim .= ":e $path/$result[Filename]\n";
}
$editor = "vim";
$op = exec("whoami");
$vf = "/tmp/vim_$op";
file_put_contents($vf,$resultvim);
exec_app("$editor $vf");
exec_app("php /svn/svnroot/Applications/key_arraydiff.php $results");
system("rm \"$path\"/.*.old");


function compare($data,$comp) {
	$begin = strtotime(getenv("LEDGER_BEGIN"));
	$end = strtotime(getenv("LEDGER_END"));
	if (!(strtotime($data['Date'] <= $end && strtotime($data['Date'] >= $begin)))) return null;
	$operator = $comp['operator'];
	$key = $comp['key'];
	$val = $comp['val'];
	if ($operator($data,$key,$val))
		return $data;
	else
		return null;
	
		
}
function indeholder($data,$key,$val) {
	if (!isset($data[$key])) return false;
	return (stristr($data[$key],$val));
}
function indeholderIkke($data,$key,$val) {
	if (isset($data[$key])) return false;
	return !(stristr($data[$key],$val));
}
function getval($field) {
	global $tpath; 
	return trim(file_get_contents("$tpath/.search/$field"));
}
?>
