<?php
$op = exec("whoami");
// #godide lade bruger tilføje flere datasets som hentes automatisk til hukommelse og loades i balancerapporter - kræver vi normaliserer datasættene således at vi nuller de balancer der ikke har værdier der er i andre balancer, så de er sammenlignelige
$repbal = 0;
require_once("/svn/svnroot/Applications/newl_csv.php");
if (getenv("dataset") == "") $dataset = "All"; else $dataset=getenv("dataset");

if (isset($argv[1])&&$argv[1] == "previewbal") { $data = json_decode(file_get_contents("/home/$op/cursescmd.cache"),true);previewbal();exit();}

$data["All"] = getcsv("1970-01-01","2099-12-31",getenv("tpath"));
//$data = json_decode(file_get_contents("/home/$op/cursescmd.cache"),true);
file_put_contents("/home/$op/cursescmd.cache",json_encode($data));
system("stty -icanon");
while (true) {
	echo "\n> ";
	$c = fread(STDIN, 1);
	if ($c == "?") help();
	else if ($c == "/") search();
	else if ($c == "\n") help();
	else {
		if (function_exists($c)) {
			$r = $c();
			echo "$r\n";
		}
		else
			echo "Unknown function '$c'\n";
	}
}
function search() {
	$fd = fopen("PHP://stdin","r");
	echo "\n...Search for: ";
	$search = trim(fgets($fd));
	echo "Searcing for $search\n";
	return;
}
function q() {
	die("bye...\n");
}
function b() {
	global $dataset;
	global $data;
	$d = $data[$dataset];
	$tla = gettla($d);
	require_once("/svn/svnroot/Applications/fzf.php");
	$valg = fzf($tla."\nAll","Select one or more top level accounts (tab for multi)","--multi --tac --margin=1% --padding=1%");
	if ($valg == "All") 
		$valg = $tla;
	if ($valg != "") {
		$valg = showbal($valg);
		echo "\n$valg";
	}
}
function showbal($tlas) {
	require_once("/svn/svnroot/Applications/proc_open.php");
	global $op;
	file_put_contents("/home/$op/tmp/balshow.tla",getbal($tlas));
	$tlaz = str_replace("\n"," - ",$tlas);
	global $dataset;
	exec_app("cat /home/$op/tmp/balshow.tla|column -ts $'\t'|fzf --preview-window=top,1 --preview=\"dataset=$dataset previewline=\"{}\" php /svn/svnroot/Applications/cursescmd.php previewbal\" --ansi --tac --multi --header=\"B ($tlaz)\" --margin=1% --padding=1% > ~/tmp/balselect.tla");
	return trim(file_get_contents("/home/$op/tmp/balselect.tla"));
}
function getbal($tlas) {
	$tlaz = str_replace("\n"," - ",$tlas);
	$tlas = explode("\n",trim($tlas));
	$rv = "";
	global $repbal;
	$repbal = 0;
	foreach ($tlas as $curtla) {
		$rv .= gettlabal($curtla);
	}
	return $rv;
}
function getcolor($tla) {
	if ($tla == "Indtægter") return "\033[38;5;236;48;5;28m";
	if ($tla == "Udgifter") return "\033[38;5;236;48;5;161m";
	if ($tla == "Aktiver") return "\033[38;5;237;48;5;118m";
	if ($tla == "Resultatdisponering") return "\033[38;5;76;48;5;18m";
	if ($tla == "Egenkapital") return "\033[38;5;231;48;5;22m";
	if ($tla == "Passiver") return "\033[38;5;234;48;5;91m";
	if ($tla == "Fejlkonto") return "\033[38;5;16;48;5;196m";
	
}
function gettlabal($tla) {
	global $data; global $dataset; global $repbal;
	$ds = $data[$dataset];
	$col = getcolor($tla);
	$rv = getcolor($tla) . strtoupper($tla) ."\t🐶" . str_pad(" ",15," ",STR_PAD_LEFT) . "\033[0m\n";
	//$rv .= getcolor($tla) . $acc ."\t". str_pad(number_format($curbal,2,",","."),15," ",STR_PAD_LEFT) . "\033[0m\n";
	$total = 0;
	foreach ($ds as $currow) {
		$x = explode(":",$currow['Account']);
		if ($x[0] == $tla) {
			$l2 = $x[0] . ":".$x[1];
			if (!isset($bal[$l2])) $bal[$l2] = 0;
			if (substr($currow['Account'],0,strlen($l2)) == $l2)
				$bal[$l2] += $currow['Amount'];
				$total += $currow["Amount"];
		}
	}
	foreach ($bal as $acc => $curbal) {
		$col = getcolor($tla);
		$rv .= getcolor($tla) . $acc ."\t💰". str_pad(number_format($curbal,2,",","."),15," ",STR_PAD_LEFT) . "\033[0m\n";
	}
	require_once("/svn/svnroot/Applications/ansi-color.php");
	$tlaupper = strtoupper($tla);
	$rv .= getcolor($tla) . "∑ $tlaupper\t💰". str_pad(number_format($total,2,",","."),15," ",STR_PAD_LEFT) . "\033[0m\n\n";
	$repbal += $total;
	return $rv;
}
function d() {
	echo "\n";
	require_once("/svn/svnroot/Applications/fzf.php");
	global $dataset;
	global $data;
	$selectdataset = fzf("All\nPeriod\nCustom");
	if ($selectdataset == "") return
	$dataset = $selectdataset;
	if ($selectdataset == "Custom") {

		system("stty sane");
		$fd = fopen("PHP://stdin","r"); echo "Dataset Name: "; $name = trim(fgets($fd)); fclose($fd);
		$fd = fopen("PHP://stdin","r"); echo "Dataset Begin: "; $begin = trim(fgets($fd)); fclose($fd);
		$fd = fopen("PHP://stdin","r"); echo "Dataset End: "; $end = trim(fgets($fd)); fclose($fd);
		$data[$name] = getcsv($begin,$end,getenv("tpath"));
		$dataset = $name;

		system("stty -icanon");
	}
	else if ($selectdataset == "All")
		$data["All"] = getcsv("1970-01-01","2099-12-31",getenv("tpath"));
	else if ($selectdataset == "Period")
		$data["All"] = getcsv(getenv("LEDGER_BEGIN"),getenv("LEDGER_END"),getenv("tpath"));
	else if ($dataset == "All") $dataset = "Period"; else $dataset = "All";
	echo "Dataset $dataset\n";
}
function byaccount($a,$b) {
	return ($a['Account'] < $b['Account']);
}
function p() {
	global $data;global $dataset; global $op;
	$valg = "";
	echo "\n";
	$sorted = $data[$dataset];
	usort($sorted,"byaccount");
	foreach ($sorted as $cd) {
		$cd['Amount'] = number_format($cd['Amount'],2,",",".");
		$valg .= "$cd[Account]\t$cd[Date]\t$cd[tekst]\t$cd[Amount]\n";
	}	
	file_put_contents("/home/$op/tmp/postings.dat",$valg);
	system("cat /home/$op/tmp/postings.dat|column -ts $'\t'|fzf --multi -e --header=\"$dataset\" > ~/tmp/pvalg.dat");
	return trim(file_get_contents("/home/$op/tmp/pvalg.dat"));
}
function help() {
	echo "\n";
	echo "Options: (b)alance (p)ostings (m)asterdata (e)xport (d)ataset";
}
function gettla($dataset) {
	$tlas = array();
	foreach ($dataset as $curdata) {
		$curtla = explode(":",$curdata['Account'])[0];
		if (!in_array($curtla,$tlas)) array_push($tlas,$curtla);
	}
	require_once("/svn/svnroot/Applications/tlasort.php");
	usort($tlas,"tlasort");
	return implode("\n",$tlas);
}
function hexdump($data, $newline="\n")
{
  static $from = '';
  static $to = '';
  
  static $width = 16; # number of bytes per line
  
  static $pad = '.'; # padding for non-visible characters
  
  if ($from==='')
  {
    for ($i=0; $i<=0xFF; $i++)
    {
      $from .= chr($i);
      $to .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
    }
  }
  
  $hex = str_split(bin2hex($data), $width*2);
  $chars = str_split(strtr($data, $from, $to), $width);
  
  $offset = 0;
  foreach ($hex as $i => $line)
  {
    echo sprintf('%6X',$offset).' : '.implode(' ', str_split($line,2)) . ' [' . $chars[$i] . ']' . $newline;
    $offset += $width;
  }
}
function previewbal() {
	global $data;global $dataset; global $argv;
	system("clear");
	$pl = getenv("previewline");
	$acc = trim(explode("💰",$pl)[0]);
	$balz = array();
	foreach ($data[$dataset] as $curtrans) {
		$curtrans['Date'] = str_replace("\"","",$curtrans['Date']);
		$curtrans['Date'] = str_replace("/","-",$curtrans['Date']);
		if (substr($curtrans['Account'],0,strlen($acc)) == "$acc") {
			$curMonth = date("m", strtotime($curtrans['Date']));
			$curYear= date("m", strtotime($curtrans['Date']));
			$curQuarter = ceil($curMonth/3);
			$p = $curMonth . "Q" . $curQuarter;
			if (!isset($balz[$curQuarter])) $balz[$curQuarter] = 0;
			$balz[$curQuarter] += $curtrans['Amount'];
		}
	}
	$spark = "";
	foreach ($balz as $curbal) {
		$spark .= "$curbal ";
	}
	system("bash /svn/svnroot/Applications/spark $spark");
}
?>
