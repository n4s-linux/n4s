<?php
$dl = "|\t|";
$tpath = getenv("tpath");
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';

echo "<meta charset=utf8>";
foreach (array('Indtægter','Udgifter','Egenkapital','Passiver') as $curacc) {
	echo "<a href=#$curacc>$curacc</a><br>";
}
foreach (array('Indtægter','Udgifter','Egenkapital','Passiver') as $curacc) {
	echo "<meta charset=utf8>";
	echo "<h2><a name=$curacc><font bgcolor=yellow>$curacc</font></a></h2>";
	system("LEDGER_SORT=date LEDGER_DEPTH=9999999999999999999 php /svn/svnroot/Applications/key.php ledger r $curacc --register-format=\"%(account)$dl%(code)$dl%(payee)$dl%(display_amount)$dl%(date)$dl" . "%(tag('Filename'))\n\" > /tmp/kkout.csv");
	$data = explode("\n",file_get_contents("/tmp/kkout.csv"));
	$kk = array();
	foreach ($data as $line) {
		$ld = explode($dl,$line);
		$account = explode($curacc.":",$ld[0])[1];
		$code = $ld[1];
		$payee = $ld[2];
		$amount = round($ld[3],2);
		$date = $ld[4];
		$fn = $ld[5];
		if (!isset($kk[$account]))
			$kk[$account] = array();
		array_push($kk[$account],array('Account'=>$account,'Code'=>$code,'Payee'=>$payee,'Amount'=>$amount,'Date'=>$date,'Filename'=>$fn));

	}
$rs = "<p align=right>";
$rend = "</p>";
	foreach ($kk as $acc) {
		$bal = 0;
		foreach ($acc as $trans) {
			if ($trans['Account'] == "") continue;
			foreach ($trans as $key => &$val) {
				if (strlen($val)) $val == "&nbsp";
			}
			if ($bal == 0) {
				echo "<h3>$trans[Account]</h3>";
				echo "<table class='table-striped' width=100% >";
				echo "<tr><td width=15%>Dato</td><td width=15%>Beskrivelse</td><td width=15%>$rs Code $rend </td><td width=10%>$rs Beløb $rend</td><td width=10%>$rs Balance $rend</td>";
			}	
			$bal += $trans['Amount'];
			$link = "";
			$linkend = "";
			$back = "";
			$backend = "";
			$backlink = "";
			if (isset($trans['Filename']) && strlen($trans['Filename']) > 0) {
				$link = "<a href=#$trans[Filename]>";
				$linkend = "</a>";
				$back = "<a name=back_$trans[Filename]>";
				$backend = "</a>";
				$backlink = "back_$trans[Filename]";
				
			}
			$af = number_format($trans['Amount'],2,",",".");
			$bf = number_format($bal,2,",",".");
			echo "$back<tr><td width=50>$link $trans[Date] $linkend</td><td width=50>$link $trans[Payee] $linkend</td><td width=30>$trans[Code]</td><td width=50><p align=right>$af</p></td><td width=50><p align=right>$bf</p></td></tr>$backend\n";
			if (strlen($trans['Filename']) > 0)
				$fns .= "<h3><a name=$trans[Filename]>$trans[Filename]</a>:</h3>" . prettytrans(json_decode(file_get_contents("$tpath/".$trans["Filename"]),true),$backlink) . "<br><br>";

		}
		echo "</table><br>";
	}
	unlink("/tmp/kkout.csv");
echo "<br><br><br>";
}
echo "$fns";

function prettytrans($trans,$backlink) {
	if ($backlink != null)
		$r = "<a href=#$backlink>Tilbage</a>";
	else
		$r = "";
	$r .= "<table class='table-striped'>";
	foreach ($trans as $elem=>$key) {
		if (!is_array($key)) {
			if ($key != "")
				$r .= "<tr><td>$elem</td><td>$key</td></tr>";
		}
		else {
			$r .= "<tr><td>$elem</td><td>" . prettytrans($key,null) . "</td></tr>";
		}
	}
	return $r . "</table>";
}

?>

