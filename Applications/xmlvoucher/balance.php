<?php
require_once("rapportering.php");
function balance_html($xml,$balance,$periode = "") {
	$html = "<html><head><style type=\"text/css\">" . file_get_contents("/svn/svnroot/Applications/xmlvoucher/design/css/balance.css") . "</style><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/> <title>XMLVoucher Balance rapport v1</title></head><body><h1>Balance ($periode)</h1><table class=\"gridtable\" border=1><tr><td>Kontonr</td><td>Kontonavn</td><td>Bev&aelig;gelse</td>";
	foreach ($balance['saldi'] as $kontonr => $saldo) {
		$balancen[$kontonr] = $saldo;
		$type[$kontonr] = 'summering';
	}
	foreach ($balance['balancer'] as $kontonr => $saldo) {
		$balancen[$kontonr] = $saldo;
		$type[$kontonr] = 'finanskonto';
	}
	ksort($balancen);
	foreach ($balancen as $kontonr => $saldo) {
 		if ($kontonr) {
			if ($type[$kontonr] == 'finanskonto') {
				$kontonavn = $balance['kontonavne'][$kontonr];
				$html .= "<tr><td>$kontonr</td><td>" . htmlentities($kontonavn,null,'utf-8') . "</td><td>$saldo</td></tr>";
			}
			else if ($type[$kontonr] == 'summering') {
				$html .= "<tr><td><b>$kontonr</b></td><td><b>" . htmlentities(sum_navn($xml,$kontonr),null,'utf-8') . "</b></td><td><b>$saldo</b></td></tr>";
			}
		}
	}
	$html .= "</table></body></html>";
	file_put_contents("/svn/svnroot/tmp/rap.html",$html) or die("kan ikke skrive til html fil");
	
}
function balance($regnskabsfil, $xml,$kontostart,$kontoslut,$datostart = '1986-25-12',$datoslut = '2100-01-01') {
$konto['saldo'][0] = 0;
foreach ($xml->vouchers->voucher as $voucher) {
	$bilagsdato = ($voucher->attributes());
	$bilagsdato = $bilagsdato['bilagsdato'][0];
if (datomatch($datostart,$datoslut,$bilagsdato)) {
	foreach ($voucher->entry as $entry) {
		foreach ($entry->attributes() as $key => $value) {
			$var[$key] = $value;
		}
		$kontering = utf8_decode($var['kontering'][0]);
		$newalias = 1;
		foreach ($xml->aliases->kontoalias as $alias) {
			$attributes = $alias->attributes();
			$aliasnavn = $alias['aliasnavn'][0];
			$kontonr = $alias['kontonr'][0];
			if (utf8_decode($aliasnavn) == $kontering) {
				$kontering = intval($kontonr);
				$newalias = 0;
				break;
			}
		}
		if ($newalias == 1) {
			$konto_alias = $xml->aliases->addChild('kontoalias');
			$konto_alias->addAttribute('aliasnavn',utf8_encode($kontering));
			echo "Indtast kontonr for alias: '$kontering': ";
			$handle = fopen("php://stdin","r");
			$kontering = intval(fgets($handle));
			fclose($handle);
			$konto_alias->addAttribute('kontonr',intval($kontering));

		}
		$belob = $var['beløb'][0];
		if (!isset($konto['saldo'][$kontering]))
			$konto['saldo'][$kontering] = 0;
		$konto['saldo'][$kontering] += round(floatval($belob),2);
		$konto['saldo'][0] += round(floatval($belob),2);
		// her tjekker vi om kontoen er inkluderet i et sumelement...
		// Hvis noget ikke er værd at tælle sammen er det ikke værd at have med i en kontoplan...
		if (!is_in_sum($xml,$kontering)) {
			echo "konto '$kontering' indgår ikke i et sum-element, lad os lave det nu\n";	
			$nysum = $xml->summeringer->addChild('summering');
			$nysum->addAttribute('navn', getstring("Indtast sumelement's navn"));
			$nysum->addAttribute('nr_fra',getstring("Nummer fra"));
			$nysum->addAttribute('nr_til',getstring("Nummber til"));
			$nysum->addAttribute('nr_sum',getstring("Kontnummer for sumelement"));
		}	
		else {
			//if (!isset($konto['sumsaldo'][is_in_sum($xml,$kontering)]))
			foreach (is_in_sum($xml,$kontering) as $sumkontosum) {
				if (!isset($konto['sumsaldo'][$sumkontosum]))
					$konto['sumsaldo'][$sumkontosum] = 0;
				$konto['sumsaldo'][$sumkontosum] += round(floatval($belob),2);
			}
		}
		unset($var);
	}
}
}
$konto['saldo'][0] = round($konto['saldo'][0],2);
foreach ($konto['saldo'] as $key => $value) {
	$navnet = "";
	foreach ($xml->kontonavne->kontonavn as $kontonavn) {
		$attrib = $kontonavn->attributes();
		if ($key==$attrib['nr'])
			$navnet = $attrib['navn'];
	}
	if ($navnet == "") {
		$fd = fopen("php://stdin", "r");
		$s = "";
		while ($s == "") {
			echo "Indtast navn til konto $key: \n";
			$s = str_replace("\n","",fgets($fd));
		}
		$new_name = $xml->kontonavne->addChild('kontonavn');
		$new_name->addAttribute('navn',$s);
		$new_name->addAttribute('nr',$key);
		fclose($fd);
		$navnet = $s;
	}
	$kontonavnene[$key] = $navnet;

}
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml->asXML());
	$dom->save($regnskabsfil);
	if (!isset($konto['sumsaldo']))
		$konto['sumsaldo'] = null;
	$retval = array('balancer' => $konto['saldo'], 'kontonavne' => $kontonavnene,'saldi' => $konto['sumsaldo']);
	return $retval;
}
function is_in_sum($xml,$kontering) {
	$i=0;
	foreach ($xml->summeringer->summering as $summering) {
		$attrib = $summering->attributes();
		if ($kontering >= $attrib['nr_fra'] && $kontering <= $attrib['nr_til'])
			$retval[$i++] = intval($attrib['nr_sum']);
	}
	if (isset($retval))
		return $retval;
	else
		return 0;
}
function sum_navn($xml,$kontering) {
	foreach ($xml->summeringer->summering as $summering) {
		$attrib = $summering->attributes();
		if ($kontering == $attrib['nr_sum'])
			return ($attrib['navn']);
	}
	return 0;
}

function kontonr_by_alias($xml,$kontonr) {
	if (is_numeric($kontonr))
		return $kontonr;
	$list = $xml->xpath("//aliases/kontoalias[contains(@aliasnavn,'$kontonr')]");
	assert(count($list)>0);
	$a = $list[0]->attributes();
	return ($a['kontonr']);
}
function getstring($query) {
	$s = "";
	$fd = fopen("php://stdin","r");
	while (str_replace("\n","",$s) == "") {
		echo "$query : ";
		$s = fgets($fd);
	}
	fclose($fd);
	return str_replace("\n","",$s);
}
?>
