<?php






/* VIGTIGT



	2023-06-15T15:18 joo	evt. se newbudget.php tror det er den aktuelle der er relevant
VIGTIGT */
















require_once("ansi-color.php");
$activebudget = getenv("activebudget");
require_once("proc_open.php");
require_once("fzf.php");
$op = exec("whoami");
$tpath =getenv("tpath");
system("ls $tpath/.*.dat|while read i; do basename \"\$i\" ; done > ~/tmp/budgets.dat");
$budgets = explode("\n",trim(file_get_contents("/home/$op/tmp/budgets.dat")));
$fzf = "";
foreach ($budgets as $curbudget) {
	$fzf .= "$curbudget\n";
}
$fzf .= "NY";
$valg = fzf($fzf,"vælg budget", "--query='.$activebudget.dat'");
if ($valg == "NY") {
	echo "Indtast navn på budget: ";
	$fd = fopen("PHP://stdin","r");
	$valg = "." . trim(fgets($fd)) . ".dat";
	fclose($fd);
}
$budgetfile = "$valg";
$budgetname = $valg;
$fzf = "";
$tpath =getenv("tpath");
$accounts = explode("\n",trim(file_get_contents("/home/$op/tmp/accounts")));
if (file_exists("$tpath/$budgetfile"))
	$budget = json_decode(file_get_contents("$tpath/$budgetfile"),true);
else
	$budget = array();

