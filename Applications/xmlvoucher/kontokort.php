<?php 
function kontokort_html($kontokort,$periode) {
		assert($kontokort);
		ksort($kontokort);
		$html = "<html><head>";
		$html .="<script>" . file_get_contents("/svn/svnroot/Applications/xmlvoucher/design/table.js") . "</script>";

		$html .= "<style type=\"text/css\">" . file_get_contents("/svn/svnroot/Applications/xmlvoucher/design/css/balance.css") . "</style><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/> <title>XMLVoucher Balance rapport v1</title></head><body<h1>Kontokort ($periode)</h1>\n";
	foreach ($kontokort as $nr => $indhold) {
		usort($indhold,'date_sort');
		$html .= "<p align=left>Konto $nr</p>\n";
		$html .= "<table border=1 class=\"example table-autofilter\" width=800 class=\"box-table-a\">\n";
		$html .= "<thead><tr><th class=\"example table-autofilter\">Dato</th><th class=\"example table-autofilter\">Bilagsnr</th><th class=\"example table-autofilter\">Tekst</th><th class=\"example table-autofilter\">Konteringsalias</th><th class=\"example table-autofilter\">Momskode</th><th class=\"example table-autofilter\">Beløb</th><th class=\"example table-autofilter\">Saldo</th></tr></thead><tbody>\n";
		$saldo = 0;
		foreach ($indhold as $trans) {
			$saldo += floatval($trans['beløb']);
			$html .= "<tr><td>$trans[bilagsdato]</td><td>$trans[bilagsnr]</td><td>$trans[tekst]</td><td>$trans[kontering]</td><td>$trans[momskode]</td><td>$trans[beløb]</td><td>$saldo</td></tr>\n";
			
		}
		$html .= "</tbody></table><br>\n";
	}

	$html .= "</body></html>";
	file_put_contents("/svn/svnroot/tmp/rap.html",$html) or die("kan ikke skrive til html fil");
}
function date_sort($a,$b) {
  return strcmp($a['bilagsdato'], $b['bilagsdato']); //only doing string comparison

}
function kontokort($xml,$kontostart,$kontoslut,$startdato,$slutdato) {
foreach ($xml->vouchers->voucher as $voucher) {
	$bilagsdato = ($voucher->attributes());
	$bilagsdato = $bilagsdato['bilagsdato'][0];
	if (datomatch($startdato,$slutdato,$bilagsdato)) {
	foreach ($voucher->entry as $entry) {
		foreach ($entry->attributes() as $key => $value) {
			$var[$key] = $value;
		}
		if (kontonr_by_alias($xml,$var['kontering'][0]) >= $kontostart && kontonr_by_alias($xml,$var['kontering'][0]) <= $kontoslut  ) {
			foreach ($voucher->attributes() as $key2 => $value2) {
				$aktueltbilag[$key2] = $value2;
			}
			$aktueltbilag['kontering'] = $var['kontering'];
			$aktueltbilag['beløb'] = $var['beløb'][0];
			$aktueltbilag['momskode'] = $var['momskode'][0];
			$kontonr = intval(kontonr_by_alias($xml,$var['kontering'][0]));
			if (!isset($i[$kontonr]))
				$i[$kontonr] = 0;
			$index = $i[$kontonr];
			$retval[$kontonr][$index++] = $aktueltbilag;
			$i[$kontonr] = $index;
		}
		else {
			//Ingen match på kontointerval
			print_r($var);
		}

	}
	}
}
return (isset($retval)) ?$retval : null;
}
?>
