<?php
$debug = true;
require_once("/svn/svnroot/vendor/autoload.php");
require_once("/home/joo/.ecocred.php");
$client = new Lenius\Economic\RestClient($appToken, $grant);

$d = getalltransactions();
$g = group($d);
save_transactions($g);
function getalltransactions() {
$accyears = get("accounting-years")['collection'];
$transactions = array();
foreach ($accyears as $accyear) { // for hvert regnskabsår
	$e = str_replace("https://restapi.e-conomic.com/","",$accyear['entries']);
	$results = true;
	$page = 0;
	while ($results == true) {
		$t = get($e,array('skippages'=>$page++));
		if (!empty($t['collection'])) {
			$t = $t['collection'];
			foreach ($t as $curt) {
				$transaction = array();
				$flats = array('amountInBaseCurrency','date','entryNumber','text','voucherNumber');
				foreach ($flats as $curflat) {
					if (isset($curt[$curflat]))
						$transaction[$curflat] = $curt[$curflat];
				}
				$transaction['account'] = $curt['account']['accountNumber'];
				array_push($transactions,$transaction);
			}
		}
		else $results = false;
	}
}

	return $transactions;
}
function group($transactions) {
	$grouped = array();
	foreach ($transactions as $cur) {
		if (!isset($cur['voucherNumber'])) $cur['voucherNumber'] = "ECO-SYS";
		$grouped[$cur['date'].":" .$cur['voucherNumber']][] = $cur;
	}
	return $grouped;
}
function save_transactions($gtrans) { //gtrans stands for grouped - expects transactions grouped by date,voucher
	$tpath = trim(getenv("tpath"));
	if ($tpath == "") die("tpath ikke sat");
	foreach ($gtrans as $date => $cur) {
		if ($cur[0]['voucherNumber'] == "ECO-SYS") continue;
		$data = array();
		$data['Filename'] = "economic_transaktion_$date" . ".trans";
		$data['Date'] = date("Y-m-d",strtotime(explode(":",$date)[0]));
		$data['Description'] = (isset($cur[0]['text']) ? $cur[0]['text'] : "Ingen tekst");
		$data['Reference'] = $cur[0]['voucherNumber'];
		$data['History'][] = array('desc'=>'loaded from economic','date'=>date("Y-m-d H:m"));
		$bal = 0;
		foreach ($cur as $t) {
			$bal += round($t['amountInBaseCurrency'],2);
			$data['Transactions'][] = array('Account'=>$t['account'],'Func'=>'','Amount'=>$t['amountInBaseCurrency']);
		}
		if ($bal != 0) {
			$bal = round($bal,2) * -1;
			$data['Transactions'][] = array('Account'=>'E-conomic-ubalancer','Func'=>'','Amount'=>$bal);
		}
		if (!file_exists("$tpath/$data[Filename]")) {
			file_put_contents("$tpath/$data[Filename]",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		else {
			
			$data['History'][] = array('desc'=>'transaction was updated in e-conomic','date'=>date("Y-m-d H:m"));
			file_put_contents("$tpath/$data[Filename]",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
	}
}
function get($getwhat,$additionalparams = array()) {
	$op = exec("whoami");
	$getfn = str_replace("/","_",$getwhat);
	$pfn = "";foreach ($additionalparams as $parm=>$val) $pfn .= "$parm-$val";
	$cf = "/home/joo/tmp/ecocache_" . $op . "_" . $getfn . "-" . $pfn;
	global $debug;
	echo "getting $getwhat\n";
	$parms = ['pagesize' => 1000];
	$parms = array_merge($parms,$additionalparams);
	global $client;
	global $grant;
	global $apptoken;
	if ($debug && file_exists($cf)) {
		return json_decode(file_get_contents($cf),true);	
	}
	else {
		$response = $client->request->get($getwhat, $parms);
		$status = $response->httpStatus();
		if ($status == 200) {
			$data = $response->asArray();
			file_put_contents($cf,json_encode($data));
			return $data;
		}
		else 
			print_r($response);die();
	}
}