if (!isset($argv[1])) {
while (true) {
	$header = "konto...01,02,03...all";
	$fzf = "";
	foreach ($accounts as $account) {
		$fzf .= $account . "\t✊";
		for ($i = 1;$i<13;$i++) {
			if (!isset($budget[$account]) || !isset($budget[$account][$i])) {
				$budget[$account][$i] = 0;	
			}
			$fzf .= $budget[$account][$i] . "\t";
		}
		if (!isset($budget[$account]['all'] ))
			$budget[$account]['all'] = 0;	
			$fzf .= $budget[$account]['all'] ."\n";
		
	}
	require_once("fzf.php");
	$fzf .= "NY\nMANUEL\n";
	$konto = trim(explode("✊",fzf($fzf,"vælg konto - ctrl-c for afbryd","--tac --header='$header'",true))[0]);
	if ($konto == "MANUEL") {
		$fn = "$tpath/.calc/$budgetname.manual.ledger";
		exec_app("vim $fn");
		continue;
	}
	if ($konto == "") break;
	if (trim($konto) == "") goto out;
	if ($konto == "NY")  {
		echo "Indtast konto: ";
		$fd = fopen("PHP://stdin","r");
		$str = trim(fgets($fd));
		fclose($fd);
		$konto = $str;
	}
	$fzf = "";
	for ($i = 1;$i<13;$i++) {
		$fzf .= "$i\n";
	}
	$fzf .= "all";
	$p = fzf($fzf,"vælg periode for $konto");
	echo "Indtast beløb (ENTER FOR SPEC): ";
	$fd = fopen("PHP://stdin","r");
	$str = trim(fgets($fd));
	fclose($fd);
	if (is_numeric($str)) { // if non-number, ask for spec in vim
		$budget[$konto][$p] = $str;
	}
	else
	{
		system("mkdir -p $tpath/.calc");
		if (isset($budget[$konto][$p])) {
			$x = explode("-",$budget[$konto][$p]);
			if (isset($x[1]))
				$str = explode("-",$budget[$konto][$p])[1];
			else
				$str = time();
		}
		if ($str == "") $str = time();
		$fn = "$tpath/.calc/$str";
		if (!file_exists($fn)) {
			$header = "Post\tBeløb\n";
			file_put_contents($fn,$header);
		}
		exec_app("vim $fn");
		$budget[$konto][$p] = "calc-$str";
	}
}
out:
	file_put_contents("$tpath/$budgetfile",json_encode($budget,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	echo set("Gemt og aktiveret $budgetfile...\n","green");
}
$budgetdata = "";
for ($i = 1;$i<24;$i++) {
	$ymd = date("Y-m-01",strtotime("+$i month")); // get ymd for printing date in output transactions
	$maned = explode("-",$ymd)[1];
	foreach ($budget as $key => $acc)  { // get each account and its budget transactions
	foreach ($acc as $month => $monthbudget) { // get budget transactions of account
		//$monthbudget = parseamount($monthbudget,$account,$month,$budgetname);
		$monthbudget = parseamount_array($monthbudget,$account,$month,$budgetname);
		if ($monthbudget == null)continue;
		foreach ($monthbudget as $current) {
			if (intval($month) == intval($maned)) {
				$key = $current['txt'];
				$monthbudget = $current['amount'];
				if ($monthbudget == 0) continue;
				$shortkey = shortkey($key);
				$budgetdata .= "$ymd ⌛💸 $shortkey (m=$month)\n\t$key  $monthbudget\n\tAktiver:Likvider:Bank\n\n";
				$budgetdata .= "$ymd ⌛💸 $shortkey\n\t$key  $monthbudget\n\tAktiver:Likvider:Bank\n\n";
			}
		}
	}
	}
}
for ($i = 1;$i<24;$i++) { // gå igennem alle numre
	$ymd = date("Y-m-01",strtotime("+$i month")); // get ymd for printing date in output transactions
	$maned = explode("-",$ymd)[1]; // find måned
	foreach ($budget as $key => $acc)  { // get each account and its budget transactions
	foreach ($acc as $month => $monthbudget) { // get budget transactions of account
		// $monthbudget = parseamount($monthbudget,$account,$month,$budgetname);
		$monthbudget = parseamount_array($monthbudget,$account,$month,$budgetname);
		if ($monthbudget == null)continue;
		foreach ($monthbudget as $current) {
			if ($month == "all") {
				$key = $current['txt'];
				$monthbudget = $current['amount'];
				if ($monthbudget == 0) continue;
				$shortkey = shortkey($key);
				$budgetdata .= "$ymd ⌛💸 $shortkey ($month)\n\t$key  $monthbudget\n\tAktiver:Likvider:Bank\n\n";
				$budgetdata .= "$ymd ⌛💸 $shortkey\n\t$key  $monthbudget\n\tAktiver:Likvider:Bank\n\n";
			}
		}
	}
	}
}

$fn = "$tpath/.calc/$budgetname.manual.ledger";
if (file_exists($fn))
	$manual = file_get_contents($fn);
else
	$manual = "";

file_put_contents("$tpath/budget.ledger","; " . date("Y-m-d") . "Budget: $budgetfile\n\n". $budgetdata . "\n\n;manual\n".$manual);
	echo set("Skrevet $budgetfile...\n","green");
function parseamount($amount,$account,$month,$budgetname) {
	global $tpath;
	if ($amount === 0) {
		return 0;
	}
	else if (is_numeric($amount)) {
		return $amount;
	}
	else if (stristr($amount,"calc-")) {
		$p = "$month";
		$uid = explode("calc-",$amount)[1];
		$fn = "$tpath/.calc/$uid";
		$data = explode("\n",file_get_contents("$fn"));
		$sum = 0;
		foreach ($data as $line) {
			$x = explode("\t",$line);
			if (isset($x[1]))
				$sum += floatval($x[1]);
		}
		return $sum;
	}
}

function parseamount_array($amount,$account,$month,$budgetname) {
	$retval = array();
	global $tpath;
	if ($amount === 0) {
		return null;
	}
	else if (is_numeric($amount)) {
		return array(array('txt'=>"$account",'amount'=>$amount));
	}
	else if (stristr($amount,"calc-")) {
		$p = "$month";
		$uid = explode("calc-",$amount)[1];
		$fn = "$tpath/.calc/$uid";
		$data = explode("\n",file_get_contents("$fn"));
		$sum = 0;
		foreach ($data as $line) {
			$x = explode("\t",$line);
			$txt = $x[0]; 
			if (isset($x[1])) $amount=$x[1];else $amount=0;
			array_push($retval,array('txt'=>$account . " :" . $txt,'amount'=>$amount));
		}
		return $retval;
	}
}
function shortkey($key) {
	$x = explode(":",$key);
	$x = end($x);
	return $x;
	
}
?>
