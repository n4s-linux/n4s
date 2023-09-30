<?php
$op = exec("whoami");
$noend = getenv("noend");
$tpath = getenv("tpath");

$tpath = getenv("tpath");
$cmd = "LEDGER_DEPTH=999 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 hledger -f curl accounts > \"$tpath\"/.accounts";
system12($cmd);
$ledger_accounts = explode("\n",file_get_contents("$tpath/.accounts")); 

require_once("/svn/svnroot/Applications/fzf.php");
require_once("lookup_account.php");
$mtimez = array();
$cache = array();
$lbf = getenv("LEDGER_BUDGET_STUFF"); // this is not very compliated, it is a hack... fix me anytime 
$lem = getenv("LEDGER_ENTRY_MTIME"); // also hack just to pass envinronment from terminal to system()
require_once("key_accountsfuncs.php");
//require_once("key_csv.php");
require_once("/svn/svnroot/Applications/proc_open.php");
require_once("/svn/svnroot/Applications/key_config.php");
global $path;
require_once("/svn/svnroot/Applications/key_chart_acc.php");
global $aliases;
$editor = "php /svn/svnroot/Applications/key_fzf.php";
if (getenv("DISPLAY") == "")
	$editor = "vim";
else
	$editor = "gvim -f";
$editor = "vim";




//require_once("/svn/svnroot/Applications/key_chart_acc.php");
if ($argv[1] == "entry")
	$data = loadcache();
else
	$data = loadall($path);
$expanded = expand_ek(expand($data));
$i = 0;
$args = "";
$args_uc = array();;
	foreach ($argv as $arg) {
		if ($i > 1)
			$args .= "\"$arg\"" . " ";
		if ($i > 3)
		  array_push($args_uc,$arg);

		$i++;
	}
