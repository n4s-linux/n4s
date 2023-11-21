<?php
function findmatch($open,$fejl,$maxtimediff = 30) {
	global $tpath;
	foreach ($open as $curopen) {
		foreach ($curopen['Transactions'] as $curopentrans) {
			foreach ($fejl as $curfejl) {
				if ($curopentrans['Amount'] == -$curfejl['Amount']) {
					$md = md5(json_encode($curopentrans) . json_encode($curfejl));
					if (file_exists("$tpath/.openentries/$md")) continue;
					$diff = abs(strtotime($fejl['Date']) - strtotime($curopentrans['Date']));
					return array('Timediff'=>$td,'Open'=>$curopentrans,'Fejl'=>$curfejl,'Ignorefile'=>"$tpath/.openentries/$md");
				}
			}
		}
	}
	return false;
}
function getopen($dk) {
	$grouped = groupbyacc($dk);
	$grouped_sorted = sortbydate($grouped);
	$removezeroes = removezero($grouped_sorted);
	return $removezeroes;
}
function removezero($d) {
	foreach ($d as &$curd) {
		$sum = 0;
		$transactions = array();
		foreach ($curd['Transactions'] as $curtrans) {
			$sum += $curtrans['Amount'];
			array_push($transactions,$curtrans);
			if (intval($sum) == 0) {
				$transactions = array();
			}
		}
		$curd['Transactions'] = $transactions;
	}
	return $d;
}
function transbydate($a,$b) {
	return strtotime($a['Date']) > strtotime($b['Date']);
}
function sortbydate($data) {
	foreach ($data as &$curdata) {
		usort($curdata['Transactions'],"transbydate");
	}
	return $data;
}
function groupbyacc($dk) {
	$r = array();
	foreach ($dk as $c) {
		$r[$c['Account']]['Transactions'][] = $c;
		if (isset($c['Account']['Sum']))
			$r[$c['Account']]['Sum'] += $c['Amount'];
		else
			$r[$c['Account']]['Sum'] = $c['Amount'];
	}
	// remove empty
	$retval = array();
	foreach ($r as $curr) {
		if (!intval($curr['Sum']) == 0)
			array_push($retval,$curr);
	}
	return ($retval);
}
function getfejl($x) {
	$fejl = array();
	foreach ($x as $curx) {
		$i = 0;
		foreach ($curx['Transactions'] as $curtrans) {
			if (stristr($curtrans['Account'],'Fejlkonto')) {
				$curtrans['Date'] = $curx['Date'];
				$curtrans['Description'] = $curx['Description'];
				if (isset($curx['Reference'])) $curtrans['Reference'] = $curx['Reference'];
				$curtrans['Filename'] = $curx['Filename'];
				array_push($fejl,$curtrans);
				$i++;
			}
		}
	}
	return $fejl;
}
function getdebcred($x) {
		$open = array();
		foreach ($x as $curx) {
			$i=0;
			foreach ($curx['Transactions'] as $curtrans) {
				if (stristr($curtrans['Account'],'Debitorer') || stristr($curtrans['Account'],'Kreditorer')) {
					$curtrans['OpenTrans'] = $i;
					$curtrans['Date'] = $curx['Date'];
					$curtrans['Description'] = $curx['Description'];
					if (isset($curx['Reference'])) $curtrans['Reference'] = $curx['Reference'];
					$curtrans['Filename'] = $curx['Filename'];
					array_push($open,$curtrans);
				}
				$i++;
			}
		}
		return $open;
}
?>
