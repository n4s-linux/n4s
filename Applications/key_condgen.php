<?php
require_once("fzf.php");
$done = false;
$fields = trim(
	'
t1[Amount]
t1[Func]
t1[Account]
t2[Amount]
t2[Func]
t2[Account]
data[Description]
data[Date]
data[Ref]
data[Filename]
data[UID]
');
$operators = trim("
equal to
not equal to
contains
greater than
less than
");
$assignment_operators=trim("
set string
add string
set number
add number
substractnumber
");
function setstring($a,$b) {
	$a = str_replace("[","[\"",$a);
	$a = str_replace("]","\"]",$a);
	return  '$' . "$a = \"" .  $b . "\" ";
}
function addstring($a,$b) {
	$a = str_replace("[","[\"",$a);
	$a = str_replace("]","\"]",$a);
	return  '$' . "$a .= \"" .  $b . "\" ";
}
function setnumber($a,$b) {
	$a = str_replace("[","[\"",$a);
	$a = str_replace("]","\"]",$a);
	return  '$' . "$a =  $b . ";
}
function addnumber($a,$b) {
	$a = str_replace("[","[\"",$a);
	$a = str_replace("]","\"]",$a);
	return  '$' . "$a\" +=  $b . ";
}
function substractnumber($a,$b) {
	$a = str_replace("[","[\"",$a);
	$a = str_replace("]","\"]",$a);
	return  '$' . "$a\" -=  $b . ";

}
function equalto($a,$b) {
	return "\"" .'$' . "$a\" == \"" .  $b . "\" ";
}
function notequalto($a,$b) {
	return "\"" .'$' . "$a\" != \"" .  $b . "\" ";
}
function contains($a,$b) {
	return "stristr(\"" .'$' . "$a\" ,\"" .  $b . "\") ";
}
function greaterthan($a,$b) {
	return "\"" .'$' . "$a\" > " .  $b . " ";
}
function lessthan($a,$b) {
	return "\"" .'$' . "$a\" < " .  $b . " ";
}
$conditions = array();
$fejl = fzf("Ja\nNej","Skal reglen søge på fejlkonto - SOM UDGANGSPUNKT = JA");
if ($fejl == "Ja") {
	array_push($conditions,'stristr("$t2[Account]" ,"fejl")');
	$c = 1;
}
else
	$c = 0;

$orgfields = $fields;
while (1) {
	require_once("proc_open.php");
	if ($c == 1)
		$fields="Done\n$fields";
	$field = fzf($fields,"Chose field for testing on");
	if ($field == "Done") break;
	$op = str_replace(" ","",fzf($operators,"operator for $field"));
	$value = read("value for $field $op");
	echo "testing for '$value'\n";
	array_push($conditions,$op($field,$value));
	$c++;
}
$c = 0;
$fields = $orgfields;
$assignments = array();
while (1) {
	require_once("proc_open.php");
	if ($c  == 1)
		$fields="Done\n".$fields;
	$field = fzf($fields,"Chose field for changing");
	if ($field == "Done") break;
	$op = str_replace(" ","",fzf($assignment_operators,"operator for $field"));
	$value = read("value for $field $op");
	array_push($assignments,$op($field,$value));
	$c++;
}
$code = "if (\n";
$c = 0;
foreach ($conditions as $curcondition) {
	if ($c > 0) $curcondition = "&& $curcondition";
	$code .= "\t".$curcondition."\n";
	$c++;
}
$code .= ")\n{\n";
foreach ($assignments as $curassignment) {
	$code .= "\t".$curassignment . ";\n";
}
$code .= "}";
$tpath = getenv("tpath");
$fn = $tpath . "/logic_" . str_replace("_","_",read("Kodenavn")) . time();
file_put_contents($fn,$code);
exec_app("vim \"$fn\"");
chdir($tpath);
require_once("fzf.php");
/*function fzf($list,$header) {
	$cmd = "echo \"" . str_replace("\n","\\n",$list);
	$cmd .= "\"|fzf --header=\"$header\" > /tmp/fzf";
	exec_app($cmd);
	$d = explode("\n",file_get_contents("/tmp/fzf"))[0];
	unlink("/tmp/fzf");
	return $d;
}
*/
function read($var) {
	echo "$var: ";
	$fd = fopen("PHP://stdin","r");
	$d = explode("\n",fgets($fd))[0];
	fclose($fd);
	return $d;
}
?>
