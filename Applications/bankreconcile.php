<?php
	require_once("/svn/svnroot/Applications/proc_open.php");
	exec_app(" whiptail --msgbox \"To reconcile the bank, please select bank account and paste Your CSV file\"  0 0 < /dev/tty");
	date_default_timezone_set('Europe/Copenhagen');
	$tpath = getenv("tpath");
	if ($tpath == "") die("requires tpath\n");
	ob_start();
	system("color=none noend=1 php /svn/svnroot/Applications/newl.php csv");
	$lines = explode("\n",trim(ob_get_clean()));
	$accounts=array();
	$newdata = array();
	foreach ($lines as $curline) {
		$data = str_getcsv($curline);
		array_push($newdata,array("Date"=>str_replace("\"","",$data[0]),"Description"=>$data[2],"Amount"=>$data[5],"Account"=>$data[3]));
		if (!in_array($data[3],$accounts)) array_push($accounts,$data[3]);	
	}
	require_once("/svn/svnroot/Applications/fzf.php");
	$fzf = "";
	foreach ($accounts as $curacc) $fzf .= "$curacc\n";
	$valg = fzf(trim($fzf),"Chose acc to reconcile against statement");
	if ($valg == "") die("no account selected\n");
	$account_transactions = array();
	$data = $newdata;
	foreach ($data as $curdata) {
		if ($curdata["Account"] == $valg) {
			array_push($account_transactions,$curdata);
		}
	}
	$md = md5($valg);
	exec_app("vim $tpath/.reconcile_$md.csv");
$array = $fields = array(); $i = 0;
$handle = @fopen("$tpath/.reconcile_$md.csv", "r");
if ($handle) {
    while (($row = fgetcsv($handle, 4096,"\t")) !== false) {
        if (empty($fields)) {
            $fields = $row;
            continue;
        }
        foreach ($row as $k=>$value) {
            $array[$i][$fields[$k]] = $value;
        }
        $i++;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
	$csvtransactions = $array;
	$spec = array();
	foreach ($csvtransactions as $curcsv) {
		if (!isset($curcsv["Date"])) die("Requires Date column\n");
		if (!isset($curcsv["Amount"])) die("Requires Amount column\n");
		if (!isset($curcsv["Description"])) die("Requires Description column\n");
		$date = date("Y-m-d",strtotime($curcsv["Date"]));
		$amount = number_format(str_replace(",",".",$curcsv["Amount"]),2,".","");
		if (strtotime($date) < strtotime(getenv("LEDGER_BEGIN"))) continue;
		if (strtotime($date) > strtotime(getenv("LEDGER_END") . " -1 day")) continue;
		if (!isset($spec["$date"]["$amount"])) $spec["$date"]["$amount"] = array();
		$curcsv["Amount"] = str_replace(",",".",$curcsv["Amount"]);
		$curcsv["Source"] = "CSV";
		array_push($spec["$date"]["$amount"],$curcsv);
	}
	foreach ($account_transactions as $curdata) {
		$date = str_replace("/","-",trim($curdata["Date"]));
		$date = date("Y-m-d",strtotime($date));
		if (strtotime($date) > strtotime(getenv("LEDGER_END") . " +1 day")) continue;
		if (strtotime($date) < strtotime(getenv("LEDGER_BEGIN"))) continue;
		$amount = number_format(str_replace(",",".",$curdata["Amount"]),2,".","");
		if (!isset($spec["$date"]["$amount"])) $spec["$date"]["$amount"] = array();
		$curdata["Amount"] = floatval($curdata["Amount"]);
		$curdata["Source"] = "Transactions";
		$curdata["Date"] = $date;
		array_push($spec["$date"]["$amount"],$curdata);
	}
	$diff = 0;

		$specsbal =0;
	echo "\nResult:\n";
	ksort($spec);
	foreach ($spec as $curspec) {
		foreach ($curspec as $key) {
			$curbal = 0;
			$curtxt = "";
			foreach ($key as $curval) {
				$spectext = "";
				$c = $curval;
				$c["Date"] = str_replace("/","-",$c["Date"]);
				$spectext = "$c[Date]\t$c[Description]\t$c[Amount]\n";
				if ($curval["Source"] == "CSV") { $specsbal += floatval($c["Amount"]); $curbal += floatval($c["Amount"]);}  else { $specsbal -= floatval($c["Amount"]);  $curbal -= floatval($c["Amount"]);}
				$balp = number_format($specsbal,2,",",".");
				if ($curval["Source"] == "CSV")
					$amountp = number_format(-floatval($curval["Amount"]),2,",",".");
				else
					$amountp = number_format(floatval($curval["Amount"]),2,",",".");
				$shortdesc = substr($c["Description"],0,20);
				$spectext = str_pad($c["Date"],12) . mb_str_pad($shortdesc,38) . str_pad($amountp,15," ",STR_PAD_LEFT) . str_pad($balp,15," ",STR_PAD_LEFT);
				if ($curval["Source"] == "CSV") 
					$spectext =  "\033[38;5;117mCSV " . $spectext . "\033[0m\n";
				else
					$spectext =  "\033[38;5;226mSYS " . $spectext . "\033[0m\n";

				$curtxt .= $spectext;
			}
			if (intval($curbal)!= 0) { echo $curtxt; $curtxt="";}
		}
	}
	die();
}
function mb_str_pad( $input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT)
{
    $diff = strlen( $input ) - mb_strlen( $input );
    return str_pad( $input, $pad_length + $diff, $pad_string, $pad_type );
}	
?>
