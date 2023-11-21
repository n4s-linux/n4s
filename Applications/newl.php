<?php
	$nargs = $argv;
	array_shift($nargs);
	$tpath = getenv("tpath");
	require_once("/svn/svnroot/Applications/nextnumber.php");
	$nextnumber = getnextnumber($tpath);
	$nextcbnumber = getnextcbnumber($tpath);
	$orgnextnumber = $nextnumber;
	$op = exec("whoami");
	if ($tpath == "") die("new.php kræver tpath\n");
	$transactions = array();
	if (getenv("noend") != "1" && $nargs[0] != "book")
		$ledgerdata = getopening();
	else	$ledgerdata="";
	require_once("/svn/svnroot/Applications/datemorph.php");
	require_once("/svn/svnroot/Applications/short.php");
	require_once("/svn/svnroot/Applications/expand.php");
	$files = preg_grep('/^([^.])/', scandir($tpath)); // dont show hidden files
	foreach($files as $file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if ($ext == "trans") booktrans($file);
		// nargs[0] != book er når vi ikke er ved at bogføre, - når vi skal bogføre skal vi ikke have tidligere bogførte posteringer med, men kun transaktionsfiler - heller ikke scripts bliver bogført
		else if ($ext == "ledger" && $nargs[0] != "book") bookledger($file);
		else if ($ext == "bash" && $nargs[0] != "book") bookbash($file);
		else if ($nargs[0] != "book") unhandled($file);
	}
	scanalias($transactions); // check and fix missing aliases
	$x = expand($transactions); //transactions gets data from global environment set in functions
	require_once("/svn/svnroot/Applications/addresult.php");
	$x = addresult($x);
	$ledgerdata .= getledgerdata($x);
	$uid = uniqid();
	$fn = "/home/$op/tmp/.newl-$uid";
	file_put_contents("$fn",$ledgerdata);
	$begin = getenv("LEDGER_BEGIN");
	$end = getenv("LEDGER_END");
	$cmd = ("cp $fn $tpath/curl; tpath=$tpath LEDGER_BEGIN=$begin LEDGER_END=$end ledger --no-pager -X -B -f $tpath/curl ");
	if ($nargs[0] == "openentries") {
		system("mkdir -p $tpath/.openentries");
		require_once("/svn/svnroot/Applications/openentries.php");
		$dk = getdebcred($x);
		$open = getopen($dk);
		$fejl = getfejl($x);
		$match = findmatch($open,$fejl);
		while ($match != false) {
			echo $match['Open']['Date'] . "\t" . str_pad($match['Open']['Description'],25," ") . "\t" . $match['Open']['Amount'] . "\n";
			echo $match['Fejl']['Date'] . "\t" . str_pad($match['Fejl']['Description'],25," ") . "\t" . $match['Fejl']['Amount'] . "\n";
			echo "\nVil du matche disse ? (j/n): ";
			$fd = fopen("PHP://stdin","r");$str = trim(fgets($fd));	fclose($fd);
			if ($str == "j") {
				$fnfejl = $match['Fejl']['Filename'];
				$filf = json_decode(file_get_contents("$fnfejl"),true);
				$t = &$filf['Transactions'];
				$fid = $match['Fejl']['id'];
				$oldval = $t[$fid]['Account'];
				$newval = $match['Open']['Account'];
				$t[$fid]['Account'] = $newval;
				$filf['History'][] = array('op'=>exec("whoami"),'date'=>date("Y-m-d H:m"),'description'=>"Ændret konto for transaktion $fid fra $oldval til $newval");
				file_put_contents($fnfejl,json_encode($filf,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
				system("touch " . $match['Ignorefile']);
				echo "saved " . $fnfejl . "\n";
			}
			else{
				system("touch " . $match['Ignorefile']);
				echo "Ignored...\n";
			}
			$match = findmatch($open,$fejl,30);
		}
		echo "no entries to match\n";
	}
	else if ($nargs[0] == "book") {
		require_once("/svn/svnroot/Applications/nextnumber.php");
		$nextnumber = getnextnumber($tpath);
		if (file_exists("$tpath/Mainbook.ledger"))
			$curbook = fgc("$tpath/Mainbook.ledger");
		else
			$curbook = "";
		$ledgerdata = getledgerdata($x,true,false,$curbook);
		require_once("/svn/svnroot/Applications/proc_open.php");
		file_put_contents("$tpath/.bookpreview",$ledgerdata);
		exec_app("less $tpath/.bookpreview");
		$jn = "Nej\nJa";
		require_once("/svn/svnroot/Applications/fzf.php");
		$jn = fzf($jn,"Vil du bogføre den viste kladde?");
		if ($jn == "Ja") {
			$nextnumber = $orgnextnumber; // global nummercounter skal genstartes fordi vi har kørt data før
			$ledgerdata = getledgerdata($x,true,false);
			file_put_contents("$tpath/Mainbook.ledger",$ledgerdata);
			file_put_contents("$tpath/.nextnumber",$nextnumber);
			file_put_contents("$tpath/.nextcbnumber",$nextcbnumber +1);
			system("cd $tpath&&mkdir -p $tpath/.cashbooks/$nextcbnumber/&&mv $tpath/*.trans $tpath/.cashbooks/$nextcbnumber/");
		}
	}
	else if ($nargs[0] == "ui") {
		echo "launching ui... we should load csv as datasource for ledger backwards compatiblity - or get rid of ledger completely\n";
		require_once("nc.php");
		$csv = shell_exec("cp $fn $tpath/curl;tpath=$tpath LEDGER_BEGIN=$begin LEDGER_END=$end ledger --no-pager -X -B -f $tpath/curl csv --no-pager");
		$lines = explode(PHP_EOL, $csv);
		$array = array();
		$data = array();
		foreach ($lines as $line) {
		    $array = str_getcsv($line);
			if (!isset($array[3]) || $array[3] == "") continue;
			$bilag = $array[1];
			$konto = $array[3];
			$tekst = $array[2];
			$dato = $array[0];
			$belob = $array[5];
			$tags = $array[7];
			array_push($data,array('Account'=>$konto,'tekst'=>$tekst,'Date'=>$dato,'Amount'=>$belob,'tags'=>$tags,'bilag'=>$bilag));
		}
		ui($data); // x = expanded
	}
	else if ($nargs[0] == "interest") {
		calcinterest($x);		
	}
	else if ($nargs[0] != "entry") { // this is where we pass the ledger commands - todo pass them properly even with quotes and stuff, to make it a proper working full wrapper
		foreach ($nargs as $curarg)
			$cmd .= " $curarg";
		system("$cmd");
	}
	else {
		entry();
	}
	function gettag($str,$tag) {
		return "here it is\n";
	}
	function entry() {
		global $tpath;
		global $op;
		global $x;
		$f = array();
		$f['Transactions'] = array();
		$bal = -1;
		$i = 0;
			$v = "";
		while (round($bal,2) != 0) {
			if ($bal == -1 ) $bal = 0;
			require_once("/svn/svnroot/Applications/fzf.php");
			$konto = fzf("NY\n" . getkontoplan($x),"vælg konto bal=$bal");
			if ($konto == "NY") {
				echo "Indtast kontostreng: ";
				$fd = fopen("PHP://stdin","r");$konto = trim(fgets($fd)); fclose($fd);
			}
			$belob = round(askamount($konto,$bal), 2);
			require_once("/svn/svnroot/Applications/get_func.php");
			$func = get_func($konto,$bal,$belob);
			$bal += $belob;
			$v .= "\e[33m$belob\t$konto\t$func\n\e[0m";
			$f['Transactions'][$i]['Account'] = $konto;
			$f['Transactions'][$i]['Func'] = $func;
			$f['Transactions'][$i]['Amount'] = $belob;
			$i++;
		}
		$filename = date("Ymd") . uniqid();
		$f['History'] = array(array('Date'=>date("Y-m-d H:m"),'Desc'=>"Manual entry $op"));
		echo $v."\n";
		$f['Date'] = askdate();
		$f['Reference'] = askref();
		$f['Description'] = askdesc();
		$f['Filename'] = $filename . "_" . filter_filename($f['Description']) . ".trans";
		file_put_contents("$tpath/$f[Filename]",json_encode($f,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Gemt $tpath/$f[Filename]\n";
	}
	function askamount($konto,$bal) {
		require_once("/svn/svnroot/Applications/math.php");
		$bal = $bal *-1;
		$rv = getstdin("Beløb for $konto ($bal)");
		if ($rv == "") $rv = $bal;
		return evalmath($rv);
	}
	function getkontoplan($x) {
		global $tpath;
		$r = array();
		foreach ($x as $curpost) {
			foreach ($curpost['Transactions'] as $curtrans) {
				$konto = $curtrans['Account'];
				if (!in_array($konto,$r)) array_push($r,$konto);
			}
		}
		$rv = "";
		foreach ($r as $curr) $rv .= "$curr\n";
		if (file_exists("$tpath/.accounts"))
			$accounts = fgc("$tpath/.accounts");
		else
			$accounts = "";
		$rv = trim($rv) . "\n" . trim($accounts);
		$x = explode("\n",$rv);
		$x = array_unique($x);
		arsort($x,SORT_STRING);
		$x = trim(implode("\n",$x));
		return $x;
	}
	function askdesc() {
		return getstdin("Indtast tekst");
	}
	function askref() {
		return getstdin("Indtast reference");
	}
	function askdate() {
		$curdate =date("Y-m-d");
		$s = getstdin("Indtast dato");
		return ($s == "") ? $curdate : $s;
	}
	function getstdin($prompt) {
		echo "$prompt: ";
		$fd = fopen("PHP://stdin","r");
		$s = trim(fgets($fd));
		fclose ($fd);
		return $s;
	}
	function scanalias($t) {
		foreach ($t as $curtrans) {
			$i = 0;
			if (!isset($curtrans['Transactions'])) continue;
			foreach ($curtrans['Transactions'] as $curtransaction) {
				$x = explode(":",$curtransaction['Account']);
				if (!isset($x[1])) missingalias($curtrans['Filename'],$i);
				$i++;
			}
		}
	}
	function bookbash($file) {
		global $tpath;
		global $op;
		$uid = md5($tpath.$file);
		$time = date("Y-m-d");
		$buffer = "/home/$op/tmp/.buffer_" . $uid . "-" . $time;
		global $ledgerdata;
		if (stristr($file,"Periodresult") || (!file_exists($buffer))) {
			ob_start();
			system("cd $tpath;bash $file");
			$ld = ob_get_clean();
			$ledgerdata .= $ld;
			file_put_contents($buffer,$ld);
		}
		else {
			$ledgerdata .= "; buffered $file from $buffer\n" . fgc($buffer);
		}
	}
	function getopening()  {
		$begin = getenv("LEDGER_BEGIN");
		$tpath = getenv("tpath");
		$fn = "$tpath/.Åbning_$begin.ledger";
		if (file_exists($fn)) return file_get_contents($fn); else return "; No opening available $fn";
	}
	function getledgerdata($x,$book = false,$pretty = false,$currentledger = "") {
		$ledgerdata = $currentledger;
		if ($book == true && $ledgerdata == "") $ledgerdata = "\n; Dette er hovedbogen. Hver transaktion bekræfter alle transaktioner forinden med deres samlede md5 checksum - det vil sige hvis du ændrer i denne bog bliver checksummen ugyldig og bogen er manipuleret - efter denne besked starter transaktionerne fra Løbenummer 1 og frem\n\n";
		global $nextnumber;
		foreach ($x as $c) { // for hver transaktion der skal bogføres
				if ($book == true) $hash = md5(trim($ledgerdata));
				if (isset($c['Reference'])) $ref = $c['Reference']; else $ref = "";
				$ledgerdata .= "$c[Date] ($ref) $c[Description]\n";
			$counter = 0;
			foreach ($c['Transactions'] as $ct) {
				$ledgerdata .= "\t$ct[Account]  $ct[Amount] ";
				$comment = " ; Filename: $c[Filename] |||| TransID: $counter "; 
				if ($book) {
					$comment .= " |||| Løbenr $nextnumber |||| Hash $hash";
					$nextnumber++;
				}
				if (!$pretty) $ledgerdata .= $comment; 
				$ledgerdata .= "\n";
			}
				$ledgerdata .= "\n";
		}
		return $ledgerdata;
	}
	function missingalias($file,$id) {
		$tpath = getenv("tpath");
		if (true) {
			$aliases = json_decode(fgc("$tpath/aliases"),true);
			$filedata = json_decode(fgc("$tpath/$file"),true);
			$d = $filedata['Transactions'][$id]['Account'];
			if (!isset($aliases[$d])) {
				$aliases[$d] = "Mangler";
				file_put_contents("$tpath/aliases",json_encode($aliases,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
			else if (isset($aliases[$d]) && $aliases[$d] != "Mangler") {
				$filedata['Transactions'][$id]['Account'] = $aliases[$d];
				file_put_contents("$tpath/$file",json_encode($filedata,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
		}
	}
	function bookledger($file) {
		global $tpath;
		global $ledgerdata;
		$ledgerdata .= "; ledger file $file\n";
		$ledgerdata .= fgc($tpath."/".$file);
	}
	function booktrans($file) {
		global $tpath;
		global $transactions;
		$transactions[] = json_decode(fgc($tpath."/".$file),true);
	}
	function unhandled($file) {
		global $ledgerdata;
		$ledgerdata .= "; unhandled $file\n";
	}
function filter_filename($name) {
    // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
    $name = str_replace(array_merge(
        array_map('chr', range(0, 31)),
        array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
    ), '', $name);
    // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
	$name = str_replace(" ","__",$name);
    return $name;
}
?>
