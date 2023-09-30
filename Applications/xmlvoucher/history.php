<?php
function get_history($xml,$compare) {
	$retval['konto'] = "";
	$retval['momskode'] = "";
	$retval['modkonto'] = "";
	$retval['momskode2'] = "";
	foreach ($xml->vouchers->voucher as $voucher) {
		$tekst = (string)($voucher->attributes()->tekst);
		if (strcasecmp($tekst,$compare) == 0) {
			$lastmatch = $voucher;
		}
	}
	if (!isset($lastmatch))
		return $retval;
	$i = 0;
	$voucher = $lastmatch;
	foreach ($voucher->entry as $entry) {
		$i++;
	}
	$attrib = ($voucher->entry[0]->attributes());
	$retval['konto'] = $attrib['kontering'];
	$retval['momskode'] = $attrib['momskode'];
	$y = 1;
	$attrib = ($voucher->entry[$y]->attributes());
	if ($i > 1) {
		while ($attrib['kode'] == "moms" && isset($voucher->entry[$y])) {	
			$attrib = ($voucher->entry[$y]->attributes());
			$y++;
		}
		$retval['modkonto'] = $attrib['kontering'];
		$retval['momskode2'] = $attrib['momskode'];
		//både konto & modkonto (samt moms for begge)
	}

	return $retval;
}
?>
