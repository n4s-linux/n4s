<?php
	if (!isset($argv[1])) die("Kræver start"); $start = $argv[1];
	if (!isset($argv[2])) die("Kræver name"); $name = $argv[2];
	if (!isset($argv[3])) die("Kræver account1"); $account1 = $argv[3];
	if (!isset($argv[4])) die("Kræver func1"); $f1 = $argv[4];

	if (!isset($argv[5])) die("Kræver account2"); $account2 = $argv[5];
	if (!isset($argv[6])) die("Kræver func2"); $f2 = $argv[6];
	if (!isset($argv[7])) die("Kræver amount"); $amount = $argv[7];

	$tpath = getenv("tpath");
	$current = $start;
	while (strtotime($current) < time()) {
	$current = date("Y-m-d",strtotime("$current + 1 month"));
		$data = array();
		$fn = str_replace(" ","_",$name) . "_auto" . $current . ".trans";
		if (file_exists("$tpath/$fn")) { continue;}
		fwrite(STDERR, "Creating " . $name . " - " . $current . "\n");
		$data['Description'] = "$name (Automatisk transaktion)";
		$data['Reference'] = uniqid();
		$data['Date'] = $current;
		$data['Filename'] = $fn;
		$data['Comment'] = "";
		$data['Transactions'][] = array('Account'=>$account1,'Func'=>$f1,'Amount'=>$amount);
		$data['Transactions'][] = array('Account'=>$account2,'Func'=>$f2,'Amount'=>-$amount);
		$data['History'][] = array('op'=>exec("whoami"),'Desc'=>"Oprettet auto transaktion",'Date'=>date("Y-m-d H:m"));
		file_put_contents("$tpath/$fn",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		
	}
?>
