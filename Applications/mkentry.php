<?php
date_default_timezone_set('Europe/Copenhagen');
	$op = exec("whoami");
	function filebydate($a,$b) {
		return (($a['Date']) > ($b['Date']));
	}
	ob_start();
	system("gum input --header 'Søg efter'");
	$search = trim(ob_get_clean());
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
		$data = json_decode(file_get_contents($file),true);
		if (!isset($data["Description"])) $data["Description"] = "";
		if (!isset($data["Transactions"][0]) || !isset($data["Transactions"][0]['Account'])) continue;
		if (!isset($data["Transactions"][1]) || !isset($data["Transactions"][1]['Account'])) continue;
		if (!stristr(json_encode($data),$search)) continue;
		$d['data'] = $data;
		$farray[$i] = $d;
		$farray[$i]['Fn'] = basename($file);
		$farray[$i]['Dn'] = dirname($file);
		$farray[$i]['Fullfn'] = ($file);
		$shortdesc = substr($data['Description'],0,15);
		$fzf .= "$i\t$data[Date]\t$shortdesc\t" . $data['Transactions'][0]['Account'] . "\t";
		$pamount = number_format($data['Transactions'][0]['Amount'],2,".",",");
		$fzf .= $pamount. "\t";
		$dir = basename(dirname($file));
		$fzf .= $data['Transactions'][1]['Account'] . "\t$dir\n";
		$i++;
	}
	require_once("/svn/svnroot/Applications/fzf.php");
	$farray[""] = null;
	$valg = explode(" ",fzf($fzf,"Vælg transaktion at duplikere","--tac",true))[0];
	if ($valg == "") die("Intet valgt\n");
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
	$data['Date'] = date("Y-m-d");
	unset($data['Fn']);unset($data['Fullfn']);
	$data['History'] = array(array('Date'=>date("Y-m-d H:i"),'updatedby'=>exec("whoami"),"Description"=>"Duplikeret transaktion"));
	require_once("/svn/svnroot/Applications/proc_open.php");
	$data["UID"] = uniqid();
	if (!isset($data['Comment'])) $data['Comment'] = "";
	$data["Description"] = "⏲ - " . date("H:i");
	file_put_contents($nfn,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	exec_app("vi \"$nfn\"");
	$cmd = ("find /data/regnskaber ~/regnskaber/ -mtime -90 -printf \"%T@|||||%p\\n\" |grep .trans$ > /home/$op/tmp/mkentry.php.list");
	system("$cmd");
?>
