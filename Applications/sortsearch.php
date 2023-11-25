<?php
	function dosorting($arr) {
		require_once("fzf.php");
		$sortering = fzf("Date\nDescription\nAmount\nReference\nAccount\nContraAccount\n","Vælg sortering");
		if ($sortering == "") die("Afbrudt sortering af søgning\n");
		usort($arr,"sortsearch_$sortering");
		return $arr;
	}
	function sortsearch_Description($a,$b) {
		$key = "Description";
		return $a[$key] < $b[$key];
	}
	function sortsearch_Reference($a,$b) {
		$key = "Reference";
		return ($a[$key]) < ($b[$key]);
	}
	function sortsearch_Date($a,$b) {
		$key = "Date";
		return $a[$key] < $b[$key];
	}
	function sortsearch_Amount($a,$b) {
		$key = "Amount";
		$av = floatval($a['Transactions'][0][$key]) ;
		$bv = floatval($b['Transactions'][0][$key]);
		return $av < $bv;
	}
	function sortsearch_Account($a,$b) {
		$key = "Account";
		$av = floatval($a['Transactions'][0][$key]) ;
		$bv = floatval($b['Transactions'][0][$key]);
		return $av < $bv;
	}
	function sortsearch_ContraAccount($a,$b) {
		$key = "Account";
		$av = floatval($a['Transactions'][1][$key]) ;
		$bv = floatval($b['Transactions'][1][$key]);
		return $av < $bv;
	}
?>
