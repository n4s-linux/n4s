<?php
$tablequery_t = false;
require_once("edit_voucher.php");
require_once("balance_form.php");
function process_html($h) {
	usort($h,"sort_html_array");
	$retval = "";
	foreach ($h as $hc) {
		$retval .= $hc['text'] . "\n";
	}
	return $retval;
}
function sort_html_array($a,$b) {
	return ($a['id'] > $b['id']);
}
function http_edit() {
	global $data;
	save(true,"/svn/svnroot/tmp/kladde.json");
	echo "Indstil din browser til http://127.0.0.1:8000/\n";
	exec_app("php -S 0.0.0.0:8000 /svn/svnroot/Applications/jl/kvikkladde.php >/dev/null");
	$data = json_decode(file_get_contents("/svn/svnroot/tmp/kladde2.json"),true)['data'];
	validate();
	save(true);
}
function add_reconciliation() {
	global $afstemning;
	global $end;
	$k = console_input("Intast kontonummer");
	$afstemning[$k][$end]['saldo'] = console_input("Indtast saldo pr. $end");
	$afstemning[$k][$end]['kommentar'] = console_input("Indtast datakilde / kommentar");
	save(true);
}
function check_reconciliation($k,$saldo,$end) {
	global $afstemning;
	$k = intval($k);
	if (isset($afstemning[$k]) && isset($afstemning[$k][$end]) && intval($saldo) == intval($afstemning[$k][$end]['saldo'])) {
		return "☑";
	}
	else
		return "☐";
}
function bykey($a,$b) {
	echo "$a $b \n";
}
function danish_number($s,$padding = true) {
	$s = floatval($s);
	$retval = (string)number_format($s,2,',','.');
	if ($padding) {
		$retval = str_pad($retval,12," ",STR_PAD_LEFT);
	}
	return $retval;
}
function listcsv() {
	global $data;
	$file = array();
	$i = 0;
	if (!$handle = opendir("."))
		die("Kan ikke åbne mappen\n");
	while (false !== ($entry = readdir($handle))) {
		if (endsWith($entry,".csv")) {
			$file[$i++] = $entry;
		}
	}
	print_r($file);
	$filnummer = console_input("Vælg filnummer");
	console_input("Filen vises nu i vim... du kan slette linier der ikke skal med - bagefter bliver du spurgt om adskilletegn");
	exec_app("vim '$file[$filnummer]'");
	echo "VIGTIGT PROGRAM FORVENTER UTF8-KODET FIL\n";
	$skilletegn = console_input("Indtast skilletegn (kolon, semikolon eller andet)");
	require_once("/svn/svnroot/Applications/oledger/csv_to_array.php");
	$csv_file = csv_to_array($file[$filnummer],$skilletegn);
	$i = 0;
	$transactions = null;
	foreach ($csv_file as $banktrans) {
		if ($i == 0)
			$header = $banktrans;
		else if ($i == 1)
			$transactions = $banktrans;
		else
			die("Ugyldig csv fil\n");
		$i++;
	}
	if ($transactions == null)
		die("Ugyldig csv fil...\n");
	$obligatorisk = array("Dato","Tekst","Beløb");
	$modkonto = console_input("Indtast bankkontonummer fra kontoplan");
	$i = 0;
	foreach ($transactions as $banktrans) {
		foreach ($obligatorisk as $obl) {
			if (!isset($banktrans[$obl])) {
				echo "Fejl: Bankfil indeholder ikke kolonnen $obl eller er ikke UTF-8\n";
				return;
			}
		}
		foreach ($banktrans as $col => $val) {
			if (strlen($col) < 2) {
				unset($banktrans[$col]);
				continue;
			}
			if (!in_array($col,$obligatorisk)) {
				$banktrans['csv_'.$col] = $banktrans[$col];
				unset($banktrans[$col]);
			}
		}
		$banktrans['timestamp'] = date("Y-m-d");
		$banktrans['source'] = $file[$filnummer];
		$banktrans['Bilagsnr'] = "9999";
		$banktrans['Konto'] = 9901;
		$banktrans['F1'] = "";
		$banktrans['Modkonto'] = $modkonto;
		$banktrans['F2'] = "";
		$banktrans['Beløb'] = -1 *floatval((str_replace(",",".",$banktrans['Beløb'])));
		$banktrans['Dato'] = date("Y-m-d",strtotime($banktrans['Dato']));
		if ($banktrans != null)
			array_push($data,$banktrans);
		$i++;
	}
	echo "Indsat $i posteringer i journal\n";
	save();
	validate();
	return true;

}
function endsWith( $str, $sub ) {
    return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}
