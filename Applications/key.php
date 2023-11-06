<?php
require_once("/svn/svnroot/Applications/datemorph.php");
require_once("/svn/svnroot/Applications/expand.php");
require_once("/svn/svnroot/Applications/get_func.php");
require_once("/svn/svnroot/Applications/short.php");
$termcmd = getenv("termcmd");
if ($termcmd == "")
	$termcmd = "terminal";
function s2t($str) { return strtotime($str);}
$op = exec("whoami");
require_once("lookup_account.php");
$mtimez = array();
$cache = array();
$lbf = getenv("LEDGER_BUDGET_STUFF"); // this is not very compliated, it is a hack... fix me anytime 
$lem = getenv("LEDGER_ENTRY_MTIME"); // also hack just to pass envinronment from terminal to system()
require_once("/svn/svnroot/Applications/key_chart_acc.php");
require_once("key_accountsfuncs.php");
//require_once("key_csv.php");
require_once("/svn/svnroot/Applications/proc_open.php");
require_once("/svn/svnroot/Applications/key_config.php");
global $path;
global $aliases;
$editor = "php /svn/svnroot/Applications/key_fzf.php";
if (getenv("DISPLAY") == "")
	$editor = "vim";
else
	$editor = "gvim -f";
$editor = "vim";
if ($argv[1] == "entry")
	$data = loadcache();
