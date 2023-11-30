<?php
$rentesats = getenv("rente");
$prefix = "Rente.php: ";
$tpath = getenv("tpath");
$bn = basename($tpath);
$begin = getenv("LEDGER_BEGIN");
$end = date("Y-m-d",strtotime(getenv("LEDGER_END") . " -1 day"));
require_once("/svn/svnroot/Applications/short.php");
$op = exec("whoami");
$fd = fopen("PHP://stdin","r");
if (!isset($argv[1])) die("Kræver konto\n");
$konto = $argv[1];
echo "Beregner rente for $konto - sats $rentesats...\n";
$transactions=array();
while ($line = fgetcsv($fd,null,",","\"","\\")) { // GET DATA 
	$date = $line[0];
	if (isset($line[1])) $bilag = $line[1]; else $bilag = "";
	if (isset($line[2])) $desc = $line[2]; else $desc = "";
	if (isset($line[3])) $curkonto = $line[3];else $curkonto = "";
	if ($curkonto != $argv[1]) continue;
	if (isset($line[7])) $fn = $line[7]; else $fn = "";
	if (isset($line[5]))
		$belob = floatval($line[5]);
	else
		$belob = 0;
	array_push($transactions,array('Dato'=>$date,"Tekst"=>$desc,"Beløb"=>$belob));
}
$months = array();
foreach ($transactions as $curtrans) {
	$now = strtotime($end);
	$then = strtotime($curtrans['Dato']);
	$thendate = date("Y-m-d",$then);
	$then = strtotime("$thendate +1 month");
	while ($then < $now) {
		$td = date("Y-m-d",$then);
		$nd = date("Y-m-d",$now);
		if (!isset($months[date("Y-m",$then)])) $months[date("Y-m",$then)] = 0;
		$months[date("Y-m",$then)] += $curtrans['Beløb'] / 12 * $rentesats / 100;
		$thendate = date("Y-m-d",$then);
		$then = strtotime("$thendate +1 month");
	}
}
foreach ($months as $curmonth => $rente) {
	if (strtotime($curmonth . "-01") < strtotime($begin)) continue;
	$ofn = md5($konto) . "_rente_" . $curmonth . ".trans";
	if (file_exists($ofn))  continue; 
	$data = array();
	$data['Filename'] = $ofn;
	$mk = explode(":",$konto);
	$mk = $mk[count($mk)-1];
	$data['Description'] = "Rente ($rentesats%) - $mk";
	$data['Reference'] = "RENTE";
	$data['Date'] = $curmonth . "-01";
	$data['UID'] = uniqid();
	$rente = number_format($rente,2,".","");
	$data['Transactions'] = array(
			array('Account'=>$konto . ":Renter",'Amount'=>$rente),
			array('Account'=>"Udgifter:Renteudgifter:$mk",'Amount'=>-$rente)
	);
	print_r($data);
	echo "Press ENTER to book, or CTRL-C to exit...\n";
	$fd = fopen("/dev/tty","r");
	fgets($fd);
	fclose ($fd);	
	file_put_contents("$tpath/$ofn",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	system("tpath=$tpath LEDGER_END=$end LEDGER_DEPTH=999 LEDGER_BEGIN=1970-01-01 noend=1 php /svn/svnroot/Applications/newl.php csv | rente=$rentesats php /svn/svnroot/Applications/rente.php \"$konto\"");
}
