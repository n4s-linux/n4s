<?php
require_once("/svn/svnroot/Applications/prettyname.php");
$decimals = 0;
$tpath=getenv("tpath");



/*


	FIX ME FIX ME FIX ME
	FIX ME FIX ME FIX ME
	FIX ME FIX ME FIX ME
	datarray må ikke være den samme periode (ved budgetsammenligning) - dato skal ændres som det er nu, f.eks 1 i stedet for 01
	(er måske fikset... ?, måske ikke)


*/
echo "<meta charset=utf8>";
echo "<style>div { max-width: 900px }
body, h1, h2, h3, h4, h5, h6 {
  font-family: Hack, sans-serif, Arial, Helvetica
}

html, body {
  width: 210mm;
  height: 297mm;
  margin: 0 auto;
}

@page {
  size: auto;
  margin: 0;
}
  .avoid {
    page-break-inside: avoid !important;
    margin: 4px 0 4px 0;  /* to keep the page break from cutting too close to the text in the div */
  }

</style>
";
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">';
$begin =getenv("LEDGER_BEGIN") ;
$end = getenv("LEDGER_END");
$beginend = $begin.$end;
$periods = array(
	array('begin'=>$begin,'end'=>$end,'type'=>'actual')
);
$fn = "$tpath/.report_period_$beginend.json";
if (!file_exists($fn))
	file_put_contents($fn,json_encode($periods,JSON_PRETTY_PRINT));
require_once("/svn/svnroot/Applications/proc_open.php");
exec_app("vim \"$fn\"");
$periods = json_decode(file_get_contents("$fn"),true);
if (isset($argv[1]) && $argv[1] == "spec")
	$mspecs = "m/kontospecifikationer";
else
	$mspecs = "";
echo "<p align=right>";
echo "København " . date("Y-m-d") . "</p><center><br>";
for ($i = 0;$i<12;$i++)
echo "<br>";
echo "<br><b><font face=Hack>Perioderegnskab $mspecs for:</font></b><br>" . prettyname($tpath) . "</center>";
$plural = (count($periods) > 1) ? "r" : "";
echo "<center><br><b>Periode$plural:</b><br>";
	foreach ($periods as $curperiod) {
		echo $curperiod['begin']  . " - " . $curperiod['end'] . "<br>";
	}
echo "</center>";
echo "<p style=\"page-break-after: always;\">&nbsp;</p>";



$balances = array();
$accounts = array();
$delim = "-____888_||";
foreach ($periods as $period => $parray) {
	if ($parray['type'] == "budget") {
		$file = ".budget.ledger";
		if (!file_exists("$tpath/$file"))
			touch("$tpath/$file");
	}
	else
		$file = "curl";
	$cmd = ("LEDGER_DEPTH=999 LEDGER_BEGIN=$parray[begin] LEDGER_END=$parray[end] ledger --no-total --no-pager -f \"$tpath/$file\" bal --flat --balance-format=\"%(account)$delim%(display_amount)\\n\" > ~/tmp/.l.xml");
	$home = getenv("HOME");
	file_put_contents("/$home/tmp/l.cmd",$cmd);
	system($cmd);
	$data = explode("\n",file_get_contents("/$home/tmp/.l.xml"));
	foreach ($data as $line) {
		$x = explode("$delim",$line);
		if (!isset($x[1])) continue;
		$account = $x[0];
		$account = explode(":",$account);
		$account = $account[0] . ":" . $account[1];
		$amount = $x[1];
		$type = $parray['type'];
		if (isset($balances[$parray['begin'] . " - " . $parray['end'] . "($type)"][$account]))
			$balances[$parray['begin'] . " - " . $parray['end'] . "($type)"][$account] += floatval($amount);
		else
			$balances[$parray['begin'] . " - " . $parray['end'] . "($type)"][$account] = floatval($amount);
		if (!in_array($account,$accounts))
			array_push($accounts,$account);
	}
}
$accountorder = array("Indtægter","Udgifter","Resultatdisponering","Aktiver","Egenkapital","Passiver","Fejlkonto");
$sumz = array();
echo "<center><h3>Resultatopgørelse</h3></center>";
foreach ($accountorder as $toplevel) {
	echo "<table class='table-sm avoid' width=100% border=1><tr><td><b>$toplevel</b></td>";
	foreach ($periods as $curperiod)
		echo "<td>&nbsp;</td>";
	echo "</tr>";
	balance_header($periods);
	foreach ($accounts as $curaccount) {
		if (substr($curaccount,0,strlen($toplevel)) == $toplevel) {
			$curaccount_short = substr(substr($curaccount,strlen($toplevel)+1),0,35);
			echo "<tr><td width=175>$curaccount_short</td>";
			foreach ($periods as $curperiod) {
				if ($curperiod['type'] == "budget") $tds = "style=\"background-color:yellow\""; else $tds = "";
				echo "<td align=right width=125 $tds><p class=text-right>";
				$type = $curperiod['type'];	
				if (isset($balances[$curperiod['begin'] . " - " . $curperiod['end'] . "($type)"]) && isset($balances[$curperiod['begin'] . " - " . $curperiod['end'] . "($type)"][$curaccount])) {
					echo number_format($balances[$curperiod['begin'] . " - " . $curperiod['end']."($type)"][$curaccount],$decimals,",",".");
					if (isset($sumz[$toplevel][$curperiod['begin'] . " - " . $curperiod['end']."($type)"]))
						$sumz[$toplevel][$curperiod['begin'] . " - " . $curperiod['end']."($type)"] += $balances[$curperiod['begin'] . " - " . $curperiod['end']."($type)"][$curaccount];
					else
						$sumz[$toplevel][$curperiod['begin'] . " - " . $curperiod['end']."($type)"] = $balances[$curperiod['begin'] . " - " . $curperiod['end']."($type)"][$curaccount];
				}
				else echo "&nbsp"; echo "</p></td>";
			}

		}
		else {
//			echo substr($curaccount,0,strlen($toplevel)) . " != ".  $toplevel . "\n";
		}
	}
	echo "</tr>";
	echo "<tr><td><b><u>$toplevel i alt</u></b></td>";
	foreach ($periods as $curperiod) {
		if ($curperiod['type'] == "budget") $tds = "style=\"background-color:yellow\""; else $tds = "";
		echo "<td align=right $tds><b><u>";
		$type = $curperiod['type'];
		if (isset($sumz[$toplevel][$curperiod['begin'] . " - " . $curperiod['end']."($type)"]))
			echo number_format($sumz[$toplevel][$curperiod['begin'] . " - " . $curperiod['end']."($type)"],$decimals,",",".");
		else
			echo "&nbsp";
		echo "</u></b></td>";
	}	
	echo "</tr>";
	echo "</table><br>";
	if ($toplevel == "Resultatdisponering")
		echo "<p style=\"page-break-after: always;\">&nbsp;</p><center><h3>Balance</h3></center>";

}

