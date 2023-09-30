<?php
$home = getenv("HOME");
$tpath = getenv("tpath");
$bn = basename("$tpath");
$htmlfn = "$home/tmp/exportacc_$bn.html";
if (!isset($argv[1]))
	die("usage: $account\n");
ob_start();
?>
	<p align=right><img src=https://olsensrevision.dk/wp-content/uploads/elementor/thumbs/olsens-onf38ivvwko6z6ujk7ubpjc3t0n8teweq5uwfuvuhs.png><br>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
<meta charset=utf8>
<?php
	$header = ob_get_clean() .myheader();
	ob_start();
	$tomorrow = date("Y-m-d",strtotime("tomorrow"));
 	$cmd = "noend=1 LEDGER_DEPTH=5 LEDGER_BEGIN=1970/1/1 LEDGER_END=$tomorrow php /svn/svnroot/Applications/key.php ledger csv $argv[1]";
	$tpath =getenv("tpath");
	system("$cmd > $tpath/.lcsv");
$row = 1;
echo "<table width=600 class=table>";
$saldo = 0;
$from = strtotime("tomorrow");
$to = getenv("LEDGER_END");
echo "<tr><td>Konto</td><td>Dato</td><td>Tekst</td><td><p align=right>Beløb</p></td><td><p align=right>Saldo</p></td></tr>";
if (($handle = fopen("$tpath/.lcsv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	$konto = str_replace("Aktiver:Igangværende arbejder:","",$data[3]);
	if (!stristr($konto,$argv[1])) continue;
        $num = count($data);
        $row++;
	$dato = $data[0];
	if (strtotime($dato) < $from)
		$from = strtotime($dato);
	if (strtotime($dato) > $to) 
		$to = strtotime($dato);
	$tekst = $data[2];
	$belob = $data[5];
	$saldo += $belob;
	if ($saldo == 0) {
		ob_clean();
		echo "<table class=table>";
		$from = strtotime($dato);
	}
	echo "<tr><td>$konto</td><td>$dato</td><td>$tekst</td><td><p align=right>$belob</p></td><td><p align=right>$saldo</p></td></tr>";
    }
    fclose($handle);
}

echo "</table>";
$data = ob_get_clean();
$details = "<h2>Kontospecifikation '$argv[1]' for perioden ".date("Y-m-d",$from) . " - ".date("Y-m-d",$to)."</h1>";
file_put_contents("$htmlfn",$header.$details.$data.mkfooter());
$cmd = ("mkdir -p ~/tmp/Fakturaspecifikationer/; pandoc $htmlfn -V geometry:landscape -o ~/tmp/Fakturaspecifikationer/\"$argv[1]" ."_" . date("Y-m-d") .  "\".pdf ");
echo "Laver pdf...\n";
system("$cmd");
exec_app("lynx -dump $htmlfn");
function mkfooter() {
	return "<b>Med venlig hilsen</b><br>Jørgen Olsen<br>Olsens Revision ApS<br>Fortunstræde 1, 2.<br>1065 København K";
}
function myheader() {
	require_once("/svn/svnroot/Applications/proc_open.php");
	global $home;
	exec_app("vim $home/tmp/exportmsg");
	return "<br><br>" .nl2br(file_get_contents("$home/tmp/exportmsg"));
}
?>