else {
	$data = loadall($path);
}
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
if (isset($argv[1]) && $argv[1] == "ledger") {
	if ($path == "" ) {
		die("Ingen path.");
	}
	$loutput_uid = uniqid();
	$uid = date("Y-m-d") . "_".exec("whoami");
	$cbf = ".curl_$op";
	if (!file_exists("$path/$cbf"))	system("echo '' > $path/$cbf");
	$argz = implode(":",$argv);
	$o = "; $argz\n";
	if (getenv("reconciliation") == 1) {
		system ("php /svn/svnroot/Applications/csv2ledger.php *.csv> /home/$op/tmp/.csv2l");
	$o.=file_get_contents("/home/$op/tmp/.csv2l");
	}
	foreach ($expanded as $file) {
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
		if (isset($file['Reference']))
			$file['Reference'] = str_replace("\\","",$file['Reference']);
		if (isset($file['Ref']))
			$file['Ref'] = str_replace("\\","",$file['Ref']);
		$file['Description'] = str_replace("\\","",$file['Description']);
		$oo = "\n";
		$noend = getenv("noend");
		$begin = getenv("LEDGER_BEGIN");$end=getenv("LEDGER_END");
		if (!isset($file['Date'])) continue;
		if ($noend == "") {
			if (strtotime($file['Date']) < strtotime($begin)) continue;
			if (strtotime($file['Date']) > strtotime($end)) continue;
		}
		$o .= "$file[Date] ($file[Ref]) $file[Description] \t ; FilesAttached: $attachedFiles \n";
		$oo = "\n";
		foreach ($file['Transactions'] as $trans) {
			if (!isset($trans['id'])) $trans['id'] = "(unset)";
			$o .= "\t$trans[Account]  $trans[Amount] ";
			$o .= "; Filename: $file[Filename]\n\t; TransID: $trans[id]\n\t";
			if (isset($trans["OrigAmount"]))
				$o .= "; OrigAmount: " . $trans["OrigAmount"] . "\n";
			//$o .= "\n";

		}
		$o .= $oo;

	}
	system("echo '' > $path/$cbf");
	$begin = getenv("LEDGER_BEGIN");
	if (!missingvouchers()) {
		if (getenv("noend") != "1")
			system("cat $path/*.ledger $path/.Åbning_$begin.ledger > $path/$cbf 2>/dev/null");
		else
			system("ls $path/*.ledger 2>/dev/null|grep -v Åbning_|xargs cat > $path/$cbf 2>/dev/null");
	}
	file_put_contents("$path/$cbf",$o,FILE_APPEND);
	global $lbf; //hack
	global $lem; // also hack
	if (getenv("LEDGER_MISSING_VOUCHERS") != "1") {
		$basename_i = basename($i);
		$cmd = "cd $path;ls $path/*.bash -1 2>/dev/null|while read i; do echo \"; running bash \$basename_i ...\";LEDGER_CUR_FILE=\"$cbf\" bash \"\$i\" 2>/dev/null;done  >> $path/$cbf";
		if (!missingvouchers())
			system("$cmd");
		$cmd = ("cp \"$path/" . $cbf . "\"" .  " \"$path/curl\"; ledger   -B -f \"$path/curl\" $args > ~/tmp/loutput_$loutput_uid");
		system($cmd);
		$lretval = 0;
		system("ledger -B -f \"$path/$cbf\" b foobarbiditybas 2> /dev/null",$lretval);
		if ($lretval === 0) {
			$basename = basename("$path");
			$nopopup = getenv("nopopup");
			if ($nopopup != 1 && $termcmd != "terminal")
				$cmd = ("cp \"$path/" . $cbf . "\"" .  " \"$path/curl\"; ledger   -B -f \"$path/curl\" $args > ~/tmp/loutput_$loutput_uid;tmux $termcmd \"vim ~/tmp/loutput_$loutput_uid\"");
			else
				$cmd = ("cp \"$path/" . $cbf . "\"" .  " \"$path/curl\"; ledger   -B -f \"$path/curl\" $args");
		}
		else {
			$thedate=date("Y-m-d H:m");
			system("ledger -f \"$path/$cbf\" b foobarbiditybas 2>> /dev/stdout |while read line; do echo \"$thedate\t\$line\" >> \"$path/log\";done");
			echo "der er fejl, ledger er IKKE opdateret";
			die();

		}
		system($cmd);
		$time=time();
		system("cp $path/$cbf $path/curl 2>/dev/null;rm -f $path/$cbf");
}
}
if (isset($argv[1]) && $argv[1] == "load") {
  loadfile();
}
else if (isset($argv[1]) && ($argv[1] == "search" || $argv[1] == "bulk" )) {
	if (!isset($argv[2]) || !isset($argv[3]))
		die("Søgning kræver du har sat periode - skriv 'dp' eller tryk Alt-p\n");
	$search_begin = strtotime($argv[2]);
	$search_end = strtotime($argv[3]);
  $results = "";
$resultvim = "";
$count = 0;
$sresult = array();
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
			array_push($sresult,$curtrans);
			$count++;
			$results .= "\"$path/$curtrans[Filename]\" ";
			$resultvim .= ":e $path/$curtrans[Filename]\n";
			$cmd = "cp \"$path/$curtrans[Filename]\" \"$path/.$curtrans[Filename].old\";chmod 777 \"$path/.$curtrans[Filename].old\"";
			system($cmd);


		}
  }