function getautoupdate() {
	$options = array('Dato','Bilagsnr','Tekst','Konto','F1','Modkonto','F2','Dimension');
	echo "Tryk enter for at efterlade værdi som den er, eller indtast værdi for auto-update\n";
	foreach ($options as $option) {
		$txt = console_input("Ny værdi for $option");
		if (strlen($txt))
			$retval[$option] = $txt;
	}
	if (isset($retval))
		return $retval;
	else
		return array();
}
function search_edit($limit,$edit,$auto) {
	global $filter;
	global $data;
	global $begin;
	global $end;
	$s = $filter['tekst'];
	$save = 0;
	if ($limit === "ask") $limit = intval(console_input("MAX transaktioner (default 10)"));
	if ($edit === "ask") $edit = (console_input("Ønsker du at redigere i søgningens resultater? (j/n)") == "j");
	if ($auto === "ask") $auto = (console_input("Ønsker du batch-opdatering af de fundne transaktioner (j/n)") == "j");
	if ($auto == 1) {
		$auto_criteria = getautoupdate();
	}
	else
		$auto_criteria = false;
	$show = array();
	if ($limit < 1)
		$limit = 10;
	$newdata = array();
	foreach ($data as $trans) {
		array_push($newdata,$trans);
		if (count($trans) == 0)
			continue;
		if ($save >= $limit)
			continue;
		if (strtotime($trans['Dato']) < strtotime($begin) || strtotime($trans['Dato']) > strtotime($end))
			continue;
		if (strlen($filter['konto'])&&($trans['Konto'] != $filter['konto']&& $trans['Modkonto'] != $filter['konto']))
			continue;
		if (strlen($filter['bilag'])&&($trans['Bilagsnr'] != $filter['bilag']))
			continue;
		if (strlen($filter['dimension'] )&& $trans['Dimension'] != $filter['dimension'])
			continue;
		if (strlen($s) && !stristr($trans['Tekst'],$s))
			continue;
		if (floatval($trans['Beløb']) == floatval($filter['beløb']) || floatval($trans['Beløb']) == -floatval($filter['beløb'])) {
			if ($edit)
				{ array_pop($newdata);array_push($newdata,edit_trans($trans,$auto,$auto_criteria,1)); }
			else
				array_push($show,$trans);
			$save++;
			continue;
		}
		if (strlen($s) && stristr($trans['Tekst'],$s)) {
			if ($edit) {
				{ array_pop($newdata);array_push($newdata,edit_trans($trans,$auto,$auto_criteria,1)); }
			}
			else
				array_push($show,$trans);
			$save++;
			continue;
		}
		if ($filter['konto']== $trans['Konto'] || $filter['konto']== $trans['Modkonto']) {
			if ($edit) {
				{ array_pop($newdata);array_push($newdata,edit_trans($trans,$auto,$auto_criteria,1)); }
			}
			else
				array_push($show,$trans);
			$save++;
			continue;
		}
		if ($filter['bilag'] == $trans['Bilagsnr']) {
			if ($edit)
				{ array_pop($newdata);array_push($newdata,edit_trans($trans,$auto,$auto_criteria,1)); }
			else
				array_push($show,$trans);
			$save++;
			continue;
		}
		else if (isset($trans['dimension']) && $filter['dimension'] == $trans['Dimension']) {
			if ($edit)
				{ array_pop($newdata);array_push($newdata,edit_trans($trans,$auto,$auto_criteria,1)); }
			else
				array_push($show,$trans);
			$save++;
			continue;

		}
	}
	$data = $newdata;
	if (!$edit) {
		$t = "Dato|Bilag|Tekst|Konto|F1|Modkonto|F2|Dimension|Beløb\n";
		foreach ($show as $trans) {
			$t .= "$trans[Dato] | $trans[Bilagsnr] | $trans[Tekst] | $trans[Konto] | $trans[F1] | $trans[Modkonto] | $trans[F2] | $trans[Dimension] | $trans[Beløb]\n";
		}
		file_put_contents("/svn/svnroot/tmp/column.tsv",$t);
		exec_app("cat /svn/svnroot/tmp/column.tsv|column -t -s\"|\">/svn/svnroot/tmp/balance.txt;cat /svn/svnroot/tmp/balance.txt;rm /svn/svnroot/tmp/column.tsv;rm /svn/svnroot/tmp/balance.txt");
		
		
	}
	else if ($save >0) {
		save(true);
		validate();
	}
}
function find_trans($trans) {
	global $data;
	$newd = array();
	$found = null;
	foreach ($data as $d) {
		if (isset($trans["id"]) && isset($d["id"]) && $d["id"] == $trans["id"]) {
			$z = edit_trans($d);
			array_push($newd,$z);
			$found = $z;
		}
		else
			array_push($newd,$d);
	}
	$data = $newd;
	return $found;
	
}
function edit_trans($trans,$auto = false,$auto_criteria = false,$source = false) {
	global $kontoplan;
	$a = true;
	$b = true;
	if ($auto == false) 
		$trans = change_trans($trans,"Rediger postering","Redigering");
	else
		$trans = change_trans_auto($trans,$auto_criteria);
	return $trans;
}
function save($silent = false,$copy = false,$update_kontoplan = true,$source = "save") {
	global $data;
	global $filnavn;
	global $kontoplan;
	global $afstemning;
	global $indstillinger;
	ksort($kontoplan['kontoplan']);
	ksort($kontoplan['Sumfra']);
	$d = array('data'=>$data,'kontoplan'=>$kontoplan,'afstemning'=>$afstemning,'indstillinger'=>$indstillinger);
	$data = save_transactions($data,$silent,$source);
	if ($update_kontoplan == true) $kontoplan = save_kontoplan($kontoplan,$silent);
	//$bytes = file_put_contents($filnavn,json_encode($d,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	if ($copy != false)
		$bytes = file_put_contents($copy,json_encode($d,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	if (!$silent)
		echo "Opdateret db ok\n";
}
function short_hash($str) {
global $kundenr;
return $kundenr;
}
function save_kontoplan($kontoplan,$silent = false) {
	global $filnavn_short;
	global $kontoplan_id;
	global $sumfra_id;
	$prefix = short_hash($filnavn_short);
	$table = $prefix . "_h";
	mysqli_query($GLOBALS["___mysqli_ston"], "create table if not exists $table (id int primary key auto_increment,konto text, kontotype text, sumfra text,tekst text);") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
	foreach ($kontoplan['Sumfra'] as $kontonr => $start) {
		if (!isset($sumfra_id[$kontonr])) { $cmd = "insert"; $id = 'null'; } else { $id = $sumfra_id[$kontonr];$cmd = "replace";}
		$q = "$cmd into $table (id,konto,kontotype,sumfra) values ('$id',$kontonr,'SumFra',$start);";
		mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("lol2\n");
		if (!isset($sumfra_id[$kontonr]))
			$sumfra_id[$kontonr]= ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
	}
	foreach ($kontoplan['kontoplan'] as $kontonr => $text) {
		if (!isset($kontoplan_id[$kontonr])) { $cmd = "insert"; $id = 'null'; } else { $id = $kontoplan_id[$kontonr];$cmd = "replace";}
		$text = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $text);
		$q = "$cmd into $table (id,konto,kontotype,tekst) values ('$id',$kontonr,'Finans','$text');";
		mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("lol\n");
		if (!isset($kontoplan_id[$kontonr]))
			$kontoplan_id[$kontonr] = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
	}

	return $kontoplan;	
}
function load_kontoplan() {
	global $filnavn_short;
	global $kontoplan_id;
	global $kontoplan;
	$prefix = short_hash($filnavn_short);
	$retval = array('kontoplan'=>array(),'SumFra'=>array());
	$q = "select * from $prefix" . "_h;";
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $q);
	if ($res == false) {
		switch (mysqli_error($GLOBALS["___mysqli_ston"])) {
			case "Table 'jl.$prefix" . "_h' doesn't exist":
				$skabeloner = array('Momsvirksomhed','Forening');
				echo "Ny virksomhed, vælg default kontoplan\n";
				print_r($skabeloner);
				$filnavn = $skabeloner[console_input("Indtast skabelon nr")];
				$retval = json_decode(file_get_contents("/svn/svnroot/Applications/jl/$filnavn.json"),true);
				$kontoplan = save_kontoplan($retval);
				return $retval;
				break;
			default:
				die("unknown error\n");	
				break;
		}
	}
	else {
		$retval = array('kontoplan'=>array(),'Sumfra'=>array());
		while ($row = mysqli_fetch_assoc($res )) {
			if ($row['kontotype'] == 'SumFra')
				$retval['Sumfra'][$row['konto']] = $row['sumfra'];
			else if ($row['kontotype'] == 'Finans')
				$retval['kontoplan'][$row['konto']] = $row['tekst'];
			else
				die("Ukendt kontotype\n");
			$kontoplan_id[$row['konto']] = $row['id'];
		}
	}	
	return $retval;
}
function sync($askorder = true) {
	global $data;
	global $kontoplan;
	save_transactions($data,true,"sync");
	save_kontoplan($kontoplan,true);
	if ($askorder)
		$order = console_input("Ønskes specifik sortering, skriv da sql");
	else
		$order="";
	$data = load_transactions($order);
	$kontoplan = load_kontoplan();
}
function load_transactions($order = "") {
	global $filnavn_short;
	$retval = array();
	$prefix = short_hash($filnavn_short);
	$q = "select * from $prefix" . "_t $order;";
	$res = mysqli_query($GLOBALS["___mysqli_ston"], $q);
	if ($res == false) {
		switch (mysqli_error($GLOBALS["___mysqli_ston"])) {
			case "Table 'jl.$prefix" . "_t' doesn't exist":
				$retval = array();
			break;
			default: 
				echo "fejl lol:\n";
				print_r(mysqli_error($GLOBALS["___mysqli_ston"]));die();
			break;
		}
	}
	else {
		while ($row = mysqli_fetch_assoc($res )) {
			array_push($retval,$row);
		}
	}
	return $retval;
}
function save_transactions($data,$silent = false,$source = "none") {
	$retval = array();
	global $tablequery_t;
	global $filnavn_short;
	$prefix = short_hash($filnavn_short);
	$keys = array('Dato','Bilagsnr','Tekst','Konto','F1','Modkonto','F2','Dimension','Beløb');
	$keyz = $keys;
	array_push($keyz,'id');
	$q = "create table if not exists $prefix" . "_t (id int primary key auto_increment";
	foreach ($keys as $key) {
		$q .= ", $key text";
	}
	$q .= ");";
	if ($tablequery_t == false) {
		mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("fejl8: " . mysqli_error($GLOBALS["___mysqli_ston"]));
		$tablequery_t = true;
	}
	$upd = 0;
	foreach ($data as $trans) {
		$update = 0;
		if (isset($trans['id']))
			$q = "replace into $prefix" . "_t (";
		else {
			$q = "insert into $prefix" . "_t (";
		}
		$ks = "";
		$i = 0;
		if (isset($trans['update']) && $trans['update'] == 1) { $update = 1;unset($trans['update']);}
		foreach ($trans as $key => $value) {
			if (!in_array($key,$keyz) )
				continue;
			if ($i == 0) {
				$ks = $key;
				$values =  "'" . mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value) . "'";
				$i++;
			}	
			else {
				$ks .= "," . $key;
				$values .= ",'" . mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $value) . "'";	
			}
		}
		$q .= "$ks) values ($values);";
		if ($update == 1 || !isset($trans['id'])) {
			mysqli_query($GLOBALS["___mysqli_ston"], $q) or die("Fejl9: " . mysqli_error($GLOBALS["___mysqli_ston"]));
			$id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);$trans['id'] = $id;
			$upd++;
		}
		unset($trans['hash']);
		$trans["hash"] = md5(serialize($trans));
		
		array_push($retval,$trans);
	}
	if ($silent == false)
		echo "Opdateret $upd transaktioner i db ($source)\n";
	return $retval;
}
function recalc_trans($konto,$newdata) {
	global $data;
	$r = balance("kk",$konto,null,true,$data);
	return $r;
}
function balfunc() {
	beginning:
	global $filter;
	global $begin;
	global $end;
	global $data;
	global $kontoplan;
	$kontoplan = ((array)$kontoplan);
	$kp = (array)$kontoplan['kontoplan'];
	$sumfra = $kontoplan['Sumfra'];
	global $data_fi;
	global $data_pri;
	$data_pri = array();
	$data_fi = array_filter($data,"filter_period");
	$offset = 0;
	$balance = array();
	$primo = split_transactions($data_pri);
	$periode = split_transactions($data_fi);
	foreach ($primo as &$p) 
		$p = func($p,$primo);
	foreach ($periode as &$p)
		$p = func($p,$periode);
	$balance = array();
	$dimensionskonti = array();
	foreach ($primo as $trans) {
		if (!isset($balance[$trans['Konto']]['Primo']))
			$balance[$trans['Konto']]['Primo']=0;
		if (!isset($balance[$trans['Konto']]['Saldo']))
			$balance[$trans['Konto']]['Saldo']=0;
		$balance[$trans['Konto']]['Primo'] += $trans['Beløb'];
		$balance[$trans['Konto']]['Saldo'] += $trans['Beløb'];
		if (isset($trans['Dimension']) && strlen($trans['Dimension']))
			array_push($dimensionskonti,$trans['Konto']);
	}
	foreach ($periode as $trans2) {
		if (isset($trans2['Dimension']) && strlen($trans2['Dimension']))
			array_push($dimensionskonti,$trans2['Konto']);
}
		$hs = array();
		foreach ($periode as $trans) {
			if (!isset($balance[$trans['Konto']]['Periode']))
				$balance[$trans['Konto']]['Periode'] =0;
			if (!isset($balance[$trans['Konto']]['Saldo']))
				$balance[$trans['Konto']]['Saldo'] = 0;
			if (!isset($balance[$trans['Konto']]['Primo']))
				$balance[$trans['Konto']]['Primo'] = 0;
			$balance[$trans['Konto']]['Periode'] += floatval($trans['Beløb']);
			$balance[$trans['Konto']]['Saldo'] += floatval($trans['Beløb']);
		}
		$t = "Kontonr=\033[1;4mKontonavn=Primo=Bevægelse=Ultimo=A=D\033[0m\n";
		$css = file_get_contents("/svn/svnroot/Applications/xmlvoucher/design/css/balance.css");
		$h = "<style>$css</style><b>Balance for perioden $begin til $end</b><br><br><table><tr><td>Kontonr</td><td>Kontonavn</td><td>Primo</td><td>Bev&aelig;gelse</td><td>Ultimo</td></tr>";
		array_push($hs,array('id'=>0,'text'=>$h));
		$sum = array();
		foreach ($balance as $kontonr => $saldi) {
			foreach ($sumfra as $sumnr => $fra) {
				if ($kontonr >= $fra && $kontonr < $sumnr) {
					if (!isset($sum[$sumnr]))
						$sum[$sumnr] = array('Primo'=>0,'Periode'=>0,'Saldo'=>0);
					$sum[$sumnr]['Primo'] += floatval($saldi['Primo']);
					if (isset($saldi['Periode']))
						$sum[$sumnr]['Periode'] += floatval($saldi['Periode']);
					$sum[$sumnr]['Saldo'] += floatval($saldi['Saldo']);
						
				}
			}
		}
$retval = array('bal'=>$balance,'sum'=>$sum);
return $retval;
}
function kontokort($konto,$idx = 0) {
	beginning:
	global $filter;
	global $begin;
	global $end;
	global $data;
	global $kontoplan;
	$kontoplan = ((array)$kontoplan);
	$kp = (array)$kontoplan['kontoplan'];
	$sumfra = $kontoplan['Sumfra'];
	global $data_pri;
	global $data_fi;
	$data_fi = array_filter($data,"filter_period");
	$offset = 0;
	$balance = array();
	$primo = split_transactions($data_pri);
	$periode = split_transactions($data_fi);
	foreach ($primo as &$p) 
		$p = func($p,$primo);
	foreach ($periode as &$p)
		$p = func($p,$periode);
	file_put_contents("/svn/svnroot/tmp/func.json",json_encode($periode,JSON_PRETTY_PRINT));
	$balance = array();
	$dimensionskonti = array();
	foreach ($primo as $trans) {
		if (!isset($balance[$trans['Konto']]['Primo']))
			$balance[$trans['Konto']]['Primo']=0;
		if (!isset($balance[$trans['Konto']]['Saldo']))
			$balance[$trans['Konto']]['Saldo']=0;
		$balance[$trans['Konto']]['Primo'] += $trans['Beløb'];
		$balance[$trans['Konto']]['Saldo'] += $trans['Beløb'];
		if (isset($trans['Dimension']) && strlen($trans['Dimension']))
			array_push($dimensionskonti,$trans['Konto']);
	}
	foreach ($periode as $trans2) {
		if (isset($trans2['Dimension']) && strlen($trans2['Dimension']))
			array_push($dimensionskonti,$trans2['Konto']);
	}
		$k = $konto;
		$s = 0;
		if (isset($balance[$k]) && isset($balance[$k]['Primo'])) {
			$t = "Dato|Bilag|Tekst|Beløb|Saldo\n";
			$pb = ($balance[$k]['Primo']);
			$t .= "$begin|SYS|Overført tidl. periode|$pb| |$pb\n";
			$s += $pb;
		}
		$postings = array();
file_put_contents("/svn/svnroot/tmp/func2.json",json_encode($periode,JSON_PRETTY_PRINT));
		foreach ($periode as $pp) {
			if ($pp["Konto"] == $k)
				array_push($postings,$pp);
		}	
file_put_contents("/svn/svnroot/tmp/func.json",json_encode($postings,JSON_PRETTY_PRINT));

			kontokort_form($k,$pb/2,$postings,$idx);

}
function balance($rep = "bal",$konto = null,$idx = null,$recalc_postings = false,$newdata = null) {
	file_put_contents("/svn/svnroot/tmp/loggen","$rep for $konto\n",FILE_APPEND);
	beginning:
	global $filter;
	global $begin;
	global $end;
	global $data;
	if ($newdata != null)
		$data = $newdata;
	global $kontoplan;
	$kontoplan = ((array)$kontoplan);
	$kp = (array)$kontoplan['kontoplan'];
	$sumfra = $kontoplan['Sumfra'];
	global $data_pri;
	global $data_fi;
	$offset = 0;
	$balance = array();
	$primo = split_transactions($data_pri);
	$periode = split_transactions($data_fi);
	foreach ($primo as &$p) 
		$p = func($p,$primo);
	foreach ($periode as &$p)
		$p = func($p,$periode);
	$balance = array();
	$dimensionskonti = array();
	foreach ($primo as $trans) {
		if (!isset($balance[$trans['Konto']]['Primo']))
			$balance[$trans['Konto']]['Primo']=0;
		if (!isset($balance[$trans['Konto']]['Saldo']))
			$balance[$trans['Konto']]['Saldo']=0;
		$balance[$trans['Konto']]['Primo'] += $trans['Beløb'];
		$balance[$trans['Konto']]['Saldo'] += $trans['Beløb'];
		if (isset($trans['Dimension']) && strlen($trans['Dimension']))
			array_push($dimensionskonti,$trans['Konto']);
	}
	foreach ($periode as $trans2) {
		if (isset($trans2['Dimension']) && strlen($trans2['Dimension']))
			array_push($dimensionskonti,$trans2['Konto']);
}
	if ($rep == "bal") {
		$hs = array();
		foreach ($periode as $trans) {
			if (!isset($balance[$trans['Konto']]['Periode']))
				$balance[$trans['Konto']]['Periode'] =0;
			if (!isset($balance[$trans['Konto']]['Saldo']))
				$balance[$trans['Konto']]['Saldo'] = 0;
			if (!isset($balance[$trans['Konto']]['Primo']))
				$balance[$trans['Konto']]['Primo'] = 0;
			$balance[$trans['Konto']]['Periode'] += floatval($trans['Beløb']);
			$balance[$trans['Konto']]['Saldo'] += floatval($trans['Beløb']);
		}
		$t = "Kontonr=\033[1;4mKontonavn=Primo=Bevægelse=Ultimo=A=D\033[0m\n";
		$css = file_get_contents("/svn/svnroot/Applications/xmlvoucher/design/css/balance.css");
		$h = "<style>$css</style><b>Balance for perioden $begin til $end</b><br><br><table><tr><td>Kontonr</td><td>Kontonavn</td><td>Primo</td><td>Bev&aelig;gelse</td><td>Ultimo</td></tr>";
		array_push($hs,array('id'=>0,'text'=>$h));
		$sum = array();
		foreach ($balance as $kontonr => $saldi) {
			foreach ($sumfra as $sumnr => $fra) {
				if ($kontonr >= $fra && $kontonr < $sumnr) {
					if (!isset($sum[$sumnr]))
						$sum[$sumnr] = array('Primo'=>0,'Periode'=>0,'Saldo'=>0);
					$sum[$sumnr]['Primo'] += floatval($saldi['Primo']);
					if (isset($saldi['Periode']))
						$sum[$sumnr]['Periode'] += floatval($saldi['Periode']);
					$sum[$sumnr]['Saldo'] += floatval($saldi['Saldo']);
						
				}
			}
		}
//if (balance_form($balance,$sum) == "repeat") return "repeat";
		foreach ($sum as $kontonr => $saldi) {
			$kontonavn = $kp[$kontonr];
				// Turn off all error reporting
				error_reporting(0);
				if (round($saldi['Primo']) == 0 && round($saldi['Periode']) == 0&&round($saldi['Ultimo'])==0) continue;
				// Report all PHP errors (see changelog)
				error_reporting(E_ALL);
				$t.=  $kontonr. "=". "\033[1;4m" . "$kontonavn" . "=". danish_number($saldi['Primo']) . "=" . danish_number($saldi['Periode']). "=" . danish_number($saldi['Saldo']) . "\033[0m\n";						

				
				$h = "<tr><td>*$kontonr</b></td><td>" . utf8_decode($kontonavn) . "</td><td>" . danish_number($saldi['Primo']) . "</td><td>" . danish_number($saldi['Periode']) . "</td><td>" . danish_number($saldi['Saldo']) . "</td></tr></u>";
				array_push($hs,array('id'=>$kontonr,'text'=>$h));
		}
		foreach ($balance as $kontonr => $saldi) {
			if (!isset($saldi['Primo']))
				$saldi['Primo'] = 0;
			if (!isset($saldi['Periode']))
				$saldi['Periode'] = 0;

			$kontonavn = $kp[$kontonr];
			// Turn off all error reporting
			error_reporting(0);
			if (round($saldi['Primo']) == 0 && round($saldi['Periode']) == 0&&round($saldi['Ultimo'])==0) continue;
			// Report all PHP errors (see changelog)
			error_reporting(E_ALL);
			$t.=$kontonr . "=" . "\033[0;0m" . "$kontonavn=" . danish_number($saldi['Primo']) . "=" . danish_number($saldi['Periode']). "=" . danish_number($saldi['Saldo']) ."=" .  check_reconciliation($kontonr,$saldi['Saldo'],$end) . "=" . is_dimension_acc($dimensionskonti,$kontonr) . " \033[0m \n";
			$h = "<tr><td>$kontonr</td><td>" . utf8_decode($kontonavn) . "</td><td>" . danish_number($saldi['Primo']) . "</td><td>" . danish_number($saldi['Periode']) . "</td><td>" . danish_number($saldi['Saldo']) . "</td></tr>";
			array_push($hs,array('id'=>$kontonr,'text'=>$h));
		}
		file_put_contents("/svn/svnroot/tmp/column.tsv",$t);
		exec_app("cat /svn/svnroot/tmp/column.tsv|column -t -s\"=\"|sort -b -g >/svn/svnroot/tmp/balance.txt;");
		$txt = file_get_contents("/svn/svnroot/tmp/balance.txt");
		//echo $txt; // bruges ikke mere p.g.a. ny ncurses balance funktion
		unlink("/svn/svnroot/tmp/balance.txt");
		
		$h ="</table>";file_put_contents("/svn/svnroot/tmp/balance.html",$h);
		array_push($hs,array('id'=>99999,'text'=>$h));
		$h = process_html($hs);
		file_put_contents("/svn/svnroot/tmp/balance.html",$h);
		return true;
/*		if (console_input("Vis PDF rapport") == "j")
			system ("(html2ps /svn/svnroot/tmp/balance.html -o > /svn/svnroot/tmp/balance.ps;ps2pdf /svn/svnroot/tmp/balance.ps /svn/svnroot/tmp/balance.pdf;firefox -new-tab /svn/svnroot/tmp/balance.html) 2>/dev/null"); */
	}
	else if ($rep == "dimension") {
		$summer = array();
		$total = 0;
		$ingen = 0;
		foreach ($primo as $trans) {
			if ($trans['Konto'] != $konto)
				continue;
			$total += $trans['Beløb'];
			if (isset($trans['Dimension']) && strlen($trans['Dimension'])) {
				if (!isset($summer[$trans['Dimension']]))
					$summer[$trans['Dimension']] = $trans['Beløb'];
				else
					$summer[$trans['Dimension']] += $trans['Beløb'];
			}
			else
				$ingen += $trans['Beløb'];
		}
		foreach ($periode as $trans) {
			if ($trans['Konto'] != $konto)
				continue;
			$total += $trans['Beløb'];
			if (isset($trans['Dimension']) && strlen($trans['Dimension'])) {
				if (!isset($summer[$trans['Dimension']]))
					$summer[$trans['Dimension']] = $trans['Beløb'];
				else
					$summer[$trans['Dimension']] += $trans['Beløb'];
			}		
			else
				$ingen += $trans['Beløb'];
		}
		echo "Sum pr. dimensionsbasis\n";
		foreach ($summer as $dimnavn => $dimsaldo)
			echo "$dimnavn:\n\t$dimsaldo\n";
		echo "Posteringer uden dimension: $ingen\n";
		echo "Total: $total\n";
	}
	else if ($rep == "kk" || $rep == "kkk") {
		$k = $konto;
		$s = 0;
		switch ($rep) {
			case "kk":
				$t = "Dato|Bilag|Tekst|Beløb|Dimension|Saldo\n";
				break;
			case "kkk":
				$t = "Dato|Konto|Bilag|Tekst|Beløb|Dimension|Saldo\n";
				break;
		}
		if (isset($balance[$k]) && isset($balance[$k]['Primo']) && $rep == "kk") {
			$t = "Dato|Bilag|Tekst|Beløb|Saldo\n";
			$pb = ($balance[$k]['Primo']);
			$t .= "$begin|SYS|Overført tidl. periode|$pb| |$pb\n";
			$s += $pb;
		}
		$postings = array();
		foreach ($periode as $pp) {
			if ($pp["Konto"] == $k)
				array_push($postings,$pp);
		}	
		if ($recalc_postings == true)
			return $postings;
		else {
			if (kontokort_form($k,isset($pb) ? $pb : 0,$postings,$idx) == "repeat") {
				return "repeat";
			}
		}
		/*
		foreach ($periode as $trans) {
			$belob = danish_number($trans['Beløb']);
			if ($rep == "kk" && $trans['Konto'] == $k) {
				$s += $trans['Beløb'];
				$saldo = danish_number($s);
				$t .= "$trans[Dato]|$trans[Bilagsnr]|$trans[Tekst]|$belob| $trans[Dimension] | $saldo\n";
			}
			else if ($rep == "kkk") {
				if (isset($filter['bilag']) && (($filter['bilag'] == 'all') || ($filter['bilag'] == "") || ($filter['bilag'] == $trans['Bilagsnr']))) {
					$s += $trans['Beløb'];
					$saldo = danish_number($s);
					$t .= "$trans[Dato]|$trans[Konto]|$trans[Bilagsnr]|$trans[Tekst]|$belob| $trans[Dimension] | $saldo\n";
				}
			}
		}
		file_put_contents("/svn/svnroot/tmp/column.tsv",$t);
		exec_app("cat /svn/svnroot/tmp/column.tsv|column -t -s\"|\">/svn/svnroot/tmp/balance.txt;cat /svn/svnroot/tmp/balance.txt;rm /svn/svnroot/tmp/column.tsv;rm /svn/svnroot/tmp/balance.txt");
		*/
	}


}
function is_dimension_acc($dimensionskonti,$kontonr) {
	if (in_array($kontonr,$dimensionskonti))
		return "☑";
	else
		return "☐";
}
function dimension_summer($trans,&$sumarray) {
	if (isset($trans['Dimension']) && strlen($trans['Dimension'])) {
		echo "yes\n";
		if (!isset($sum_array[$trans['Dimension']]))
			$sum_array[$trans['Dimension']] = $trans['Beløb'];
		else
			$sum_array[$trans['Dimension']] += $trans['Beløb'];
	}
}
function func($p,&$push_to) {
	if (!isset($p['F1']))
		return $p;
	switch ($p['F1']) {
		case "":
			$p["Func"] = "";
			return $p;
			break;
		case "u25":
		case "U25":
			$p['Beløb'] = $p['Beløb'] * 0.8;
			$p["Func"] = $p["F1"];
			$new_t = $p;
			$new_t['Beløb'] = $p['Beløb'] * 0.25;	
			$new_t['Konto'] = 6902;
			unset($new_t['F1']);
			array_push($push_to,$new_t);
			return $p;
			break;
		case "i25":
		case "I25":
			$p['Beløb'] = $p['Beløb'] * 0.8;
			$p["Func"] = $p["F1"];
			$new_t = $p;
			$new_t['Beløb'] = $p['Beløb'] * 0.25;	
			$new_t['Konto'] = 6903;
			unset($new_t['F1']);
			array_push($push_to,$new_t);
			return $p;
			break;
		case "IV25":
		case "iv25":
			$p["Func"] = $p["F1"];
			$new_t = $p;
			$new_t['Beløb'] = $p['Beløb'] *.25;
			$new_t['Konto'] = 6906;
			unset($new_t['F1']);
			array_push($push_to,$new_t);
			$new_t['Beløb'] = $p['Beløb'] *-0.25;
			$new_t['Konto'] = 6907;
			array_push($push_to,$new_t);
			return $p;
			break;
		case "iy25":
		case "IY25":
			$p["Func"] = $p["F1"];
			$new_t = $p;
			$new_t['Beløb'] = $p['Beløb'] *.25;
			$new_t['Konto'] = 6908;
			unset($new_t['F1']);
			array_push($push_to,$new_t);
			$new_t['Beløb'] = $p['Beløb'] *-0.25;
			$new_t['Konto'] = 6909;
			array_push($push_to,$new_t);
			return $p;
			break;
			break;
		default:
			print_r($p);
			die("Ukendt funktion\n");
			break;
	}
}
function split_transactions($transactions) {
	$result = array();
	$i = 0;
	foreach ($transactions as $trans) {
		if (isset($trans["Beløb"]))
			$trans["Beløb"] = floatval($trans["Beløb"]);
		if (isset($trans['Konto']) && isset($trans['Modkonto'])) {
			$result[$i] = $trans;
			unset($result[$i]['Modkonto']);
			unset($result[$i]['F2']);
			$i++;
			$result[$i] = $trans;
			$result[$i]['Konto'] = $result[$i]['Modkonto'];
			unset($result[$i]['Modkonto']);
			$result[$i]['F1'] = $result[$i]['F2'];
			unset($result[$i]['F2']);
			$result[$i]['Beløb'] = $result[$i]['Beløb'] * -1;
			$i++;
		}
		else if (isset($trans['Konto'])) {
			$result[$i] = $trans;
			unset($result[$i]['Modkonto']);
			unset($result[$i]['F2']);
			$i++;

		}
		else if (isset($trans['Modkonto'])) {
			$results[$i] = $trans;
			$results[$i]['Konto'] = $results[$i]['Modkonto'];
			unset($results[$i]['Modkonto']);
			$results[$i]['F1'] = $results[$i]['F2'];
			unset($result[$i]['F2']);
			$result[$i]['Beløb'] = $result[$i]['Beløb'] * -1;
			$i++;
		}
		else {
			print_r($trans);
			die("somthing very strange happened, call the tech...\n");
		}
	}
	return $result;
}
function getperiod() {
	global $indstillinger;
	global $begin;
	global $end;
	if (!isset($indstillinger['begin']))
		setperiod();
	else {
		$begin = $indstillinger['begin'];
		$end = $indstillinger['end'];
	}
}
function setperiod($b = false,$e = false,$manual = false) {
	global $begin;
	global $end;
	global $indstillinger;
	global $argv;
	if ($manual == true) goto manual;
	if (isset($argv[1]) &&stristr($argv[1],"q")) {
		$word = preg_split("/q/i",$argv[1]);
		$word[1] -= 1;
		if ($word[1] > 3)
			die("Ugyldigt kvartal\n");
		$year = $word[0];
		$bmonth = array(1,4,7,10);
		$bmonth = $bmonth[$word[1]];
		$emonth = array(3,6,9,12);
		$emonth = $emonth[$word[1]];
		$begin = date("Y-m-01",strtotime("$year-$bmonth-01"));
		$end = date("Y-m-t",strtotime("$year-$emonth-01"));
		return;
	}
	else if (isset($argv[1]) && stristr($argv[1],"h")) {
		$word = preg_split("/h/i",$argv[1]);
		$word[1] -= 1;
		if ($word[1] > 1)
			die("Ugyldigt halvår\n");
		$year = $word[0];
		$bmonth = array(1,7);
		$bmonth = $bmonth[$word[1]];
		$emonth = array(6,12);
		$emonth = $emonth[$word[1]];
		$begin = date("Y-m-01",strtotime("$year-$bmonth-01"));
		$end = date("Y-m-t",strtotime("$year-$emonth-01"));
		return;
	}
	if (isset($argv[1])) { $begin = $argv[1]; goto end; }
	manual:
	if ($b == false) {
        begin:
        echo "Indtast startdato: ";
        $fd = fopen("PHP://stdin","r");
        $begin = trim(explode("\n",fgets($fd))[0]);
        fclose($fd);
        if (strlen($begin) != 10) {
                echo "Dato bør være 10 cifre lang, prøv igen...\n";
                goto begin;
        }
    }
    else
    	$begin = $b;
    if ($e == false) {
        end:
	if (isset($argv[2])) { $end = $argv[2]; goto done;}
        echo "Indtast slutdato: ";
        $fd = fopen("PHP://stdin","r");
        $end = trim(explode("\n",fgets($fd))[0]);
        fclose($fd);
        if (strlen($begin) != 10) {
                echo "Dato bør være 10 cifre lang, prøv igen...\n";
                goto end;
        }
    }
    else
    	$end = $e;
	done:
	$indstillinger['begin'] = $begin;
	$indstillinger['end'] = $end;


}
function filter_period($trans) {
	global $begin;
	global $end;
	global $data_pri;
	if (count($trans) == 0)
		return false;
	if (strtotime($trans['Dato']) >= strtotime($begin) &&strtotime($trans['Dato']) <= strtotime($end)) {
		return true;
	}
	else if (strtotime($trans['Dato']) < strtotime($begin)) {
		array_push($data_pri,$trans);
		return false;
	}
}
		
