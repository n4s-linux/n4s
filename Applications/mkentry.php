<?php
	// refresh(); // uncomment this if having problem with missing cache
	$op = exec("whoami");
	require_once("/svn/svnroot/Applications/proc_open.php");
	require_once("/svn/svnroot/Applications/fzf.php");
	require_once("/svn/svnroot/Applications/ask.php");
	date_default_timezone_set('Europe/Copenhagen');
	function filebydate($a,$b) {
		return (($a['Date']) > ($b['Date']));
	}

	if (getenv("adjustbalance") != "1") {
		ob_start();
		system("gum input --header 'Søg efter'");
		$search = trim(ob_get_clean());
		if ($search == "") die();
	$filez = explode("\n",trim(file_get_contents("/home/$op/tmp/mkentry.php.list")));
	foreach ($filez as $curfile) {
		$sortfilez[] = array('Date' => explode("|||||",$curfile)[0],		'Fn' => explode("|||||",$curfile)[1]);
	}
	usort($sortfilez,"filebydate");
	$filez = array();
	foreach ($sortfilez as $curfile){ $filez[] = $curfile["Fn"];}
	$farray = array();
	$i = 0;
	$fzf = "";
	foreach ($filez as $key => $file) {
		$d['fullfn'] = $file;
		$d['fn'] = basename($file);
		if (!file_exists($file)) 
			continue;
		$data = json_decode(file_get_contents($file),true);
		$data["UID"] = substr(uniqid(),0,8);
		if (!isset($data["Description"])) $data["Description"] = "";
		if (!isset($data["Transactions"][0]) || !isset($data["Transactions"][0]['Account'])) continue;
		if (!isset($data["Transactions"][1]) || !isset($data["Transactions"][1]['Account'])) continue;
		if (!stristr(json_encode($data,JSON_UNESCAPED_UNICODE),$search)) continue;
		$d['data'] = $data;
		$farray[$i] = $d;
		$farray[$i]['Fn'] = basename($file);
		$farray[$i]['Dn'] = dirname($file);
		$farray[$i]['Fullfn'] = ($file);
		$shortdesc = substr($data['Description'],0,15);
		$fzf .= "$i\t$data[Date]\t$shortdesc\t💵" . $data['Transactions'][0]['Account'] . "💵\t";
		$pamount = number_format($data['Transactions'][0]['Amount'],2,".",",");
		$fzf .= $pamount. "\t";
		$dir = basename(dirname($file));
		$fzf .= $data['Transactions'][1]['Account'] . "\t$dir\n";
		$i++;
	}
	$farray[""] = null;
	$valg = fzf($fzf,"Vælg transaktion at duplikere","--tac",true);
	$nummer = explode(" ",$valg)[0];
	if ($valg == "") {
		die("Intet valgt\n");
	}
	}
	if (getenv("adjustbalance") == "1") {
		$fzf = "";
		$balz = getbalz();
		foreach ($balz as $curacc => $curbal) {
			$curbal = number_format($curbal,2,".",",");
			$curbal = str_pad($curbal,20," ",STR_PAD_LEFT);
			$fzf .= "$curacc\t💵$curbal\n";
		}
		$konto = fzf($fzf,"Chose balance to adjust - used TAB to alter multiple accounts","--ansi --multi",true);
		$konti = explode("\n",trim($konto));
		if (empty($konti[0])) die("Aborted no account selected to adjust balance for\n");
		foreach ($konti as $konto) {
		$konto = trim(explode("💵",$konto)[0]);
		$tpath = getenv("tpath");
		$bal = $balz[$konto];
		echo "Old balance for $konto: $bal\n";
		echo "New balance for $konto: ";
		$new = intval(str_replace(",",".",trim(fgets(STDIN))));
		if ($new == 0) {
			exec_app("whiptail --msgbox \"Assuming 0 balance for $konto -  if You disagree, please abort mission after this message (Ctrl-C)\" 8 80");
			$new = 0;
		}
		$diff = $new -$bal;
		echo "Forskel $diff\n";
		$data = array();
		$end = date("Y-m-d",strtotime(getenv("LEDGER_END") . " -1 day"));
		$begin = date("Y-m-d",strtotime(getenv("LEDGER_BEGIN")));
		$today=date("Y-m-d");
		$dato=fzf("$end\n$today\n$begin","Chose date for adjustment [$konto] $new - $bal = $diff");
		if ($dato == "") die("Cancelled adjustment\n");
		$data["Date"] = $dato;
		$diff = number_format($diff,2,".","");
		$data["UID"] = substr(uniqid(),0,8);
		$data["Transactions"][0] = array("Account"=>$konto,"Amount"=>$diff,"Func"=>"");
		require_once("/svn/svnroot/Applications/lookup_account.php");
		$mk = lookup_acc(null,$diff,"Where to move $diff [$konto] balance to?");
		if ($mk == "") die("Cancelled adjustment\n");
		require_once("/svn/svnroot/Applications/get_func.php");
		$func = get_func($konto,$diff,$diff);
		$data["Transactions"][1] = array("Account"=>$mk,"Amount"=>-$diff,"Func"=>"$func");
		echo "\nWould You like to add a comment: ";
		$comment= trim(fgets(STDIN));
		$data["Description"] = "⚖ Adjustment [$konto - $bal ⇝ $new] $comment";
		$data['History'] = array(array('Date'=>date("Y-m-d H:i"),'updatedby'=>exec("whoami"),"Description"=>"Justeret balance '$konto' fra $bal til $new"));
		$new = 1;
		$dir = $tpath;
		$nfn = "";
		$date = date("Y-m-d");
		while (true) {
			$nfn = $dir. "/adj_$date" . "_". $new .".trans";
			if (!file_exists($nfn)) break;
			$new++;
		}
		$data['Filename'] = basename($nfn);
		$data["Comment"] = "";
		$data["UID"] = uniqid();
		$data["Reference"] = "⚖️ ADJ [" . askref() . "]";
		file_put_contents("$nfn",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
		echo "Saved $nfn\n";
		}
		die();
	}

	$valg = $nummer;
	$new = 1;
	$data = $farray[$valg];
	$dir = $data["Dn"];
	$nfn = "";
	while (true) {
		$nfn = $dir. "/dup_" . $new .".trans";
		if (!file_exists($nfn)) break;
		$new++;
	}

	$ffn = $data['Fullfn'];
	$data = $data['data'];
	$ofn = (isset($data['OrgFilename'])) ? $data['OrgFilename'] : $data['Filename'];
	$data['OrgFilename'] = $ofn;
	$data['Filename'] = basename($nfn);
	unset($data["Status"]);
	$data['Date'] = date("Y-m-d");
	unset($data['Fn']);unset($data['Fullfn']);
	$data['History'] = array(array('Date'=>date("Y-m-d H:i"),'updatedby'=>exec("whoami"),"Description"=>"Duplikeret transaktion"));
	$data["UID"] = uniqid();
	if (!isset($data['Comment'])) $data['Comment'] = "";
	$data["Description"] = "⏲ - " . date("H:i");
	file_put_contents($nfn,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	exec_app("vi \"$nfn\"");
	refresh();
function refresh()  {
	global $op;
	$cmd = ("cmatrix&(find /data/regnskaber ~/regnskaber/ -name \\*.trans -mtime -180 -printf \"%T@|||||%p\\n\" |grep .trans$ > /home/$op/tmp/mkentry.php.list;killall cmatrix)"); //run cache for next time
	file_put_contents("/home/$op/tmp/mkentry.cmd",$cmd);
	exec_app("$cmd");
}
function getbal($konto,$tpath) {
	$tomorrow = date("Y-m-d",strtotime("tomorrow"));
	echo "Calcumulating balance for $konto...\n";
	ob_start();
	system("tpath=\"$tpath\" LEDGER_BEGIN=1970-01 LEDGER_END=$tomorrow LEDGER_DEPTH=99 color=none noend=1 php /svn/svnroot/Applications/newl.php csv");
	$sum =0;
	foreach (explode("\n",trim(ob_get_clean())) as $curline) {
		$csv = str_getcsv($curline);
		if ($csv[3] == $konto) $sum += $csv[5];
	}
	return $sum;
}
function getbalz() {
ob_start();
system("color=none php /svn/svnroot/Applications/newl.php csv");
$data = ob_get_clean();
$balz = array();
$x = explode("\n",trim($data));
foreach ($x as $curline) {
	$csv = str_getcsv($curline);
	$acc = $csv[3];
	$amount = $csv[5];
	if (!isset($balz[$acc])) $balz[$acc] = 0;
	$balz[$acc] += $amount;
}
ksort($balz);
return $balz;
}
?>