require_once("sortsearch.php");
  if ($results != "") {
	$resultvim = "";
	$fzf = "Alle\n";
	require_once("shortacc.php");
	$sresult = dosorting($sresult);
	//usort($sresult,"sortsearch_desc");
	foreach ($sresult as $curres) {
		$curres['Description'] = substr($curres['Description'],0,25);
		$konto = str_pad(shortacc($curres['Transactions'][0]['Account']),4," ",STR_PAD_LEFT);
		$m1 = ($curres['Transactions'][0]['Func']);
		$m2 = ($curres['Transactions'][1]['Func']);
		if (count($curres['Transactions']) > 2) $mk = "FLERE";
		$mk =  str_pad(shortacc($curres['Transactions'][1]['Account']),4," ",STR_PAD_LEFT);		
		if (count($curres['Transactions']) > 2) $mk = "FLERE";
		$a = str_pad(number_format($curres['Transactions'][0]['Amount'],2,",","."),10," ",STR_PAD_LEFT);
		error_reporting(0);
		$fzf .= "$curres[Date]\t$curres[Reference]\t$curres[Description]\t$a\t$konto ($m1) \t$mk ($m2)\t⚡$curres[Filename]\n";
		error_reporting(E_ALL);
	}
	require_once("fzf.php");
	if (getenv("justshowall") == "1") 
		{ $res = array(); $res[0] = "Alle"; }
	else
		$res = explode("\n",fzf($fzf,"Vælg søgeresultat","--multi --exact",true));
	if (trim($res[0]) == "Alle") {
		foreach ($sresult as $curres) {
			$fn = $curres['Filename'];
			$resultvim .= ":e $fn\n";
		}
	}
	else {
		foreach ($res as $line) {
			if (trim($line) == "") continue;
			$fn= explode("⚡",$line)[1];
			$resultvim .= ":e $fn\n";
		}
	}
	require_once("/svn/svnroot/Applications/ansi-color.php");
	system("clear");
	foreach ($res as $curres) { echo set($curres. "\n","blue_bg");}
	$action = fzf("Redigér\nÆndr Konto1\nÆndr Konto2\nÆndr Func1\nÆndr Func2\nÆndr reference","Vælg en handling","--height=10");
	$op = exec("whoami");
	$vf = "/tmp/vim_$op";
	file_put_contents($vf,$resultvim);
	//exec_app("$editor $results");
	if ($action == "Ændr reference"||$action == "Ændr Konto1"||$action=="Ændr Konto2"||$action == "Ændr Func1"||$action=="Ændr Func2") {
		$nyværdi="";
		if ($action == "Ændr reference"||$action=="Ændr Func1"||$action=="Ændr Func2") {
			$fd = fopen("PHP://stdin","r");
			$str = "";
			while ($str == "") {
				echo "$action: ";
				$str = trim(fgets($fd));
			}
			$nyværdi = trim($str);
		}
		else if ($action == "Ændr Konto1" || $action == "Ændr Konto2") {
			$nykonto = lookup_acc($accounts,0);
		}
		foreach (explode("\n",$resultvim) as $curfile) {
			$fn = trim(substr($curfile,3));
			if ($fn == "") continue;
			if (!file_exists("$tpath/$fn"));
			$data = json_decode(fgc("$tpath/$fn"),true);

			if ($action == "Ændr Func1") {
				$oldref = $data['Transactions'][0]['Func'];
				$data['History'][] = array('date'=>date("Y-m-d H:m"),'op'=>$op,"Description"=>"Ændret Func1 fra '$oldref' to '$nyværdi'");
				$data['Transactions'][0]['Func'] = $nyværdi;
				file_put_contents("$tpath/$fn",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			else if ($action == "Ændr Func2") {
				$oldref = $data['Transactions'][1]['Func'];
				$data['History'][] = array('date'=>date("Y-m-d H:m"),'op'=>$op,"Description"=>"Ændret Func2 fra '$oldref' to '$nyværdi'");
				$data['Transactions'][0]['Func'] = $nyværdi;
				file_put_contents("$tpath/$fn",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			else if ($action == "Ændr reference") {
				$oldref = $data['Reference'];
				$data['History'][] = array('date'=>date("Y-m-d H:m"),'op'=>$op,"Description"=>"Ændret Reference fra '$oldref' to '$nyværdi'");
				$data['Reference'] = $nyværdi;
				file_put_contents("$tpath/$fn",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			else if ($action == "Ændr Konto1") {
				$oldkonto = $data['Transactions']['0']['Account'];
				$data['History'][] = array('date'=>date("Y-m-d H:m"),'op'=>$op,"Description"=>"Ændret Konto1 fra '$oldkonto' to '$nykonto'");
				$data['Transactions'][0]['Account'] = $nykonto;
				file_put_contents("$tpath/$fn",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
			else if ($action == "Ændr Konto2") {
				$oldkonto = $data['Transactions']['1']['Account'];
				$data['History'][] = array('date'=>date("Y-m-d H:m"),'op'=>$op,"Description"=>"Ændret Konto2 fra '$oldkonto' to '$nykonto'");
				$data['Transactions'][1]['Account'] = $nykonto;
				file_put_contents("$tpath/$fn",json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
			}
		}
		
	}
	else if ($action == "Redigér") {
		if (strlen(trim($resultvim))) {
			exec_app("$editor -s $vf");
			exec_app("php /svn/svnroot/Applications/key_arraydiff.php $results");
			system("rm \"$path\"/.*.old");
		}
	}
	}
}
else if (isset($argv[1]) && $argv[1] == "acclookup") {
	$bal = 0;
	file_put_contents("$path/.accl",lookup_acc($accounts,$bal));
}
else if (isset($argv[1]) && $argv[1] == "entry") {
				goback_label:

//if (!file_exists("$path/chart_of_account"))
system("cd \"$path\";tpath=\"$path\" noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=$(echo -n $(date +%Y-%m-%d -d \"tomorrow\")) nopopup=1 php /svn/svnroot/Applications/key.php ledger bal >/dev/null&");
	file_put_contents("$path/chart_of_account",json_encode($accounts,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
$bal = 0;
$filename = date("Ymd") . uniqid();
$curtrans = array();
$curtrans['Transactions'] = array();
$curtrans['Date'] = get_date();
$curtrans['Reference'] = get_ref();
$curtrans['Comment'] = "";
$curtrans['Description'] = get_desc();
$curtrans['History'] = array(array('Date'=>date("Y-m-d H:m"),'Desc'=>"Manual entry $op"));
if (isset($argv[2]) && $argv[2] == "bilag") 
	$curtrans["Bilag"] = system("xclip -o -select clipboard");

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
	$bal = round($bal,2);
    if ($bal == 0) {
      file_put_contents($path . "/".$curtrans['Filename'],json_encode($curtrans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) . "\n");
	
	if (getenv("LEDGER_ENTRY_MTIME") != "")
		touch($path . "/".$curtrans['Filename'],strtotime(explode("-",getenv("LEDGER_ENTRY_MTIME"))[0]));
			$expanded = expand_ek(expand($data));
			$goback = 1;
	echo "File gemt - uid $curtrans[UID]...\n";
	echo "\nTryk e to edit or ENTER to exit...\n";
    system("stty -icanon -echo");
	$c = fread(STDIN, 1);
	if ($c == "e" || $c == "E")
		exec_app("$editor \"$path/$curtrans[Filename]\"");
	system("stty sane");
	break;
//stty sane
			//goto openentries;

			/*begin new transaction
			$curtrans = array();
      $curtrans['Transactions'] = array();
      $curtrans['Date'] = get_date();
    $curtrans['Ref'] = get_ref();
    $curtrans['Description'] = get_desc();
    $curtrans['Filename'] = $filename;
		*/
    }
  }
}
function missingvouchers() { // special function enabled by environment vartiable - it will only output transactions for ledger that has missing voucher 
	return (getenv("LEDGER_MISSING_VOUCHERS") == "1");
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
	system($cmd);
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
	system("/svn/svnroot/Applications/oledger/ledger2html/ledger2html -f \"$uri\" bal >> \"$hfn\"");
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
		if (stristr($trans['Account'],'Likvider'))
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
				//system("git -C \"$path\" commit nomatch -m 'Nye non matches'");
			}
			if ($txt == "y"||$txt == "j"||$txt == "J") {
				make_match_changes($bankmatch,$debcredmatch);
				die();
			}
	return false;

}
function expand_ek($darray) {
	$ektrans = array();
	return $darray;
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
		system("cd $path;" . 'stat -c "%X|||%n" * >' . "$path/.mtimez");
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
	return file_get_contents($fn);
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
		if (trim($fn) == "") continue;
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
//				//system("git -C \"$path\" commit  \"$fn\" -m 'Filename problem fixed inside loadall()'");
			}
    array_push($data,$newd);
		}
  }
	return $data;
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
	$op = exec("whoami");
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
		$result = round(floatval($str),2);
	}
	else {
		echo "Intet tal angivet, antager restbeløb($bal)...\n";
		$result = round($bal*-1,2);
		$form = "Remainder on manual entry";
	}
	return array("formula"=>$form,"calculated"=>number_format($result,2,".",""));
}
require_once("/svn/svnroot/Applications/math.php");
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
?>