/*if (isset($argv[1]) && $argv[1] == "openentries") {
	openentries:
	$open = array();
	foreach ($expanded as $file) {
		foreach ($file['Transactions'] as $trans) {
				if ($trans['Account'] == 'Passiver:Kreditorer:Auto:Open'||$trans['Account'] == 'Aktiver:Omsætningsaktiver:Debitorer:Auto:Open') {
					array_push($open,array('file'=>$file,'transaction'=>$trans));
				}

		}
	}
	//now find matching bank transactions;
	foreach ($expanded as $file) {
		foreach ($file['Transactions'] as $trans) {
			if ($trans['Account'] != "Aktiver:Likvider:Bank") continue;
			foreach ($open as $opentrans) {

				if (strtotime($file['Date']) >= strtotime($opentrans['file']['Date']) && $trans['Amount'] == $opentrans['transaction']['Amount']) {
					$bankmatch = $file;
					$debcredmatch = $opentrans;
					if ( maybe_make_match($bankmatch,$debcredmatch) == true)
						break 2;
				}
			}
		}
	}
	if (isset($goback)&&$goback==1)
		goto goback_label;
}*/
if (isset($argv[1]) && $argv[1] == "searchuid") {
	exec_app("$editor /tmp/pasteuids");
	$uids = array_filter(explode(" ",str_replace("─","",file_get_contents("/tmp/pasteuids"))));
	print_r($uids);die();
	$filez = array();
	foreach ($uids as $curuid)
		array_push($filez,trim(shell_exec("grep \"$curuid\" $path/*.trans -l")));
	$vimfilez = "";
	foreach ($filez as $curfile)
		$vimfilez .= " \"$curfile\"";
	exec_app("$editor $vimfilez");
}
if (isset($argv[1]) && $argv[1] == "bilag" ) {
	foreach($data as $file) {
		$orgfile = $file;
		if (strtotime($file["Date"]) < strtotime("-35 days")) continue;
		$amount = $file["Transactions"][0]["Amount"];
		if ($amount == 0) continue; // ingen tomme transaktioner tak
		$fn = str_replace(".trans","",sanitize($file["Filename"] . "_" .$amount ,true) . ".pdf");
		if (!file_exists($path."/vouchers/used/$fn") && !file_exists($path."/vouchers/ignore/$fn")) {
			touch($path."/vouchers/$fn",strtotime($file["Date"])) ;
			$file["HasVoucher"] = "Missing";
		}
		else {
			if (file_exists($path."/vouchers/used/$fn"))
				$file["HasVoucher"] = $fn;
			else if (file_exists($path."/vouchers/ignore/$fn"))
				$file["HasVoucher"] = "Ignore";
			if (json_encode($orgfile) != json_encode($file))
				file_put_contents($file["Filename"],json_encode($file,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		}
		if (json_encode($orgfile) != json_encode($file))
				file_put_contents($file["Filename"],json_encode($file,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		system6("tpath=$path bash /svn/svnroot/Applications/arkiverbilag.bash");
	}
}
if (isset($argv[1]) && $argv[1] == "ledger") {
	if (getenv("LEDGER_CACHE") == "1") {
		$cmd = ("ledger -B -f \"$path/curl\" $args");
		echo "$cmd\n";
		system1("$cmd");
		die();
	}
	$uid = date("Y-m-d") . "_".$op;
	//$cbf = "/tmp/" . "_curl_$uid";
	if ($noend == "") $noend=0;
	$cbf = ".curl_$uid" . "_" . "$noend";
	$o = "";
	if (getenv("reconciliation") == 1) {
		system ("php /svn/svnroot/Applications/csv2ledger.php *.csv> /tmp/.csv2l");
	$o.=file_get_contents("/tmp/.csv2l");
	}
	foreach (expand($data) as $file) {
		if (!nobilag($file) && missingvouchers())
			continue;
		if (!isset($file['Ref']) && isset($file['Reference']))
			$file['Ref'] = $file['Reference'];
		if (!isset($file["Ref"]))
			$file["Ref"] = "";
		$attachedFiles = 0;
		if (isset($file['Filereferences'])){
            $attachedFiles = count($file['Filereferences']);
        }
		$o .= "$file[Date] ($file[Ref]) $file[Description] \t ; FilesAttached: $attachedFiles \n";
//		$o .= "; FilesAttached: 0\n";
		$oo = "";
		foreach ($file['Transactions'] as $trans) {
			if (!isset($trans['id'])) $trans['id'] = "(unset)";
			$o .= "\t$trans[Account]  $trans[Amount] ";
			$o .= "; Filename: $file[Filename]\n\t; TransID: $trans[id]\n\t";
			if (isset($trans["OrigAmount"]))
				$o .= "; OrigAmount: " . $trans["OrigAmount"] . "\n";
			//$o .= "\n";
//			$oo .= "$file[Date] ($file[Ref]) $file[Description]\n";
//			$oo .= "\tUdgifter:Administration:Revisor:Auto  2.5 ; Filename: $file[Filename]\n\tPassiver:Skyldige omkostninger:Revisor:Auto  -2.5 ; Filename: $file[Filename]\n\n";

		}
//        		$o .= "; FilesAttached: 0\n";
		$o .= "\n";
		$o .= $oo;

	}

	system6("echo '' > $path/$cbf");
	if (!missingvouchers())
		system2("cat $path/*.ledger > $path/$cbf 2>/dev/null");
	file_put_contents("$path/$cbf",$o,FILE_APPEND);
	global $lbf; //hack
	global $lem; // also hack
	if (getenv("missing_vouchers") != "1") {
		//$cmd = ("cd $path;ls $path/*.bash -1|while read i; do cat \"\$i\";done > $path/.scrips.bash;LEDGER_CUR_FILE=\"$cbf\" bash $path/.scrips.bash >> $path/$cbf;rm $path/.scrips.bash");
		$basename_i = basename($i);
		$cmd = "cd $path;ls $path/*.bash -1 2>/dev/null|while read i; do echo \"; running bash \$basename_i ...\";LEDGER_CUR_FILE=\"$cbf\" bash \"\$i\" 2>/dev/null;done  >> $path/$cbf";
		if (!missingvouchers() || getenv("LEDGER_SCRIPS") == "run")
			system4("$cmd");
		//file_put_contents("/tmp/cmd.txt",$cmd);
		}
	$cmd = "ledger -f \"$path/$cbf\" b indtægter: udgifter: --depth 1|tail -n1";
	$internalargs = "";
	$cmd = ("cp \"$path/" . $cbf . "\"" .  " \"$path/curl\"; ledger   -B -f \"$path/curl\" $args $internalargs ");
	system3($cmd);
	unlink("$path/$cbf");
	//mkhtml("$path/curl");

}
if (isset($argv[1]) && $argv[1] == "load") {
  loadfile();
}
else if (isset($argv[1]) && $argv[1] == "search_exTrykion") {
	$results = "";
	foreach ($data as $curtrans) {
		$a = 0;
		eval('$a = (' . $argv[2] . ");");
		if ($a) $results .= "\"$path/$curtrans[Filename]\" ";
	}
	if ($results != "")
		exec_app("$editor $results");
}
else if (isset($argv[1]) && $argv[1] == "search") {
	$search_begin = strtotime($argv[2]);
	$search_end = strtotime($argv[3]);
  $results = "";
$resultvim = "";
$count = 0;
  foreach ($data as $curtrans) {
unset($curtrans["History"]); // dont search in history please
    $j = json_encode($curtrans,JSON_UNESCAPED_UNICODE); // put it into json so we can search in the string
		if ($j == false) die("having trouble decoding $curtrans[Filename], talk to Jørgen about this\n");
		$match = true;
		foreach ($args_uc as $curarg) {
		if (!isset($curtrans["Date"])) {
			echo "Error no date in : ";
			print_r($curtrans);die();
		}
		$t = strtotime($curtrans["Date"]);
		if ($t > $search_end || $t < $search_begin)
			{
			$match = false;}
		    if (!stristr($j,$curarg))
					$match = false;
		    }
		if ($match && strlen($curtrans['Filename'])) {
			$count++;
			$results .= "\"$path/$curtrans[Filename]\" ";
			$resultvim .= ":e $path/$curtrans[Filename]\n";
			system5("cp \"$path/$curtrans[Filename]\" \"$path/.$curtrans[Filename].old\";chmod 777 \"$path/.$curtrans[Filename].old\"");

		}
  }
  if ($results != "") {
	$vf = "/tmp/vim_$op";
	file_put_contents($vf,$resultvim);
	//exec_app("$editor $results");
	exec_app("$editor -s $vf");
	exec_app("php /svn/svnroot/Applications/key_arraydiff.php $results");
	system6("rm \"$path\"/.*.old");
	}
}
else if (isset($argv[1]) && $argv[1] == "acclookup") {
	$bal = 0;
	file_put_contents("$path/.accl",lookup_acc($accounts,$bal));
}
else if (isset($argv[1]) && $argv[1] == "entry") {
				goback_label:

//if (!file_exists("$path/chart_of_account"))
system7("cd \"$path\";tpath=\"$path\" noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=$(echo -n $(date +%Y-%m-%d -d \"tomorrow\")) php /svn/svnroot/Applications/key.php ledger bal >/dev/null&");
	file_put_contents("$path/chart_of_account",json_encode($accounts,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
$bal = 0;
$filename = date("Ymd") . uniqid();
$curtrans = array();
$curtrans['Transactions'] = array();
$curtrans['Date'] = get_date();
$curtrans['Ref'] = get_ref();
$curtrans['Description'] = get_desc();
$curtrans['History'] = array(array('Date'=>date("Y-m-d H:i:s"),'Desc'=>'Manual entry'));
if (isset($argv[2]) && $argv[2] == "bilag") 
	$curtrans["Bilag"] = system6("xclip -o -select clipboard");

$curtrans['Filename'] = $filename . "_" . str_file_filter($curtrans['Description']) . ".trans";
	$curtrans['UID'] = uniqid();
  while (1) {
    $acc = lookup_acc($accounts,$bal);
    $am = get_amount($acc,$bal);
    $amount = $am['calculated'];
    $amount_original =  $am['formula'];
    $func = get_func($acc,$bal,$amount);
if ($amount == $amount_original)
    array_push($curtrans['Transactions'], array('Account'=>$acc,'Amount'=>$amount,'Func'=>$func));
else
	array_push($curtrans['Transactions'],array('Account'=>$acc,'Amount'=>$amount,'Func'=>$func,'Amount_Calculation'=>$amount_original));
    $bal += $amount;
    if ($bal == 0) {
      file_put_contents($path . "/".$curtrans['Filename'],json_encode($curtrans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) . "\n");
	
	if (getenv("LEDGER_ENTRY_MTIME") != "")
		touch($path . "/".$curtrans['Filename'],strtotime(explode("-",getenv("LEDGER_ENTRY_MTIME"))[0]));
			$data = loadall($path);
			$expanded = expand_ek(expand($data));
			$goback = 1;
	echo "File gemt...\n";
	echo "\nTryk e to edit or ENTER to exit...\n";
    system7("stty -icanon -echo");
	$c = fread(STDIN, 1);
	if ($c == "e" || $c == "E")
		exec_app("$editor \"$path/$curtrans[Filename]\"");
	system7("stty sane");
	break;
    }
  }
}
function missingvouchers() { // special function enabled by environment vartiable - it will only output transactions for ledger that has missing voucher 
	return (getenv("missing_vouchers") == "1");
}
function nobilag($f) {
	$retval = false;
	if (!isset($f['Filereferences'])) {
		/*
		if (isset($f['Reference']) && $f['Reference'] == "")
			$retval = true;
		else */ if (isset($f['Reference']) && stristr($f['Reference'],"CSV-"))
			$retval = true;
	}
	return $retval;
}
function mkhtml($uri) {
return true;
//skip all this - got another thing for that
	$begin = date("Y-m-d",strtotime(getenv("LEDGER_BEGIN")));
	$end = date("Y-m-d",strtotime(getenv("LEDGER_END")));
	global $path;
	if (!is_dir("$path/html"))
		mkdir("$path/html");
	$hfn = "$path/html/$begin" . "_" . $end . ".html";
	$cmd = "LEDGER_DEPTH=99 ledger -f \"$uri\" reg -S account,date --register-format=\"%(date)|||%(tag('Filename'))|||%(account)|||%(amount)|||%(payee)\\n\" > /tmp/odata 2>/dev/null";
	system8($cmd);
	$data = file_get_contents("/tmp/odata");
	unlink("/tmp/odata");
	$lines = explode("\n",$data);
	$curacc = "";
	$balance = 0;
	ob_start();
?><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<?php
	echo "<table class=table border=1 width=600>";
	foreach ($lines as $line) {
		$cols = explode("|||",$line);
		if (isset($cols[1]))
			$fn = $cols[1];
		else $fn = "#";
		if (!isset($cols[2])) continue;
		if ($cols[2] != $curacc) {
			$curacc = $cols[2];
			$balance = 0;
			echo "</table><br><br><h3>Kontokort for $curacc</h3><table border=1>";
		}
		$balance += round($cols[3],2);
		$balance = round($balance,2);
		$curwidth =[100,600,300,300];
		echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		for ($i = 0;$i<4;$i++) {
			if ($i != 2)
				echo "<td width=$curwidth[$i]><a href=../$fn>$cols[$i]</a></td>";
		}
		echo "<td><a href=../$fn>$balance</a></td>";
		echo "</tr>";

	}
	echo "</table>";
	file_put_contents($hfn,"<meta charset=utf8>");
	system6("/svn/svnroot/Applications/oledger/ledger2html/ledger2html -f \"$uri\" bal >> \"$hfn\"");
	file_put_contents($hfn,ob_get_clean(),FILE_APPEND);

}
function maybe_make_match($bankmatch,$debcredmatch) {
	global $path;
	if (file_exists("$path/nomatch"))
		$nomatch = json_decode(file_get_contents("$path/nomatch"),true);
		else
			$nomatch = array();
	$amount = 0;

	foreach ($bankmatch['Transactions'] as $trans) {
		if ($trans['Account'] == 'Aktiver:Likvider:Bank')
			$amount += $trans['Amount'];
	}

			$matchmd5 = md5(serialize($bankmatch) . serialize($debcredmatch));
			if (in_array($matchmd5,$nomatch)) {
					return;
			}
		echo "Muligt match for " . $debcredmatch['file']['Description'] . " ($amount):\n";
	echo "\t" . $bankmatch['Date'] . "\t" . $bankmatch['Description'] . "\n";
			$fd = fopen("PHP://stdin","r");
			echo "Match it? (y/n)";
			if (getenv("y2all") == "1")
				$txt = "y";
			else
				$txt = trim(fgets($fd));
			if ($txt == "n") {
				array_push($nomatch,$matchmd5);
				file_put_contents("$path/nomatch",json_encode($nomatch,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)."\n");
			}
			if ($txt == "y"||$txt == "j"||$txt == "J") {
				make_match_changes($bankmatch,$debcredmatch);
				die();
			}
	return false;

}
function make_match_changes($bankmatch,$debcredmatch) {
	global $path;
		$dkacc = $debcredmatch['transaction']['Account'];
	$dkaccdone = str_replace(":Open",":Done",$dkacc);

	$fn = $bankmatch['Filename'];
	$data = file_get_contents($fn);
	$data = json_decode($data,true);
	$newtrans = array();
	foreach ($data['Transactions'] as $trans) {
		if ($trans['Account'] == 'Aktiver:Likvider:Bank')
			array_push($newtrans,$trans);
	}
	array_push($newtrans,array('Amount'=>$newtrans[0]['Amount'] * -1,'Func'=>"",'Account'=>"$dkaccdone"));
	$data['Transactions'] = $newtrans;
	$write = array();
	array_push($write, array('filename'=>$bankmatch['Filename'],'data'=>$data));
	$fn = $debcredmatch['file']['Filename'];
		$data = file_get_contents($fn);
	$data = json_decode($data,true);
	$newtrans = array();
	foreach ($data['Transactions'] as $trans) {
		if ($trans['Account'] == $dkacc) {
			//$trans['Account'] .= ":Done";
			$trans['Account'] = str_replace(":Open",":Done",$trans['Account']);
		}
		array_push($newtrans,$trans);
	}
	$data['Transactions'] = $newtrans;
	array_push($write, array('filename'=>$fn,'data'=>$data));
	foreach ($write as $w) {
		file_put_contents($w['filename'],json_encode($w['data'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");

	}
}
function expand_ek($darray) {
	$ektrans = array();
	return $darray;
}
function datemorphology($darray) {
	if (!isset($darray['Description']))
		return $darray;
	if (!isset($darray['Date']))
		return $darray;
	if (stristr($darray['Description'],"#igår")) {
		$curdate = strtotime($darray['Date']);
		$yesterday = date("Y-m-d",strtotime("-1 days",$curdate));
		$darray['Date'] = $yesterday;
		$darray['Description'] .= " (added day after)";
	}
	return $darray;

}
function expand($darray){

$newdarray = array();
$darray_add = array();
foreach ($darray as $dataarray) {
		$newtrans = array();
	if (!isset($dataarray['Transactions'])) continue;
	$id = 0;
	$dataarray = datemorphology($dataarray);
	foreach ($dataarray['Transactions'] as $trans) {
		$sourcetrans = $trans;
		$trans['id'] = $id++;
		if (!isset($trans["Amount"]))
			die($dataarray['Filename'] . " has missing amount inside it\n");
		$trans["OrigAmount"] = $trans["Amount"];
		if (!isset($trans["Func"]))
			$trans["Func"] = "";
		if ($trans["Func"] != "" && stristr($trans['Account'],'Likvider')) {
			echo $dataarray['Filename'] . "\n";
			echo("fejl - der er skrevet funktion ind på en bank/kasse\n");
		}
		if ($trans['Func'] == "iv" || $trans['Func'] == "iv25") {
			$ot = $trans;
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af varekøb udland";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:IV";
			array_push($newtrans,$trans);
			$trans = $ot;

		}
		if ($trans['Func'] == "iy" || $trans['Func'] == "iy25") {
			$ot = $trans;
			$trans['Amount'] = $trans['Amount'] * -0.25;
			$trans['Account'] = "Passiver:Moms:Moms af ydelser udland";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
			$trans["Amount"] = $trans["Amount"] *-1;
			$trans["Account"] = "Passiver:Moms:Købsmoms:IY";
			array_push($newtrans,$trans);
			$trans = $ot;

		}

		if ($trans['Func'] == "u" || $trans['Func'] == "U" || $trans["Func"] == "u25") {
			$trans['Amount'] = $trans['Amount'] * 0.8;
			array_push($newtrans,$trans);
			$sourcetrans = $trans; // very important if we need source trans for periodization
			$trans['Amount'] = $trans['Amount'] /4;
			$trans['Account'] = "Passiver:Moms:Salgsmoms";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
		}
			else	if ($trans['Func'] == "i" || $trans['Func'] == "I") {
			$trans['Amount'] = $trans['Amount'] * 0.8;
			array_push($newtrans,$trans);
			$sourcetrans = $trans; // very important if we need source trans for periodization

			$trans['Amount'] = $trans['Amount'] /4;
			$trans['Account'] = "Passiver:Moms:Købsmoms";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
		}

			else	if ($trans['Func'] == "rep" || $trans['Func'] == "Rep") {
			$vatamount = $trans["Amount"] * 0.05;
			$trans['Amount'] = $trans['Amount'] * 0.95;
			array_push($newtrans,$trans);
			$sourcetrans = $trans; // very important if we need source trans for periodization

			$trans['Amount'] = $vatamount;
			$trans['Account'] = "Passiver:Moms:Købsmoms";
			$trans['id'] = "virt";
			array_push($newtrans,$trans);
		}


		else {
			//always push original trans;
			array_push($newtrans,$trans);

		}
			/*if (substr($sourcetrans['Account'],0,3) == "Ind"||substr($sourcetrans['Account'],0,3) == "Udg") {
				$eqtrans = $sourcetrans;
				$eqtrans['Account'] = "Egenkapital:Periodens resultat";
				array_push($newtrans,$eqtrans);
				$eqtrans['Amount'] = $eqtrans['Amount']*-1;
				$eqtrans['Account'] = "Resultatoverførsel";
				array_push($newtrans,$eqtrans);

			}	*/
		if (getenv("simple") != "1" && isset($trans['P-Start']) && isset($trans['P-End']) && $trans["P-Start"] != $trans["P-End"]) {
			$start = new DateTime($trans['P-Start']);
			$end = new DateTime($trans['P-End']);
			$inc = DateInterval::createFromDateString('first day of next month');
			$end->modify('+1 day');
			$p = new DatePeriod($start,$inc,$end);
						$count = 0;
			$max = null;
			foreach ($p as $d) { $count++; $max = $d ;}
			$ptrans = $dataarray;
			$orgtrans = $dataarray;
			$ptrans['Transactions'] = array();
			if (strtotime($orgtrans['Date']) < strtotime($trans['P-End'])) {
				if (isset($sourcetrans['P-Account']) && strlen($sourcetrans['P-Account']))
					$contra = $sourcetrans['P-Account'];
				else if ($sourcetrans['Amount'] > 0)
					$contra = "Aktiver:Forudbetalte omkostninger:$orgtrans[Description]";
				else
					$contra = "Passiver:Debitor forudbetalinger:$orgtrans[Description]";
			}
			else {
				if (isset($sourcetrans['P-Account']) && strlen($sourcetrans['P-Account']))
					$contra = $sourcetrans['P-Account'];
				else if ($sourcetrans['Amount'] > 0)
					$contra = "Passiver:Skyldige omkostninger:$orgtrans[Description]";
				else
					$contra = "Passiver:Debitor efterbetalinger:$orgtrans[Description]";
			}

			$reversetrans = $sourcetrans;
			$reversetrans["OrigAmount"] = $reversetrans["Amount"];
			$reversetrans['Amount'] = $reversetrans['Amount'] * -1;
			//print_r($p);die();
			array_push($ptrans['Transactions'],$reversetrans);
			$reversetrans['Account'] = $contra;
			$reversetrans['Amount'] = $reversetrans['Amount'] * -1;
			array_push($ptrans['Transactions'],$reversetrans);
			$ptrans['Description'] .= " (P)";
			array_push($darray_add,$ptrans);
			if ($count == 0)
				$amountperiod = round($reversetrans["Amount"],2);
			else
				$amountperperiod = round($reversetrans['Amount'] ,2)/ $count;
			$remainder = $reversetrans['Amount'];
			$leftover = $amountperperiod*$count - floor($amountperperiod * $count);
			$source = $orgtrans;
			$source['Transactions'] = array();
			$i= 0;
			$pcount = -1;
			foreach ($p as $d) $pcount++;
			foreach ($p as $d) {
				$remainder -= $amountperperiod;
				$ptrans = $sourcetrans;
				$source = $orgtrans;
				$source['Transactions'] = array();
				$source['Date'] = $d->format("Y-m-d");
				$ptrans['Amount'] = $amountperperiod;
				$ptrans['id'] = "virt";
				if ($i == $pcount)
					$ptrans['Amount'] += $remainder;
				array_push($source['Transactions'] ,$ptrans);
				$ptrans['Amount'] = $ptrans['Amount'] * -1;
				$ptrans['Account'] = $contra;
				array_push($source['Transactions'],$ptrans);
				$source['Description'] .= " (P)";
				array_push($darray_add,$source);
				$i++;
			}
		}



	}
	/*
	foreach ($newtrans as $sourcetrans) {
			if (substr($sourcetrans['Account'],0,3) == "Ind"||substr($sourcetrans['Account'],0,3) == "Udg") {
				$eqtrans = $sourcetrans;
				$eqtrans['Account'] = "Egenkapital:Periodens resultat";
				array_push($newtrans,$eqtrans);
				print_r($newtrans);
				$eqtrans['Amount'] = $eqtrans['Amount']*-1;
				$eqtrans['Account'] = "Resultatoverførsel";
				array_push($newtrans,$eqtrans);
				print_r($newtrans);

			}
	}
	echo "nothing?";
	die();*/
	$dataarray['Transactions'] = $newtrans;
	array_push($newdarray,$dataarray);

}
	$newdarray = array_merge($newdarray, $darray_add);
//	$newdarray = array_merge($newdarray, $darray_add_ek);
	return $newdarray;

}
function loadcache() {
	global $cache;
	global $path;
	if (file_exists("$path/.cache")){
		$cache = unserialize(file_get_contents("$path/.cache"));;
	}
	else
		$cache = array();
	return $cache;
}
function mtimefast($fn) {
	$fn = basename($fn);
	global $path;
	global $mtimez;
	if (empty($mtimez))  {
		system6("cd $path;" . 'stat -c "%X|||%n" * >' . "$path/.mtimez");
		$data = file_get_contents("$path/.mtimez");
		$x = explode("\n",$data);
		foreach ($x as $line) {
			$y = explode("|||",$line);
			if (!isset($y[1])) continue;
			$mtimez[$y[1]] = $y[0];
		}
	}
	if (!isset($mtimez[$fn])) {
		return time();
	}
	else
		return $mtimez[$fn];
}
function file_get_contents_cached($fn,$customer){
	global $cache;
	global $path;
	if (empty($cache)) loadcache();
	$mt = mtimefast($fn);
	if (!is_dir($fn)) {
		if (!isset($cache[$fn][$mt])) {
			$cache[$fn][$mt] = file_get_contents($fn);
			return $cache[$fn][$mt];
		}
		else
			return $cache[$fn][$mt];
	}
	else {
		echo "$fn is a dir, file_get_contents_cached...\n";
	}
} 
function loadall($path) {
$bn = basename($path);
system("mkdir -p ~/.cache/$bn");
$uid = uniqid();
  system("ls \"$path\" > \"$path/.loadlist.$uid\"");
  $list = file_get_contents("$path/.loadlist.$uid");
  system("rm \"$path/.loadlist.$uid\"");
  $list = explode("\n",$list);
  $data = array();
  foreach ($list as $fn) {
		if (stristr($fn,".tgz")||$fn == "chart_of_account"||$fn == "bin" || $fn == "danløn" || $fn == "comments"||stristr($fn,".php")||stristr($fn,".xbrl")||stristr($fn,".xml")||stristr($fn,".csv")||stristr($fn,"modstridende kopi")||$fn == "vouchers"||$fn == "notes"||$fn == "password"||$fn == "img_tmp"||$fn == "img" ||$fn == "csv"||$fn == "html" ||$fn == "" || $fn == "curl" || stristr($fn,"conflicted copy")||$fn == "log" || $fn == "aliases" || stristr($fn,"logic_") || $fn == "logic" || $fn == "curl_kk.html"|| stristr($fn,".ledger")||$fn=="Forside.html"|| stristr($fn,".bash") || stristr($fn,".sc")) continue;
		$newd = json_decode(file_get_contents_cached("$path/$fn",$bn),true);
		//file_put_contents($fn,json_encode($newd,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		if ($newd == false) {
			$err = date_format($date=date_create('now'),'Y-m-d H:i:s') . " - Unable to json decode $fn - written \"$path/log\"\n";
			file_put_contents("$path/log",$err,FILE_APPEND);
			fwrite(STDERR, $err);
			system("echo \"$err\"");
			die(); //continue;
		}
    	if (stristr($fn,".trans")) {
			if ($newd['Filename'] != $fn) {
				$newd['Filename'] = $fn;
				//file_put_contents($fn,json_encode($newd,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) . "\n");
			}
    array_push($data,$newd);
		}
  }
	$newdata = array();
	foreach ($data as $file) {
		$new = check_accounts($file);
		array_push($newdata,$new);
	}
	system("cd \"$path\"; unlink err.trans 2>/dev/null");
	global $cache;
	file_put_contents("$path/.cache",serialize($cache));
  return $newdata;
}
function account_exists($trans) {
	//we dont be strict now, but we can later... for now we just check if it is top level !

	$e = explode(":",$trans);
	if (isset($e[1]))
		return true;
	else
		return false;
	global $accounts;
	$curaccount = $accounts;
	foreach ($e as $pos) {
		if (isset($curaccount[$pos])) {
			$curaccount = $curaccount[$pos];
		}
		else {
		return false;
		}
	}
	return true;
}
function lookup_acc_older($accounts,$bal) {
	global $path;
    system("stty -icanon -echo");
	echo "Looking up account (balance $bal)\n";
	$curpos = $accounts;
	$accountstring = "";

	//system("clear");
	$v = $curpos;
	$keyz = print_array($curpos);
	$lvl = 0;
	while ($c = fread(STDIN, 1) and has_children($curpos)) {

	//	system("clear");
		//echo "Balance: $bal, Key Tryked: " . ord($c) . "\n";
			$upper = strtoupper($c);
			if (isset($keyz[$upper])) {
				$curpos = $curpos[$keyz[$upper]];
				$lvl++;
				if ($accountstring == "")
					$accountstring = $keyz[$upper];
				else
					$accountstring .= ":$keyz[$upper]";
			}
		else if ($c == "+") {
			system("stty sane");
			echo "Opret ny konto i $accountstring: ";
			$fd = fopen("PHP://stdin","r");
			$a = trim(fgets($fd));fclose($fd);
			$curpos[$a] = array();
			if ($accountstring != "")
				$accountstring .= ":$a";
			else
				$accountstring = "$a";
			system("stty -icanon -echo");

			echo "\n";
			break;
		}
		else if ($c == "/") {
			exec_app("LEDGER_DEPTH=999 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 ledger -f $path/curl accounts|fzf > $path/.acclookup");
			$accountstring = trim(file_get_contents($path."/.acclookup"));
			if (strlen($accountstring) == 0) {
				echo "Ingen konto valgt ved opslag...\n";
				$curpos = "";
				$lvl = 0;
				break;
			}
			else {
				$curpos = $accountstring;
				$lvl++;
				break;
			}
		}




		$v = $curpos;
		if (empty($curpos)) break;
		//echo "$accountstring\n";
		$keyz = print_array($curpos,$lvl);

	}


    system("stty sane");

	return $accountstring;
}
function get_desc() {
	global $path;
	global $op;
	echo "Transaktionstekst (skriv M for flere): ";
	$fd = fopen("PHP://stdin","r");
	$str = trim(fgets($fd));
	if ($str == "M") {
		global $editor;
		exec_app("$editor $path/.memo_$op");
		$str = str_replace("\n"," | ",trim(file_get_contents("$path/.memo_$op")));
		unlink("$path/.memo_$op");
	}
	return $str;
}
function get_ref() {
	echo "Reference (bilag): ";
	$fd = fopen("PHP://stdin","r");
	$str = trim(fgets($fd));
	return $str;
}
function get_date() {
	echo "Dato: ";
	$fd = fopen("PHP://stdin","r");
	$str = trim(fgets($fd));
	if (!strlen($str)) {
		$str = date("Y-m-d");
		echo "Antager dato: $str...\n";
	}
	else if (strlen ($str) == 4) {
			// assuming ddmm or dd/mm or dd-mm
		$day = substr($str,0,2);
		$month = substr($str,-2);
			$year = date("Y");
		echo "Assuming $year-$month-$day\n";
		return "$year-$month-$day";
	}
	else if (strlen($str) == strlen("YYYY-mm-dd")) {
		return $str;
	}
	else {
		echo "Incorret date format ($str), try again...\n";
		return get_date();
	}
	return $str;
}
function get_amount($account,$bal) {
	echo "Indtast beløb for $account (bal $bal): ";
	$fd = fopen("PHP://stdin","r");
	$form = trim(fgets($fd));
	$str = (string)evalmath($form);
	if (strlen($str)) {
		$result = floatval($str);
	}
	else {
		echo "Intet tal angivet, antager restbeløb($bal)...\n";
		$result = $bal*-1;
		$form = "Remainder on manual entry";
	}
	return array("formula"=>$form,"calculated"=>$result);
}
function evalmath($equation)
{
	if ($equation == "") return "";
    $result = 0;
    // sanitize imput
    $equation = preg_replace("/[^a-z0-9+\-.*\/()%]/","",$equation);
    // convert alphabet to $variabel
    $equation = preg_replace("/([a-z])+/i", "\$$0", $equation);
    // convert percentages to decimal
    $equation = preg_replace("/([+-])([0-9]{1})(%)/","*(1\$1.0\$2)",$equation);
    $equation = preg_replace("/([+-])([0-9]+)(%)/","*(1\$1.\$2)",$equation);
    $equation = preg_replace("/([0-9]{1})(%)/",".0\$1",$equation);
    $equation = preg_replace("/([0-9]+)(%)/",".\$1",$equation);
    if ( $equation != "" ){
	if ($equation == "0") return 0;
        $result = @eval("return " . $equation . ";" );
    }
    if ($result == null) {
        throw new Exception("Unable to calculate equation");
    }
    return $result;
   // return $equation;
}
function get_func($account,$bal,$amount) {
	system("clear"); 
	$func = fzf("Ingen\nu\ni\niy\niv\nrep\n", "Vælg funktion (moms)", "--border=sharp");
	if ($func == "Ingen")
		return "";
	else
		return ($func);
}
function has_children($array) {
	$children = 0;
	foreach ($array as $key) {
		if (is_array($key))
			$children++;
	}
	if ($children == 0)
		return false;
	else
		return true;
}
function print_array($ar,$lvl=0) {
return;
	$retval = array();
$hclz = array();
	$nhcl = 1;
	//if (has_children($ar)) {
		foreach ($ar as $key => $val) {
			for ($i = 0;$i<$lvl*3;$i+=1) echo "\t";
			preg_match( '/[A-Z]/', $key, $matches, PREG_OFFSET_CAPTURE );
			//$hcl = $matches[0][0];
			if (!isset($matches[0][0])) {
				$hcl = $nhcl++;
			}
			else {
				if (!in_array($matches[0][0],$hclz)) {
					$hcl = $matches[0][0];
					array_push($hclz,$hcl);
				}
				else
					$hcl = $nhcl++;
			}
			echo "$hcl - $key\n";
			$retval[$hcl] = $key;
		}
	return $retval;
	//}
	/*else {
		foreach ($ar as $key) {
			for ($i = 0;$i<$lvl*3;$i+=1) echo "\t";
			echo "\t*$key\n";
		}
	}*/
}
function str_file_filter(
	$str,
	$sep = '_',
	$strict = false,
	$trim = 75) {
	$str = strip_tags(htmlspecialchars_decode(strtolower($str))); // lowercase -> decode -> strip tags
	$str = str_replace("%20", ' ', $str); // convert rogue %20 into spaces
	$str = preg_replace("/%[a-z0-9]{1,2}/i", '', $str); // remove hexy things
	$str = str_replace("&nbsp;", ' ', $str); // convert all nbsp into space
	$str = preg_replace("/&#?[a-z0-9]{2,8};/i", '', $str); // remove the other non-tag things
	$str = preg_replace("/\s+/", $sep, $str); // filter multiple spaces
	$str = preg_replace("/\.+/", '.', $str); // filter multiple periods
	$str = preg_replace("/^\.+/", '', $str); // trim leading period
	if ($strict) {
		$str = preg_replace("/([^\w\d\\" . $sep . ".])/", '', $str); // only allow words and digits
	} else {
		$str = preg_replace("/([^\w\d\\" . $sep . "\[\]\(\).])/", '', $str); // allow words, digits, [], and ()
	}
	$str = preg_replace("/\\" . $sep . "+/", $sep, $str); // filter multiple separators
	$str = substr($str, 0, $trim); // trim filename to desired length, note 255 char limit on windows
	$str = str_replace("'","",$str);
		$str = str_replace(")","",$str);
			$str = str_replace("()","",$str);


	return $str;
}
// Returns full file name including fallback and extension
function str_file(
	$str,
	$sep = '_',
	$ext = '',
	$default = '',
	$trim = 248) {
	// Run $str and/or $ext through filters to clean up strings
	$str = str_file_filter($str, $sep);
	$ext = '.' . str_file_filter($ext, '', true);
	// Default file name in case all chars are trimmed from $str, then ensure there is an id at tail
	if (empty($str) && empty($default)) {
		$str = 'no_name__' . date('Y-m-d_H-m_A') . '__' . uniqid();
	} elseif (empty($str)) {
		$str = $default;
	}
	// Return completed string
	if (!empty($ext)) {
		return $str . $ext;
	} else {
		return $str;
	}
}
function sanitize($string = '', $is_filename = FALSE)
{
 // Replace all weird characters with dashes
 $string = preg_replace('/[^\w\-'. ($is_filename ? '~_\.' : ''). ']+/u', '-', $string);

 // Only allow one dash separator at a time (and make string lowercase)
 return mb_strtolower(preg_replace('/--+/u', '-', $string), 'UTF-8');
}
function system1($a) { return system($a); }

function system2($a) { return system($a); }

function system3($a) { return system($a); }

function system4($a) { return; return system($a); }

function system5($a) { return system($a); }

function system6($a) { return system($a); }

function system7($a) { return system($a); }

function system8($a) { return system($a); }

function system12($a) { return system($a); }
?>
