<?php
$tpath = getenv("tpath");
$op = exec("whoami");
function mktrans($transactions,$date,$fn,$desc,$comment,$ref) {
	global $tpath;
	global $op;
	echo "checking $tpath/$fn.trans\n";
	if (file_exists("$tpath/$fn".".trans")) { echo ("; $fn exists\n"); return false;}
	$data["Date"] = $date;
	$data["Description"] = $desc;
	$data["Filename"] = $fn . ".trans";
	$data["Transactions"] = $transactions;
	$data["Comments"] = $comment;
	$data["Reference"] = $ref;
	$data["History"] = array(array("desc"=>"Ny transaktion","date"=>date("Y-m-d H:m"),"op"=>$op));
	file_put_contents("$tpath/$fn".".trans",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
?>
