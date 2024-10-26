<?php
require_once("/svn/svnroot/Applications/uc_odata.php");
require_once("/home/joo/uccred.php");
$firmaid = 94606;
 $d = getdata("GLAccountClient");
foreach ($d as $curacc) {
	$type = $curacc["AccountType"];
	if (!isset($darray[$type])) $darray[$type] = array();
	array_push($darray[$type],$curacc);
}
$income = array();
$otherinccome = array();
$expense = array();
$depreciation = array();
foreach ($darray as $type => $trans) {
	echo "type==$type\n";
	if ($type == "Header") continue;
	if ($type == "Calculation expression") continue;
	if ($type == "Total") continue;
	else if ($type == "Revenue" || $type == "Profit and loss") {
		foreach ($trans as $ct) {
			if (!isset($income["ThisYear"])) $income["ThisYear"] = 0;
			if (!isset($income["PrevYear"])) $income["PrevYear"] = 0;
			if (!isset($ct["ThisYear"])) $ct["ThisYear"] = 0;
			if (!isset($ct["PrevYear"])) $ct["PrevYear"] = 0;
			$income["ThisYear"] += $ct["ThisYear"];
			$income["PrevYear"] += $ct["PrevYear"];
		}
	}
	else if ($type == "Other Income") {
		foreach ($trans as $ct) {
			if (!isset($otherincome["ThisYear"])) $otherincome["ThisYear"] = 0;
			if (!isset($otherincome["PrevYear"])) $otherincome["PrevYear"] = 0;
			if (!isset($ct["ThisYear"])) $ct["ThisYear"] = 0;
			if (!isset($ct["PrevYear"])) $ct["PrevYear"] = 0;
			$otherincome["ThisYear"] += $ct["ThisYear"];
			$otherincome["PrevYear"] += $ct["PrevYear"];
		}
	}
	else if ($type == "Cost of Goods Sold" || $type == "Cost"||$type=="Expenses") {
		foreach ($trans as $ct) {
			if (!isset($expense["ThisYear"])) $expense["ThisYear"] = 0;
			if (!isset($expense["PrevYear"])) $expense["PrevYear"] = 0;
			if (!isset($ct["ThisYear"])) $ct["ThisYear"] = 0;
			if (!isset($ct["PrevYear"])) $ct["PrevYear"] = 0;
			$expense["ThisYear"] += $ct["ThisYear"];
			$expense["PrevYear"] += $ct["PrevYear"];
		}
	}
	else if ($type == "Depreciation") {
		foreach ($trans as $ct) {
			if (!isset($depreciation["ThisYear"])) $depreciation["ThisYear"] = 0;
			if (!isset($depreciation["PrevYear"])) $depreciation["PrevYear"] = 0;
			if (!isset($ct["ThisYear"])) $ct["ThisYear"] = 0;
			if (!isset($ct["PrevYear"])) $ct["PrevYear"] = 0;
			$depreciation["ThisYear"] += $ct["ThisYear"];
			$depreciation["PrevYear"] += $ct["PrevYear"];
		}
	}
	else {
		echo "Unhandled $type\n";
	}
}