function validate($silent = false) {
	global $entrydata;
	global $data;
	global $kontoplan;
	$transactions = 0;
	$errors = 0;
	foreach ($data as &$dataen) {
		$dataen = (array)$dataen;
		if (count($dataen) == 0)
			continue;
		$transactions++;
		foreach ($entrydata as $d=>$dtype) {
			switch ($dtype) {
				case "text":
					break;
				case "date":
					if (!isset($dataen[$d]) || !validateDate($dataen[$d])) {
						$errors++;
						if ($dataen[$d] != ".")
							$dataen = change_trans($dataen,"Ugyldig $d");
						else
							$dataen[$d] = date("Y-m-d");
						save(true);
					}
					break;
				case "numeric":
					if (!isset($dataen[$d]) || !is_numeric($dataen[$d])) {
						$errors++;
						$dataen = change_trans($dataen,"Ugyldig $d");
						save(true);
					}
					break;
				case "numeric_optional":
						if (isset($dataen[$d]) && strlen($dataen[$d]) && !is_numeric($dataen[$d])) {
						$errors++;
						$dataen = change_trans($dataen,"Ugyldig $d");
						save(true);
						}
					break;	
				case "account":
						if (!isset($dataen[$d])) {
							$errors++;
							$dataen = change_trans($dataen,"Konto er obligatorisk");
						}
						else if (isset($kontoplan['Sumfra'][$dataen[$d]])) {
							$errors++;
							$dataen = change_trans($dataen,"Der kan ikke posteres på en sumkonto");
						}
						else if (!isset($kontoplan['kontoplan'][$dataen[$d]])) {
							if (is_numeric($dataen[$d]))
								add_account($dataen[$d]);
							else
								$dataen = change_trans($dataen,"Konto skal være numerisk");
						}
					break;
				case "account_optional":
					if (!isset($kontoplan['kontoplan'][$dataen[$d]])) {
						if (is_numeric($dataen[$d]))
							add_account($dataen[$d]);
						else
							$dataen = change_trans($dataen,"Konto skal være numerisk");
					}
					break;
				default: 
					die("Datatype $dtype ikke håndteret i programmet...\n");
					break;
			}
		}
	}
	if ($silent == false) echo "Datafil valideret: $transactions transaktioner, $errors fejl\n";
}
function change_trans_auto($trans,$auto_criteria) {
	$retval = $trans;
	$change = 0;
	foreach ($auto_criteria as $column => $val) {
		$retval[$column] = $val;
		$change++;
	}
	if ($change > 0)
		$retval['update'] = 1;
	else
		$retval['update'] = 0;
	return $retval;
}
function change_trans($d,$d2,$field = 'error') {
	$d[$field] = "$d2";
	/* file_put_contents("/svn/svnroot/tmp/transaction.json",json_encode($d,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	exec_app("vim /svn/svnroot/tmp/transaction.json");
	$retval= (array)json_decode(file_get_contents("/svn/svnroot/tmp/transaction.json"));
	*/
	$update = false;
	$retval = edit_voucher($d,$update);
	if ($update != false)
		$retval['update'] = 1;
	else
		$retval['update'] = 0;
	unset($retval[$field]);
	return $retval;
}
function add_account($acc_no) {
	global $kontoplan;
	if (!is_numeric($acc_no)) {
		echo "Vi har en transaktion hvor kontonummer ikke er numerisk, det vil blive ordnet ved næste validering...\n";
		return false;
	}
	$kontoplan['kontoplan'][$acc_no] = console_input("Konto $acc_no findes ikke i kontoplan endnu, indtast navn");
	save(true);
	return true;
}
function addentry_frominput() {
	global $data;
	global $kontoplan;
	$d = array();
	global $entrydata;
	foreach ($entrydata as $dd => $ddtype) {
		$d[$dd] = console_input($dd);	
		if ($dd == "Konto" || $dd == "Modkonto") {
			if(!isset($kontoplan['kontoplan'][$d[$dd]]))
				add_account($d[$dd]);	
		}
		if ($dd == "Dato") {
			if (strlen($d[$dd]) == 5) {
				$d[$dd] .= "-" . date('Y');
				echo "Dato genkendt som $d[$dd]\n";
			}
			else if (strlen($d[$dd]) == 4) {
				$d[$dd] = substr($d[$dd],0,2) . "-" . substr($d[$dd],2,2) . "-" . date("Y");
				echo "Dato genkendt som $d[$dd]\n";
			}
		}
	}
	array_push($data,$d);
	validate();
	save();
}
require_once("/svn/svnroot/Applications/jl/console_input.php");
function validateDate($input)
{
	return (strlen($input)==10);
}
?>
