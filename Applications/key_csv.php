<?php
require_once("/svn/svnroot/Applications/str_file_filter.php");
require_once("/svn/svnroot/Applications/proc_open.php");
require_once("/svn/svnroot/Applications/print_array.php");
require_once("/svn/svnroot/Applications/lookup_account.php");
require_once("/svn/svnroot/Applications/oledger/csv_to_array.php");
if (isset($argv[1]) && $argv[1] == "load") {
	loadfile();
	echo "loaded()";sleep(5);
	die();
}
function loadfile() {
	$path = getenv("tpath");
  global $accounts;
  $availablefields =array("Date","Description","Reference","Amount","Account","Func","Comment","Choose","Currency","AmountCurrency","Bankfee");;
       array_push($availablefields,"No match");
$op=exec("whoami");
	global $argv;
	if (!isset($argv[2]))
  $filename = "/home/$op/tmp/" . time() . ".csv";
	else
		$filename = $argv[2];
       exec_app("nano $filename");
	if (!file_exists($filename)) die("afbrudt ingen data\n");
       $d = detectDelimiter("$filename");
       $data = csv_to_array("$filename",$d);
	unlink($filename);
       $contraacc = lookup_acc($accounts,0,"Bankimport - Select Account to import to");
       $mappings = array();
	require_once("/svn/svnroot/Applications/fzf.php");
       foreach ($data['header'] as $header) {
		$mappings[$header] = fzf(implode("\n",$availablefields),"chose what field $header maps to");
		if ($mappings[$header] == "") die("Aborted the csv import\n");
		if ($mappings[$header] == "Choose") {
			echo "Indtast navn på felt du ønsker at indlæse '$header' i: ";
			$fd = fopen("PHP://stdin","r");$str = trim(fgets($fd));fclose($fd);
			$mappings[$header] = $str;
		}
       }
       foreach ($data['rows'] as $row) {
           $c = array();
           foreach ($mappings as $csvheader => $datafield) {
                   $c[$datafield] = $row[$csvheader];
           }
      $c['Amount'] = str_replace(".","",$c['Amount']);       $c['Amount'] = str_replace(",",".",($c['Amount'])); 	$c['Date'] = str_replace(".","-",$c['Date']); 	$c['Date'] = str_replace("/","-",$c['Date']);
if (isset($c['Func'])) 	$curfunc = $c['Func']; else 	$curfunc = "";
if (isset($c['Account'])) 	$curacc = $c['Account']; else 	$curacc = "";
           $curtrans['Transactions'] = array();
	$curtrans['Comment'] = "";
         $curtrans['Description'] = clean($c['Description']);
	$x = explode("-",$c['Date']);
	if (strlen($x[0]) == 2 && strlen($x[1]) == 2 && strlen($x[2]) == 4)
		$curtrans['Date'] = date("Y-m-d",strtotime($x[2]."-" . $x[1] . "-" . $x[0]));
        else if (strlen($x[0]) == 2 && strlen($x[1]) == 2 && strlen($x[2]) == 2)
		$curtrans['Date'] = date("Y-m-d",strtotime(2000+$x[2]."-" . $x[1] . "-" . $x[0])); // could be problematic for the next generation
	else // assume iso
		$curtrans['Date'] = date("Y-m-d",strtotime($c['Date']));
	if (strtotime($curtrans['Date']) > strtotime("+1 days") && getenv("future") != "1") {
		$jsondata = json_encode($curtrans);
		continue;
		die("Aborting, we should not import future transactions $c[Date]...\n");
	}
         $curtrans['UID'] = uniqid();
	if (isset($c["Reference"]))
		 $curtrans['Reference'] = $c['Reference'];
	else
		$curtrans["Reference"] = "";
         if ($curtrans['Reference'] == "")
           $curtrans['Reference'] = 'CSV' . "-" . $curtrans['UID'];
         $curtrans['Filename'] =  $path . "/" . str_file_filter($c['Description'] . " - " . $curtrans['Date']) . "-$curtrans[UID].trans";
         $fn = $curtrans['Filename'];
         $curtrans['Filename'] =  str_file_filter($c['Description'] . " - " . $curtrans['Date']) . "-$curtrans[UID].trans";
$curtrans['History'] = array(array('op'=>$op,'Date'=>date("Y-m-d H:i"),'Desc'=>'Indlæst CSV'));
                 $curtrans['Transactions'] = array(
                   array('Account'=>$contraacc,'Amount'=> $c['Amount'],'Func'=>''),
                   array('P-Start'=>'','P-End'=>'','Account'=> ($curacc == "") ? 
                         (( $c['Amount'] < 0) ? "Fejlkonto:Uhåndterede kreditorbetalinger" : "Fejlkonto:Uhåndterede debitorbetalinger") : $curacc
                         ,'Func'=>$curfunc,'Amount'=> $c['Amount'] * -1)
                   );
		if (isset($c["Currency"]) && $c["Currency"] != "") $curtrans["Transactions"][0]['Currency'] = $c["Currency"];
		if (isset($c["AmountCurrency"]) && $c["AmountCurrency"] != "") $curtrans["Transactions"][0]['AmountCurrency'] = number_format($c["AmountCurrency"],2,".","");
		if (isset($c["Bankfee"]) && $c["Bankfee"] != "") {
			$c["Bankfee"] = floatval(str_replace(",",".",$c["Bankfee"]));
			if ($c["Bankfee"] != 0) {
				array_push($curtrans["Transactions"],array("Account"=>"Bankfee","Amount"=>$c["Bankfee"]*-1,"Func"=>""));
				array_push($curtrans["Transactions"],array("Account"=>$contraacc,"Amount"=>$c["Bankfee"] ,"Func"=>""));
			}
		}
         file_put_contents($fn,json_encode($curtrans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
         }

         system("LEDGER_END=2099-12-31 LEDGER_BEGIN=1970-01-01 php /svn/svnroot/Applications/newl.php r \"$contraacc\"|tail -n10"); 
die();

}
function detectDelimiter($csvFile)
{
    $delimiters = array(
        ';' => 0,
        ',' => 0,
        "\t" => 0,
        "|" => 0
    );

    $handle = fopen($csvFile, "r");
    $firstLine = fgets($handle);
    fclose($handle); 
    foreach ($delimiters as $delimiter => &$count) {
        $count = count(str_getcsv($firstLine, $delimiter));
    }

    return array_search(max($delimiters), $delimiters);
}
function clean($string) {
	$string = preg_replace('/[\x00-\x1F\x7F]/u', '', $string);
	$string = str_replace(";",":",$string);
	$string = str_replace("\\","",$string);
   return $string;
}
?>
