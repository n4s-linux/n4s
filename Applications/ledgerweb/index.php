<meta charset=utf8>
<?php 
error_reporting(E_ALL);
echo "<pre>";
require_once("config.php");	
if (!isset($_GET['beginytd']))
	$beginytd = date("Y") . "-01-01";
else
	$beginytd = $_GET['beginytd'];
if (!isset($_GET['endytd']))
	$endytd = date("Y-m-d");
else
	$endytd = $_GET['endytd'];
if (isset($_GET['begin']))
	$begin = $_GET['begin'];
else
	$begin = date("Y-m") . "-01";
if (isset($_GET['end']))
	$end = $_GET['end'];
else
	$end = date("Y-m-d");
	
$datafile = $_GET['customer'];
$income = runledger($datafile,"bal Indtægter --depth 2",$begin,$end,$beginytd,$endytd);
$expense = runledger($datafile,"bal Udgifter --depth 2",$begin,$end,$beginytd,$endytd);
$aktiver = runledger($datafile,"bal Aktiver --depth 2",$begin,$end,$beginytd,$endytd);
$passiver = runledger($datafile,"bal Passiver --depth 2",$begin,$end,$beginytd,$endytd);
$egenkapital = runledger($datafile,"bal Egenkapital --depth 2",$begin,$end,$beginytd,$endytd);
$globalsum = 0;
echo "<table><tr><td valign=top>";
baltable($income,"Indtægter");
baltable($expense,"Udgifter");
baltable($aktiver,"Aktiver");
baltable($egenkapital,"Egenkapital");
baltable($passiver,"Passiver");
echo "Nulkontrol: $globalsum\n";
echo "</td><td valign=top>";
if (isset($_GET['explore']))
	explore($_GET['explore'],$datafile,$_GET['exploremode']);
echo "</td></tr></table>";
function baltable($data,$headline = "Overskrift",$links = true) {
	global $datafile;
	global $globalsum;
	if ($links)
		echo "<table border=1 >";
	else
		echo "<table border=10 width=600>";
        $hl = strtoupper($headline);
	echo "<tr><td width=450><b>$hl</b></td><td width=75><b>Periode</b></td><td width=75><b>ÅTD</b></td></tr>";
	$sum = 0;
	$sumytd= 0;
	$curid = -1;
echo "<pre>";
	$begin = $data[0]['period']['begin'];
	$end = $data[0]['period']['end'];
	$beginytd = $data[1]['period']['begin'];
	$endytd = $data[1]['period']['end'];
	$akksum = 0;
	foreach ($data[0]['array'] as $row) {
		$curid++;
		$account = $row[0];
		if (!strlen($row[0]))
			$account = $data[1]['array'][$curid][0];
		if ($links)
		$row[0] = substr($row[0],strlen($headline)+1);
		echo "<tr><td>";
error_reporting(0);
		if ($links)
			echo "<a href=\"index.php?explore=$account&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd&exploremode=period&datafile=$datafile\">";
		else
			echo "<a href=\"index.php?explore2=$row[2]&exploremode=period&datafile=$datafile\">";
error_reporting(E_ALL);
		echo "$account";
		echo "</a>";
		echo "</td><td> <p align=right>";
error_reporting(0);
		if ($links)
			echo "<a href=\"index.php?explore=$account&begin=$begin&end=$end&beginytd=$beginytd&endytd=$endytd&exploremode=period&datafile=$datafile\">";
		else
			echo "<a href=\"index.php?explore2=$row[2]&exploremode=ytd&datafile=$datafile\">";
error_reporting(E_ALL);
		if (isset($row[1]))
			echo "$row[1]";
			echo "</a>";
		echo "</p></td>";
		if (isset($row[1])) $akksum += $row[1];
		if ($links) {
			error_reporting(0);
			echo "<td><p align=right><a href=\"index.php?explore=$account&beginytd=$beginytd&endytd=$endytd&begin=$_GET[begin]&end=$_GET[end]&exploremode=ytd&datafile=$datafile\">" . $data[1]['array'][$curid][1]  . "</a></p></td>";
			error_reporting(E_ALL);
		}
		else {
			 echo "<td><p align=right><a href=\"index.php?explore=$account&beginytd=$beginytd&endytd=$endytd&begin=$_GET[begin]&end=$_GET[end]&exploremode=ytd&datafile=$datafile\">" . $akksum . "</a></p></td>";
		}
		echo "</tr>";
		if (isset($row[1])) $sum += $row[1];
error_reporting(0);
		$sumytd += $data[1]['array'][$curid][1];
error_reporting(E_ALL);
	}
	if ($links)
		echo "<tr><td><b><u>$headline i alt</u></b></td><td><p align=right>$sum</p></td><td><p align=right>$sumytd</p></td></tr>";

	echo "</table>";
}
function explore($account,$datafile,$explore = 'period') {
	global $beginytd;
	global $endytd;
	if ($explore == "period")
		$data = runledger($datafile,"reg \"$account\"", $_GET['begin'], $_GET['end'],$beginytd,$endytd);
	else
		$data = runledger($datafile,"reg \"$account\"", $_GET['beginytd'], $_GET['endytd'],$beginytd,$endytd);
	baltable($data,"Indhold af konto $account for perioden $_GET[begin]-$_GET[end]",false,$explore);
}
function runledger($datafile = null,$cmd = 'bal', $start = '2018-05-01', $end = '2020-05-31',$startytd='2018-01-01',$endytd='2020-12-31') {
$periods = array('period'=>array('begin'=>$start,'end'=>$end),'ytd'=>array('begin'=>$startytd,'end'=>$endytd));
$retvalz = array();
$call = $cmd;
foreach ($periods as $period) {
	$cmd = ("/usr/bin/ledger --recursive-aliases -B -f /home/joo/$datafile/curl $call --begin '$period[begin]' --end '$period[end]' --register-format=\"%(date) %(payee) |||||%(amount)|||||%(note)|||||\\n\" --balance-format=\"%(account)|||||%(amount)\\n\" > /tmp/loutput\n");
	system($cmd);
	$data = file_get_contents("/tmp/loutput");
	//unlink("/tmp/loutput");
	$data = explode("\n",$data);
	$retval = array();
	foreach ($data as $curline) {
		$line = explode("|||||",$curline);
		if (isset($line[2])) {
			$line[2] = trim($line[2]);
		}
			array_push($retval,$line);
	}
	array_push($retvalz,array('period'=>$period,'array'=>$retval));
}
	return $retvalz;
}
?>
