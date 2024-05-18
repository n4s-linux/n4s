<?php
function lowestid($data) {
	$curid = 99999999999999;
	foreach ($data["Transactions"] as $ct) {
		if (!isset($ct["TransactionNo"])) continue;
		if ($ct["TransactionNo"] < $curid) $curid = $ct["TransactionNo"];
	}
	return $curid;
}
function gethash($tid) {
		global $tpath;
                global $files;
                $hashdata = "";
                foreach ($files as $curfile) {
                        $data = json_decode(file_get_contents($curfile),true);
                        if (!isset($data["Status"]) || $data["Status"] != "Locked") continue;
                        foreach ($data["Transactions"] as $ct) {
                                if (!isset($ct["TransactionNo"]) || $ct["TransactionNo"] >= $tid) continue;
                                else $hashdata .= trim(file_get_contents($curfile));
                        }
                }
		$hash = hash('xxh32', $hashdata);
                return $hash;

        }
        function nextbookingnumber() {
                global $tpath;
                if (!file_exists("$tpath/.nextbookingno")) {
                        file_put_contents("$tpath/.nextbookingno",1);
                        return 1;
                }
                else return file_get_contents("$tpath/.nextbookingno");
        }
?>
