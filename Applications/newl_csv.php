<?php
function getcsv($begin,$end,$tpath) {
		$data = array();
		$csv = shell_exec("unset LEDGER_DEPTH;LEDGER_DEPTH=10 tpath=$tpath LEDGER_BEGIN=$begin LEDGER_END=$end color=none php /svn/svnroot/Applications/newl.php csv --no-pager -X -B");
		$lines = explode(PHP_EOL, $csv);
		$array = array();
		$data = array();
		foreach ($lines as $line) {
		    $array = str_getcsv($line);
			if (!isset($array[3]) || $array[3] == "") continue;
			$bilag = $array[1];
			$konto = $array[3];
			$tekst = $array[2];
			$dato = $array[0];
			$belob = $array[5];
			$tags = $array[7];
			array_push($data,array('Account'=>$konto,'tekst'=>$tekst,'Date'=>$dato,'Amount'=>$belob,'tags'=>$tags,'bilag'=>$bilag));
		}
		return $data;
}
?>
