<?php
	$lockthesefiles = array();
	$aliases_warning_displayed = 0;
	$undefined_aliascount = 0;
	require_once("/svn/svnroot/Applications/ansi-color.php");
	require_once("/svn/svnroot/Applications/lookup_account.php");
	require_once("/svn/svnroot/Applications/fzf.php");
	require_once("/svn/svnroot/Applications/openentries.php");
	require_once("/svn/svnroot/Applications/newl_csv.php");
	require_once("rewrite.php");
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
	if (getenv("budget") == 1 && file_exists("$tpath/.budget.ledger")) bookledger(".budget.ledger");
	foreach($files as $file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if ($ext == "trans") booktrans($file);
		// nargs[0] != book er når vi ikke er ved at bogføre, - når vi skal bogføre skal vi ikke have tidligere bogførte posteringer med, men kun transaktionsfiler - heller ikke scripts bliver bogført
		else if ($ext == "ledger" && $nargs[0] != "book") bookledger($file);
		else if ($ext == "bash" && $nargs[0] != "book") bookbash($file);
		else if ($ext == "php" && $nargs[0] != "book") bookphp($file);
		else if ($nargs[0] != "book") unhandled($file);
	}
	if (!empty($deletebilag)) rundelbilag();
	scanalias($transactions); // check and fix missing aliases
	$x = expand($transactions); //transactions gets data from global environment set in functions
	require_once("/svn/svnroot/Applications/addresult.php");
	if (getenv("skipresult") == "") $x = addresult($x);
	$ledgerdata .= getledgerdata($x);
	$uid = uniqid();
	$fn = "/home/$op/tmp/.newl-$uid";
	file_put_contents("$fn",$ledgerdata);
	$begin = getenv("LEDGER_BEGIN");
	$end = getenv("LEDGER_END");
	$cmd = ("cp $fn $tpath/.curl$begin-$end; tpath=$tpath LEDGER_BEGIN=$begin LEDGER_END=$end ledger --no-pager -X -B -f $tpath/.curl$begin-$end ");
	if ($nargs[0] == "search") {
		$s = $argv;
		unset($s[0]);
		unset($s[1]);
		$searchresults = array();
		foreach ($transactions as $curtrans) {
			$res = searchfor($s,$curtrans);
			if ($res != -1)
				array_push($searchresults,$res);
		}
		displaysearch($searchresults,$s);
	}
	else if ($nargs[0] == "suggestions") {
		$x = getcsv("1970-01-01",$end,$tpath);
		$fejl = getfejl($x,true);
		$suggestioncount = 0;
		$accepted = 0;
		$yestoall = 0;
		foreach ($fejl as $curfejl) {
			$similar = findsimilar($curfejl,$transactions);
			if (is_array($similar)) {
				$suggestioncount += 1;
				$id = gettag($curfejl,"TransID");
				$fn = gettag($curfejl,"Filename");
					$accepted++;
					$data = json_decode(fgc($fn),true);
					$oldacc = $data["Transactions"][$id]['Account'];
					$oldvat = $data["Transactions"][$id]['Func'];
					$data["Transactions"][$id]['AccountSuggestion'] = $similar["Kontoforslag"];
					$data["Transactions"][$id]['FuncSuggestion'] = $similar["Momsforslag"];
					array_push($data["History"],array("Date"=>date("Y-m-d H:m"),"op"=>$op,"Description"=>"Forslag til transaktion $id baseret på historik: $similar[Kontoforslag] ($similar[Momsforslag])"));
					file_put_contents("$tpath/$fn",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
		}
		die("Suggestions handled\n");
	}
	else if ($nargs[0] == "openentries") {
		$match = true;
		while ($match != false) {
			system("mkdir -p $tpath/.openentries");
			$x = getcsv("1970-01-01",$end,$tpath);
			$dk = getdebcred($x);
			$open = getopen($dk);
			$fejl = getfejl($x);
			$match = findmatch($open,$fejl);
			if ($match == false) continue;
			$fnmatch = gettag($match['Open'],'Filename');
			$fnfejl = gettag($match['Fejl'],'Filename');
			echo "$fnmatch vs $fnfejl\n";
			if ($fnmatch == $fnfejl) { touch($match['Ignorefile']);continue;}

			echo $match['Open']['Date'] . "\t" . str_pad($match['Open']['bilag'],19, " ") . "\t" . str_pad(substr(lastacc($match['Open']['Account']),-25),25," ") ."\t" . str_pad($match['Open']['tekst'],25," ") . "\t" . $match['Open']['Amount'] . "\n";
			echo $match['Fejl']['Date'] . "\t" .  str_pad($match['Fejl']['bilag'],19, " ") . "\t" . str_pad(substr(lastacc($match['Fejl']['Account']),-25),25," ") . "\t" . str_pad($match['Fejl']['tekst'],25," ") . "\t" . $match['Fejl']['Amount'] . "\n";
			echo "\nVil du matche disse ? (j/n): ";
			$fd = fopen("PHP://stdin","r");$str = trim(fgets($fd));	fclose($fd);
			if ($str == "j") {
				if (!file_exists($fnfejl) || !file_exists($fnmatch)) { "smth not exist, continuing ... :-)\n";die();}
				$uid = uniqid();
				$tid = gettag($match['Fejl'],"TransID");
				$mtid = gettag($match['Open'],"TransID");
				$filf = json_decode(file_get_contents("$tpath/$fnfejl"),true);
				$mfilf = json_decode(file_get_contents("$tpath/$fnmatch"),true);
				$mfilf['MatchID'] = $uid;
				$filf['MatchID'] = $uid;
				$filf['Transactions'][$tid]['Account'] = $match['Open']["Account"] . ":$uid";
				$filf['Transactions'][$tid]['Func'] = "";
				$filf['History'][] = array('Date'=>date("Y-m-d H:m"),'op'=>exec("whoami"),"Desc"=>"Automatisk udligning af kreditor via openentries");
				file_put_contents("$tpath/$fnfejl",json_encode($filf,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
				echo "saved " . $fnfejl . "\n";
				$mfilf['Transactions'][$mtid]['Account'] = $match['Open']["Account"] . ":$uid";
				$mfilf['Transactions'][$mtid]['Func'] = "";
				$mfilf['History'][] = array('Date'=>date("Y-m-d H:m"),'op'=>exec("whoami"),"Desc"=>"Automatisk udligning af kreditor via openentries");
				file_put_contents("$tpath/$fnmatch",json_encode($mfilf,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
				echo "saved " . $fnmatch. "\n";
				system("touch " . $match['Ignorefile']);
			}
			else{
				system("touch " . $match['Ignorefile']);
				echo "Ignored...\n";
			}
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
			$antal = count(array_unique($lockthesefiles));
			echo set(strtoupper("Bekræftet bogføring af $antal poster ($begin - $end)\n"),"inverse");
			$nextnumber = $orgnextnumber; // global nummercounter skal genstartes fordi vi har kørt data før
			$ledgerdata = getledgerdata($x,true,false,$curbook);
			file_put_contents("$tpath/Mainbook.ledger",$ledgerdata);
			file_put_contents("$tpath/.nextnumber",$nextnumber);
			file_put_contents("$tpath/.nextcbnumber",$nextcbnumber +1);
			foreach (array_unique($lockthesefiles) as $curfile) {
				$data = json_decode(fgc($curfile),true);
				$data['Description'] = "🔒" . $data['Description'];
				$data['Status'] = "Locked";
				$data['History'][] = array("Desc" => 'Bogført','op'=>$op,'tidspunkt'=>date("Y-m-d H:m"));
				file_put_contents("$tpath/$curfile",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
				foreach ($data['Transactions'] as $ct) {
					$str = str_pad($data['Filename'],25," ",STR_PAD_RIGHT) . "\t";
					$str .= str_pad($ct['Account'] . " (" . $ct['Func'] . ")",25," ",STR_PAD_RIGHT) . "\t";
					$str .= str_pad($ct['Amount'],10," ",STR_PAD_LEFT);
					echo set(strtoupper("🔒 $str\n"),"green");
				}
				system("chmod a=r $tpath/$curfile");
			}
		}
		else
			echo set(strtoupper("Afbrudt bogføring\n\n"),"inverse");
	}
	else if ($nargs[0] == "preview") {

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
		if ($undefined_aliascount > 0)fwrite(STDERR,"$undefined_aliascount manglende aliases - skriv 'aliases'\n");
	}
	else {
		entry();
	}
	function entry() {
		global $tpath;
		global $op;
		global $x; // expanded transactions
		$hashkonti = ""; // til at finde kontosammenfald for automatisk tekst gentagelsesforslag
		$f = array();
		$f['Transactions'] = array();
		$bal = -1;
		$i = 0;
			$v = "";
		while (round($bal,2) != 0) {
			if ($bal == -1 ) $bal = 0;
			require_once("/svn/svnroot/Applications/fzf.php");
			$find = false;
			$konti = lookup_acc("",$bal,"entry (bal=$bal)");
			if (trim($konti) == "") die("Afbrudt kontering\n");
			if ($konti == "") die("Afbrudt kontering\n");
			$konti = explode("\n",$konti);
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
				$hashkonti .= $konto;
				$f['Transactions'][$i]['Func'] = $func;
				$f['Transactions'][$i]['Amount'] = $belob;
				$i++;
			}
		}
		$filename = date("Ymd") . uniqid();
		$f['History'] = array(array('Date'=>date("Y-m-d H:m"),'Desc'=>"Manual entry $op"));
		$f['Date'] = askdate();
		$f['Reference'] = askref();
		$f['Description'] = askdesc(md5($hashkonti));
		$f['Filename'] = $filename . "_" . filter_filename($f['Description']) . ".trans";
		file_put_contents("$tpath/$f[Filename]",json_encode($f,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Gemt $tpath/$f[Filename]\n";
		system("php /svn/svnroot/Applications/newl.php b >/dev/null");
	}
	function askamount($konto,$bal) {
		require_once("/svn/svnroot/Applications/math.php");
		$bal = $bal *-1;
		$rv = getstdin("Beløb for $konto ($bal)");
		$rv = str_replace(",",".",$rv); // tillad komma, bliver blot tolket som punktum der er iso locales default decimalseparator
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
		ob_start();
		system("cat /svn/svnroot/Libraries/Kontoplan.txt");
		$x = explode("\n",ob_get_clean());
		foreach ($x as $curline) { if (strlen($curline)) array_push($r,$curline); }
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
	function askdesc($hash) {
		global $tpath;
		if (file_exists("$tpath/.lastdesc_$hash")) {
			$curdesc = file_get_contents("$tpath/.lastdesc_$hash");
		}
		else $curdesc = "";
		if ($curdesc != "")
			$rv = getstdin("Indtast tekst [$curdesc]");
		else
			$rv = getstdin("Indtast tekst");
		file_put_contents("$tpath/.lastdesc_$hash",$rv);
		return $rv;
	}
	function askref() {
		global $tpath;
		if (file_exists("$tpath/.lastref")) {
			$curref= intval(trim(file_get_contents("$tpath/.lastref")));
			if ($curref> 0)  $curref++;
			else  $curref= "" ;
		}
		else $curref= "";
		if ($curref!= "") {
			$rv = getstdin("Indtast reference (. for intet bilag) [$curref]");
		}
		else
			$rv = trim(getstdin("Indtast reference"));
		if ($rv == ".") $rv = "";
		else if ($rv == "") $rv = $curref;
		file_put_contents("$tpath/.lastref",$rv);
		return strval($rv);
	}
	function askdate() {
		global $tpath;
		if (!file_exists("$tpath/.lastdate"))
			$curdate =date("Y-m-d");
		else
			$curdate = file_get_contents("$tpath/.lastdate");
		if (stristr($tpath,"igang")) $curdate = date("Y-m-d");
		$s = getstdin("Indtast dato [$curdate]");
		$retval = ($s == "") ? $curdate : $s;
		file_put_contents("$tpath/.lastdate",$retval);
		return $retval;
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
	function bookphp($file) {
		global $tpath;
		global $op;
		global $ledgerdata;
		ob_start();
		system("cd $tpath;php $file");
		$ld = ob_get_clean();
		$x = explode("\n",$ld);
		$s = "";
		foreach ($x as $curx)  {
			$s .= "; $curx\n";
		}
		$ledgerdata .= $s . "\n; rewrite:\n";
		$ledgerdata .= rewrite($ld);
	}
	function bookbash($file) {
		global $tpath;
		global $op;
		global $ledgerdata;
		ob_start();
		system("cd $tpath;bash $file");
		$ld = ob_get_clean();
		$x = explode("\n",$ld);
		$s = "";
		foreach ($x as $curx) {
			$s .= "; $curx\n";
		}
		$ledgerdata .= $s . "\n; rewrite:\n";
		$ledgerdata .= rewrite($ld);
	}
	function getopening()  {
		$begin = getenv("LEDGER_BEGIN");
		$tpath = getenv("tpath");
		$fn = "$tpath/.Åbning_$begin.ledger";
		if (file_exists($fn)) return file_get_contents($fn); else return "; No opening available $fn";
	}
	function getledgerdata($x,$book = false,$pretty = false,$currentledger = "") {
		global $lockthesefiles;
		$ledgerdata = $currentledger;
		$begin =getenv("LEDGER_BEGIN");
		$end =getenv("LEDGER_BEGIN");
		require_once("/svn/svnroot/Applications/ansi-color.php");
		if ($book == true) echo set(strtoupper("Starter bogføring af poster [ $begin - $end ]\n\n"),"inverse");
		if ($book == true && $ledgerdata == "") $ledgerdata = "\n; Dette er hovedbogen. Hver transaktion bekræfter alle transaktioner forinden med deres samlede md5 checksum - det vil sige hvis du ændrer i denne bog bliver checksummen ugyldig og bogen er manipuleret - efter denne besked starter transaktionerne fra Løbenummer 1 og frem\n\n";
		global $nextnumber;
		foreach ($x as $c) { // for hver transaktion der skal bogføres
			if (strtotime($c['Date'] >= strtotime($end) || strtotime($c['Date']) <= strtotime($begin))) continue; // skip periods we are not in - only book current period
			if (isset($c['Reference'])) $ref = $c['Reference']; else $ref = "";
			if ($book == true) { 
				$hash = md5(trim($ledgerdata)); 
				$ledgerdata .= "$c[Date] ($ref) ☀ $c[Description]\n";
			}
			else {
				$ledgerdata .= "$c[Date] ($ref) ✎ $c[Description]\n";
			}
		$counter = 0;
		foreach ($c['Transactions'] as $ct) {
			array_push($lockthesefiles,$c['Filename']);
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
		$alreadyused = array();
		global $undefined_aliascount;
		global $aliases_warning_displayed;
		$update = trim(getenv("updatealiases"));
		$tpath = getenv("tpath");
		$aliases = json_decode(fgc("$tpath/aliases"),true);
		$filedata = json_decode(fgc("$tpath/$file"),true);
		$d = $filedata['Transactions'][$id]['Account'];
		if ($update == "") {
			$undefined_aliascount++;
			if (!isset($aliases[$d]) && $update == "") {
				$aliases[$d] = "Mangler";
				file_put_contents("$tpath/aliases",json_encode($aliases,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
			else if (isset($aliases[$d]) && $aliases[$d] != "Mangler") {
				$filedata['Transactions'][$id]['Account'] = $aliases[$d];
				file_put_contents("$tpath/$file",json_encode($filedata,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			}
		}
		else {
			echo "its do or die time\n";
			require_once("/svn/svnroot/Applications/proc_open.php");
			$bn = basename("$tpath");
			if ($aliases_warning_displayed == 0) { exec_app("whiptail --msgbox \"Der mangler at blive defineret nye aliases i $bn ($d)\nDu vil nu blive spurgt hvilken konto de enkelte aliases skal henføres til\" 10 80");$aliases_warning_displayed=1;}
			if (!isset($alreadyused[$d])) {
				$x = explode(":",$d);
				if (isset($x[1]))
					$konto = $d;
				else
					$konto = lookup_acc("",0,"aliaset '$d'","");
				$alreadyused[$d] = $konto;
			}
			else
				$konto = $alreadyused[$d];
			//$konto = "Mangler";
			//getkontoplan_allaccounts($tekst = " - aliases $d peger på");
			//$konto = lookup_acc("",0,$tekst = " - aliases $d peger på");
			if ($konto == "") die();
			$aliases[$d] = $konto;
			file_put_contents("$tpath/aliases",json_encode($aliases,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
			$filedata['Transactions'][$id]['Account'] = $konto;
			file_put_contents("$tpath/$file",json_encode($filedata,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		}
	}
	function bookledger($file) {
		global $tpath;
		global $ledgerdata;
		$ledgerdata .= "; ledger file $file\n";
		$filedata = (fgc($tpath."/".$file));
		$x = explode("\n",$filedata);
		$s = "";
		foreach ($x as $curline) {
			$s .= " ; $curline\n";
		}
		$ledgerdata .= "; rewrite: \n" . rewrite($filedata);
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
		if (!is_writable("$tpath/$file")) {
			//fwrite(STDERR,"$file is readonly (booked) - skipping");
			return;
		}
		$newtrans = json_decode(fgc($tpath."/".$file),true);
		if ($newtrans == false) {
			file_put_contents("$tpath/.log",date("Y-m-d H:m") . "Could not json decode $tpath/$file\n",FILE_APPEND);
			FWRITE(STDERR,set("Error: for at se fejl skriv 'fejl'\n","red"));
			sleep(2);
		}
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
		$transactions[] = json_decode(rewrite(json_encode($newtrans)),true);
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
function lastacc($acc) {
	$r = "";
	$s = explode(":",$acc);
	$last = $s[count($s)-1];
	if (strlen($last) > 10)
	$last= "..." . substr($last,-10);
	unset($s[count($s)-1]);
	foreach ($s as $curelement) {
		$r .= substr($curelement,0,1) . ":";
	}
	$r .= $last;
	return $r;
}
function searchfor($searcharray,$trans) {
	$h = $trans['History'];
	unset($trans['History']);
	$txt = json_encode($trans,JSON_UNESCAPED_UNICODE);
	foreach ($searcharray as $cursearch) {
		if (stristr($txt,$cursearch) == false) {
			return -1;
		}
	}
	$trans['History'] = $h;
	return $trans;
}
function displaysearch($res,$s) {
	require_once("/svn/svnroot/Applications/shortacc.php");
	require_once("/svn/svnroot/Applications/sortsearch.php");
	$fzf = "";
	$res = dosorting($res);
	foreach ($res as $curres) {
		$fzf .= str_pad($curres['Date'],15," ",STR_PAD_RIGHT) . "\t";
		$fzf .= str_pad(substr($curres['Reference'],0,15),15," ",STR_PAD_RIGHT) . "\t";
		$fzf .= str_pad(substr($curres['Description'],0,20),20," ",STR_PAD_RIGHT) . "\t";
		$short = shortacc($curres['Transactions'][0]['Account']);
		$fzf .= str_pad($short,8," ",STR_PAD_LEFT) . "\t";
		$amount = number_format($curres['Transactions'][0]['Amount'],2,",",".");
		$fzf .= str_pad($amount,15," ",STR_PAD_LEFT) . "\t";
		$short = shortacc($curres['Transactions'][1]['Account']);
		$fzf .= str_pad($short,8," ",STR_PAD_LEFT) . "\t";
		if (isset($curres['Transactions'][2])) {
			$multi = "𝤒";
		}
		else
			$multi = "᛫";

		$fzf .= str_pad(getops($curres),5," ",STR_PAD_LEFT) . "\t";

		$fzf .= str_pad($multi,2," ",STR_PAD_LEFT) . "\t$curres[Filename]";
		$fzf .= "\n";
	}
	$sogning = "";
	foreach ($s as $curs) $sogning .= $curs." ";
	$valg = fzf($fzf,"Søgning $sogning","",true);
}
function getops($trans) {
	$ops = array();
	foreach ($trans['History'] as $curhist) {
		$op = "";
		if (isset($curhist['op']))
			$op = $curhist['op'];
		else if (isset($curhist['updatedby']))
			$op = $curhist['updatedby'];
		if ($op != "") {
			if (!in_array($op,$ops)) array_push($ops,$op);
		}
	}
	$r = "";
	foreach ($ops as $curop) {
		$r .= $curop . " ";
	}
	return trim($r);
}
?>