if (!isset($argv[1]) || $argv[1] != "spec") {
	system("echo Printer ikke kontokort i balancen, skriv 'html spec' for at få dem med... > /dev/stderr");
}
else {
	// KONTOKORT START
	$parray = $periods[0];
		if ($parray['type'] == "budget") 
			$file = ".budget.ledger";
		else
			$file = "curl";
		$cmd = ("LEDGER_DEPTH=999 LEDGER_BEGIN=1970/01/01 LEDGER_END=2099/12/31 ledger --no-total --no-pager -f \"$tpath/$file\" register --register-format=\"%(account)\\t%(payee)\\t%(display_amount)\\t%(date)\\t%(code)\\n\" > ~/tmp/.l2.xml");
	system($cmd);
	$data = explode("\n",file_get_contents("/$home/tmp/.l2.xml"));
	unlink("/$home/tmp/.l.xml");
	unlink("/$home/tmp/.l2.xml");
	$postings = array();
	foreach ($data as $line) {
		$x = explode("\t",$line);
		if (!isset($x[3])) continue;
		$account = $x[0]; $payee = $x[1]; $amount = $x[2]; $date = $x[3]; $code = substr($x[4],0,5);
		array_push($postings, array('account'=>$account,'payee'=>$payee,'amount'=>$amount,'date'=>$date,'code'=> $code));
	}
	array_multisort(array_column($postings,'account'),array_column($postings,'date'),$postings);
	$accountorder = array("Indtægter","Udgifter","Aktiver","Egenkapital","Passiver","Fejl/analyse","Fejlkonto");
	echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
	$i = 0;
	$fejlkonto = array();
	foreach($periods as $curperiod) {
		if ($i++ >= 1) continue; // DISABLE THIS FOR FULL SPECS OF ALL PERIODS, NOT JUST THE FIRST
		echo "<center><u><h3><b>Poster for perioden $curperiod[begin] - $curperiod[end]</b></h3></u></center><br><font size=4>";
		$new_postings = array();
		foreach ($postings as $posting) {
			if (strtotime($posting['date']) >= strtotime($curperiod['begin']) && strtotime($posting['date']) < strtotime($curperiod['end'])) {
				array_push($new_postings,$posting);
			}
		}
		foreach ($accountorder as $toplevel) {
			echo "<center><h5>Poster på $toplevel</h5></center><br><table class=table-bordered border=1 width=100%>";
			$y = 0;
			//echo "<tr><td>Konto</td><td>Dato</td><td>Tekst</td><td>Beløb</td><td>Konto</td><td>$toplevel</td></tr>";
			kk_header($toplevel);
			$total = 0;
			$totalz = array();
			foreach ($new_postings as $posting ) {
				if (stristr($posting['account'],"fejl")) {
					array_push($fejlkonto,$posting);
				}
				$niceamount = number_format($posting['amount'],$decimals,",",".");
				if (substr($posting['account'],0,strlen($toplevel)) == $toplevel) {
					$total += $posting['amount'];
					if (isset($totalz[$posting['account']]))
						$totalz[$posting['account']] += $posting['amount'];
					else {
						$y++;
						if ($y > 0) {
							$colcount = 5;
							echo "<tr>";
							for ($i = 0;$i<$colcount;$i++) {
								echo "<td>&nbsp</td>";
							}
							echo "</tr>";
							$totalz[$posting['account']] = $posting['amount'];
						}
					}
					$account_cut = substr($posting['account'],strlen($toplevel)+1);
					$posting['payee'] = fixcode(substr($posting['payee'],0,25));
					

					if (isset($argv[1]) && $argv[1] == "censor") {
						$posting['payee'] = "Hemmeligholdt";
						$x = explode(":",$posting['account']);
						$acc = $x[0] . ":" . $x[1];
						if (isset($x[2]))
							$acc .= ":$x[2]";
						$show = $acc;
					}
					else
						$show=$account_cut;
					if (stristr($posting['code'],"csv")) {
						$posting['code'] = "";
						$color="brown";
					}
					else
						$color="green";
					$color="black";
					echo "<tr><td><font color=$color>$show</font></td><td><font color=$color>$posting[date]</font></td><td><font color=$color>$posting[code]</font></td><td><font color=$color>$posting[payee]</font></td><td align=right><font color=$color>$niceamount</font></td>";
					echo "<td align=right><font color=$color>(";
					$nicetotalz = number_format($totalz[$posting['account']],$decimals,",",".");
					echo $nicetotalz;
					echo ")</font></td>";
					//echo "<td align=right>$nicetotal</td>";
					echo "</tr>";

				}
			}
			$nicetotal = number_format($total,$decimals,",",".");	
			echo "</table><p align=right> Total $toplevel: $nicetotal<BR><br>";
		}
		echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
	}
}
// NOTER SLUTNING
$cmd = "touch $tpath/.notes_end.txt;vim $tpath/.notes_end.txt";
exec_app("$cmd");
$notes_back = file_get_contents("$tpath/.notes_end.txt");
if (strlen(trim($notes_back))) {
	echo "<p style=\"page-break-after: always;\">&nbsp;</p>";
	echo "<center><h3>Noter</h3></center>";

	$x = explode("\n",$notes_back);
	echo "<table class=table-striped width=100%>";
	$i = 0;
	foreach ($x as $line) {
		echo "<tr>";
		$c = explode("\t",$line);
		if (!isset($c[1])) {
			echo "</tr></table><br>$c[0]<br><table class=table-striped width=100%>";
			$i = 0;
			continue;
		}
		$y = 0;
		foreach ($c as $column) {
			$width=25;
			if ($y == 0) $width=100;
			if (is_numeric(str_replace(",",".",$column)))
				$column = number_format(str_replace(",",".",$column),$decimals,",",".");
			if ($i == 0 || $i == count($x))
				echo "<td width=$width><b><u>$column</u></b></td>";
			else
				echo "<td width=$width>$column</td>";
			$y++;
		}
		echo "</tr>";
		$i++;
	}
	echo "</table><br>";
}
function kk_header($toplevel)  {
?>
<tr>
  <th style="width: 35%">Konto</th>
  <th style="width: 8%">Dato</th>
<th style="width: 8%">Bilag</th>
  <th style="width: 25%">Tekst</th>
  <th style="width:  8%">Beløb</th>
  <th style="width:  8%">Konto</th>
</tr>
<?php
}
function rf_date($date) {
	return date("Y-m-d",strtotime($date));
}
function balance_header($periods) {
?><tr><td><b><u>Konto</u></b></td><?php
	$tds = "";
	foreach ($periods as $curperiod) {
		if ($curperiod['type'] == "budget") $tds = "style=\"background-color:yellow\""; else $tds = "";
?>
		<td align=right <?php echo $tds;?>><b><u><p class="text-xs-right"><?php echo rf_date($curperiod['begin']) . " - ".  rf_date($curperiod['end']);?></u></b></p>
	<?php }
?></tr><?php

}
function fixcode($string) {
$string = preg_replace('/[^[:print:]]/', '', $string);

return $string;

}
?>
