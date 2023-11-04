<?php
	$tpath = getenv("tpath");
	if ($tpath == "") die("new.php kræver tpath\n");
	$transactions = array();
	if (getenv("noend") != "1")
		$ledgerdata = getopening();
	else	$ledgerdata="";
	require_once("/svn/svnroot/Applications/datemorph.php");
	require_once("/svn/svnroot/Applications/short.php");
	require_once("/svn/svnroot/Applications/expand.php");
	$files = preg_grep('/^([^.])/', scandir($tpath)); // dont show hidden files
	foreach($files as $file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if ($ext == "trans") booktrans($file);
		else if ($ext == "ledger") bookledger($file);
		else if ($ext == "bash") bookbash($file);
		else unhandled($file);
	}
	scanalias($transactions); // check and fix missing aliases
	$x = expand($transactions); //transactions gets data from global environment set in functions
	$ledgerdata .= getledgerdata($x);

	$uid = uniqid();
	$op = exec("whoami");
	$fn = "/home/$op/tmp/.newl-$uid";
	file_put_contents("$fn",$ledgerdata);
	$nargs = $argv;
	array_shift($nargs);
	$cmd = ("cp $fn $tpath/curl; ledger -f $fn");
	if ($nargs[0] != "entry") {
		foreach ($nargs as $curarg)
			$cmd .= " $curarg";
		system("$cmd");
	}
	else
		entry();
	function entry() {
		global $tpath;
		global $op;
		global $x;
		$f = array();
		$f['Date'] = askdate();
		$f['Reference'] = askref();
		$f['Description'] = askdesc();
		$f['Transactions'] = array();
		$bal = -1;
		$i = 0;
		while ($bal != 0) {
			if ($bal == -1 ) $bal = 0;
			require_once("/svn/svnroot/Applications/fzf.php");
			$konto = fzf(getkontoplan($x),"vælg konto bal=$bal");
			$belob = askamount($konto,$bal);
			require_once("/svn/svnroot/Applications/get_func.php");
			$func = get_func($konto,$bal,$belob);
			$bal += $belob;
			$f['Transactions'][$i]['Account'] = $konto;
			$f['Transactions'][$i]['Func'] = $func;
			$f['Transactions'][$i]['Amount'] = $belob;
			$i++;
		}
		$filename = date("Ymd") . uniqid();
		$f['History'] = array(array('Date'=>date("Y-m-d H:m"),'Desc'=>"Manual entry $op"));
		$f['Filename'] = $filename . "_" . filter_filename($f['Description']) . ".trans";
		file_put_contents("$tpath/$f[Filename]",json_encode($f,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Gemt $tpath/$f[Filename]\n";
	}

	function askamount($konto,$bal) {
		$bal = $bal *-1;
		$rv = getstdin("Beløb for $konto ($bal)");
		return ($rv == "") ? $bal:$rv;	
	}
	function getkontoplan($x) {
		$r = array();
		foreach ($x as $curpost) {
			foreach ($curpost['Transactions'] as $curtrans) {
				$konto = $curtrans['Account'];
				if (!in_array($konto,$r)) array_push($r,$konto);
			}
		}
		$rv = "";
		foreach ($r as $curr) $rv .= "$curr\n";
		return $rv;
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
			foreach ($curtrans['Transactions'] as $curtransaction) {
				$x = explode(":",$curtransaction['Account']);
				if (!isset($x[1])) missingalias($curtrans['Filename'],$i);
				$i++;
			}
		}
	}
	function bookbash($file) {
		global $tpath;
		$op = exec("whoami");
		$uid = md5($file);
		$time = date("Y-m-d");
		$buffer = "/home/$op/tmp/.buffer_" . $uid . "-" . $time;
		global $ledgerdata;
		if (stristr($file,"Periodresult") || !file_exists($buffer)) {
			ob_start();
			echo "; running $file ...\n";
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
	function getledgerdata($x) {
		$ledgerdata = "";
		foreach ($x as $c) {
			if (isset($c['Reference'])) $ref = $c['Reference']; else $ref = "";
			$ledgerdata .= "$c[Date] ($ref) $c[Description]\n";
			foreach ($c['Transactions'] as $ct) {
				$ledgerdata .= "\t$ct[Account]  $ct[Amount]\n";
			}
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
    return $name;
}
?>
