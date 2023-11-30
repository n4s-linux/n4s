<?php
	$deletebilag = array();
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
	if (!empty($deletebilag)) rundelbilag();
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
	$cmd = ("cp $fn $tpath/.curl$begin-$end; tpath=$tpath LEDGER_BEGIN=$begin LEDGER_END=$end ledger --no-pager -X -B -f $tpath/.curl$begin-$end ");
	if ($nargs[0] == "openentries") {
		system("mkdir -p $tpath/.openentries");
		require_once("/svn/svnroot/Applications/openentries.php");
		require_once("/svn/svnroot/Applications/newl_csv.php");
		$x = getcsv("1970-01-01",$end,$tpath);
		$dk = getdebcred($x);
		$open = getopen($dk);
		$fejl = getfejl($x);
		$match = findmatch($open,$fejl);
		while ($match != false) {
			echo $match['Open']['Date'] . "\t" . str_pad($match['Open']['tekst'],25," ") . "\t" . $match['Open']['Amount'] . "\n";
			echo $match['Fejl']['Date'] . "\t" . str_pad($match['Fejl']['tekst'],25," ") . "\t" . $match['Fejl']['Amount'] . "\n";
			echo "\nVil du matche disse ? (j/n): ";
			$fd = fopen("PHP://stdin","r");$str = trim(fgets($fd));	fclose($fd);
			if ($str == "j") {
				$fnfejl = gettag($match['Fejl'],'Filename');
				$tid = gettag($match['Fejl'],"TransID");
				$filf = json_decode(file_get_contents("$tpath/$fnfejl"),true);
				$filf['Transactions'][$tid]['Account'] = $match['Open']["Account"];
				$filf['Transactions'][$tid]['Func'] = "";
				$filf['History'][] = array('Date'=>date("Y-m-d H:m"),'op'=>exec("whoami"),"Desc"=>"Automatisk udligning af kreditor via openentries");
				file_put_contents("$tpath/$fnfejl",json_encode($filf,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
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
		require_once("/svn/svnroot/Applications/nc.php");
		require_once("/svn/svnroot/Applications/newl_csv.php");
		$data = getcsv($begin,$end,$tpath);
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
			$konti = explode("\n",fzf("NY\n" . getkontoplan($x),"vælg konto bal=$bal","--bind 'enter:toggle+accept'  --bind 'tab:toggle+down+clear-query' --multi"));
			foreach ($konti as $konto) {
				if ($konto == "NY") {
					echo "Indtast kontostreng: ";
					$fd = fopen("PHP://stdin","r");$konto = trim(fgets($fd)); fclose($fd);
				}
				$belob = round(askamount($konto,$bal), 2);
				require_once("/svn/svnroot/Applications/get_func.php");
				$func = get_func($konto,$bal,$belob);
				$bal += $belob;
				$v .= "\e[33m$belob\t$konto\t$func\n\e[0m";
				$f['Transactions'][$i]['P-Start'] = "";
				$f['Transactions'][$i]['P-End'] = "";
				$f['Transactions'][$i]['Account'] = $konto;
				$f['Transactions'][$i]['Func'] = $func;
				$f['Transactions'][$i]['Amount'] = $belob;
				$i++;
			}
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
		system("php /svn/svnroot/Applications/newl.php b >/dev/null");
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
		global $ledgerdata;
		ob_start();
		system("cd $tpath;bash $file");
		$ld = ob_get_clean();
		$ledgerdata .= $ld;
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
				$tid = (isset($ct['id'])) ? $ct['id'] : $counter;
				$comment = " ; Filename: $c[Filename] |||| TransID: $tid "; 
				if ($book) {
					$comment .= " |||| Løbenr $nextnumber |||| Hash $hash";
					$nextnumber++;
				}
				if (!$pretty) $ledgerdata .= $comment; 
				$ledgerdata .= "\n";
				if (!isset($ct['id'])) $counter++;
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
	function rundelbilag() {
		global $deletebilag;;
		global $tpath;
		foreach ($deletebilag as $bilag) {
			echo "deleting $bilag\n";
			unlink($bilag);
		}
	}
	function booktrans($file) {
		global $tpath;
		global $transactions;
		global $op;
		global $deletebilag;
		$newtrans = json_decode(fgc($tpath."/".$file),true);
		if (!isset($newtrans['Reference'])) $newtrans['Reference'] = "";
		if ($newtrans['Reference'] == "p") $newtrans['Reference'] = file_get_contents("/home/$op/tmp/.curfile");
		if (isset($newtrans['Reference']) && isFile($newtrans['Reference'])) {
			if (!file_exists($newtrans['Reference'])) {
				$newtrans['Reference'] = str_replace("'","",trim($newtrans['Reference']));
				$nb = getnextbilagnumber($tpath);
				$bn = basename($newtrans['Reference']);
				$cmd = "mkdir -p $tpath/.vouchers;cp \"$newtrans[Reference]\" \"$tpath/.vouchers/$nb - $bn\"";
				$deletebilag[] = $newtrans['Reference'];
				$filemd5 = md5_file($newtrans['Reference']);
				$mtime = filemtime($newtrans['Reference']);
				$newtrans['Files'][] =  array('Filename'=>$nb . " - $bn","Hash"=>$filemd5,"Mtime"=>date("Y-m-d H:m",$mtime));
				$newtrans['Reference'] = $nb;
				$nb++;
				$newtrans['History'][] = array('fn'=>$bn,'op'=>$op,'desc'=>"Uploadet bilag $newtrans[Reference]",'date'=>date("Y-m-d H:m"),'filemd5'=>$filemd5);
				system("$cmd");
				file_put_contents("$tpath/$file",json_encode($newtrans,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
				file_put_contents("$tpath/.nextbilagnumber",$nb);
				rundelbilag();
			}
		}
		$transactions[] = $newtrans;
	}
function isFile($file) {
        $f = pathinfo($file, PATHINFO_EXTENSION);
        return (strlen($f) > 0) ? true : false;
    }
	function unhandled($file) {
		global $ledgerdata;
		$ledgerdata .= "; unhandled $file\n";
	}
function filter_filename($name) {
	$name = str_replace("#","_",$name);
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
