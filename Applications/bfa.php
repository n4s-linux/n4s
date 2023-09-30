<?php
	$ts = array();
	$defaultdate = null;
	$data = file_get_contents("brainfuckdata.txt");
	$lines = explode("\n",$data);
	$ln = 0;
	foreach ($lines as $line) {
		$ln++;
		if (substr($line,0,1) == "#") continue; // kommentarer starter med #
		if (!strlen(trim($line))) continue; // tomme linier ignoreres
		if (isdate($line)) {
			$defaultdate = date('Y-m-d',strtotime($line));
			continue;
		}
		else if (istransaction($line)) {
			addtrans($line,$defaultdate,$ln);
		}
		else {
			echo "Unhandled line: $line\n";
		}
	}
$balance = array();
$nulkontrol = 0;
	foreach ($ts as $transaction) {
		if (isset($balance[$transaction['konto']]))
			$balance[$transaction['konto']] += $transaction['belob'];
		else
			$balance[$transaction['konto']] = $transaction['belob'];
	
		$nulkontrol += $transaction['belob'];

	}
print_r($balance);
echo "Nulkontrol: $nulkontrol\n";
function addtrans($line,$date,$ln) {
	global $ts;
	$fields = explode(" ",$line);
	if (isdate($fields[0])) {
		$date= array_shift($fields);
	}
	$bilag = $fields[0];
	$konto = $fields[1];
	$belob1 = $fields[2];
	$belob2 = $fields[2] * -1;
	if (isset($fields[3]))
		$modkonto = $fields[3];
	$moms1 = explode(".",$konto);
	if (isset($moms1[1]))
		$belob1 = apply_moms($bilag,$belob1,$moms1[1],$date,$ln);
	if (isset($modkonto)) {
		$moms2 = explode(".",$modkonto);
		if (isset($moms2[1]))
			$belob2 = apply_moms($bilag,$belob2,$moms2[1],$date,$ln);
	}
	array_push($ts,array("line"=>$ln,"bilag"=>$bilag,"konto"=>explode(".",$konto)[0],"belob"=>$belob1,'date'=>$date,'linedata'=>$line));
	if (isset($modkonto)) array_push($ts,array("line"=>$ln,"bilag"=>$bilag,"konto"=>explode(".",$modkonto)[0],"belob"=>$belob2,'date'=>$date,'linedata'=>$line));
}
function apply_moms($bilag,$belob,$momskode,$date,$ln) {
	if ($momskode == "i") {
		addtrans("$bilag 6903 " . $belob * 0.2  ,$date,$ln);
		return $belob * 0.8;
	}
	else if ($momskode == "u")
		addtrans("$bilag 6902 " . $belob * 0.2 ,$date,$ln);
		return $belob * 0.8;
}
function isdate($date) {
	if (strtotime($date) > 1)
		return true;
	else
		return false;
}
function istransaction($line) {
	$d = explode(" ",$line);
	$minimumfields = array('bilag','konto','belob','modkonto');
	if (count($d) < count($minimumfields))
		return false;
	else
		return true;
}
?>
