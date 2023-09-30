<?php
$lf = getenv("tpath") . "/curl";
echo $lf;
echo "<meta charset=utf8><pre>";
system("LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger accounts > /tmp/accounts");
echo "<table border=1>";
system("LEDGER_DEPTH=2 php /svn/svnroot/Applications/key.php ledger balance  --balance-format=\"<tr><td>%(display_total)</td><td>%(account)</td></tr>\"");
echo "</table>";
$accounts = explode("\n",file_get_contents("/tmp/accounts"));
$tla = gettla($accounts);
$printed = array();
foreach ($tla as $top) {
	system("LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger accounts |grep $top:> /tmp/accounts");
	$accounts = explode("\n",file_get_contents("/tmp/accounts"));
	foreach ($accounts as $account) {
		$account = implode(":",array_slice(explode(":",$account),0,2));
		if (!isset($printed[$account])) {
			print_level1($account);
			$printed[$account] = 1;
		}
			
	}

}
unlink ("/tmp/accounts");

function print_level1($account) {
	global $lf;
	echo "<b>" . strtoupper($account)."</b>\n";
	$account = str_replace("/",".",$account);
	$account = str_replace("ø",".",$account);
	$account = str_replace("æ",".",$account);
	echo "<table border=1>";
	system("LEDGER_SORT=account,date LEDGER_DEPTH=999 ledger -B -f \"$lf\" --register-format=\"<tr><td>%(date)</td><td>%(payee)</td><td>%(account)</td><td>%(display_amount)</td><td>%(total)</td></tr>\" r \"/^$account(:.*)?$/\"\n");
	echo "</table>";
}


function gettla($accounts,$level = 0) {
$retval = array();
foreach ($accounts as $account) {
	$account = explode(":",$account);
	if ($account[0] == "") continue;
	if (isset($account[$level])) {
		if (!in_array($account[$level],$retval))
			array_push($retval,$account[$level]);
	}
}
usort($retval,"tla_sort");
return $retval;
}
function tla_sort($a,$b) {
	$values = array('Indtægter'=>1,'Udgifter'=>2,'Resultatdisponering'=>3,'Aktiver'=>4,'Passiver'=>5,'Egenkapital'=>6);
	if (isset($values[$a]))
		$avalue = $values[$a];
	else
		$avalue = 99;
	if (isset($values[$b]))
		$bvalue = $values[$b];
	else
		$bvalue = 99;
	return $avalue > $bvalue;
}
?>
