<?php
	$aff=true;
	$balance = -1;
	function acc($bal) {
		$tpath=getenv("tpath");
		global $aff;
		if ($aff) {
			$cmd="LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 LEDGER_DEPTH=5 ledger -f $tpath/curl accounts>$tpath/.accounts";
			system($cmd);
			$aff= false;
		}
		$cmd="cat $tpath/.accounts|fzf --header='balance $bal'";
		return system("$cmd");
	}
	function gs($str) {
		echo $str . ":" ;
		$fd =fopen("PHP://stdin","r");
		$str = explode("\n",fgets($fd))[0];
		fclose($fd);
		return $str;
	}
while ($balance != 0) {
	echo "balance: $balance\n";
	$acc = acc($balance);
	if ($balance == -1) $balance = 0;
	$amount = gs("amount");
	$balance +=$amount;
error_reporting(0);
	$t[$acc]['amount'] -= $amount;
error_reporting(E_ALL);
}
	print_r($t);
?>
