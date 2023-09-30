<?php
$link = mysqli_connect("jodb.0lsen.com","root","jh3r7qTQ","curses");
mysqli_select_db($link,"curses");
$begin = '2018-12-23';
$data = file_get_contents($argv[1]);

$dom = new domDocument;

@$dom->loadHTML($data);
$dom->preserveWhiteSpace = false;

$rows = $dom->getElementsByTagName('tr');
$i = 0;
$tcz = array();
foreach ($rows as $row) {
	$tc =$row->textContent;
	$tc = strip_tags($tc);
	$tc = strip_whites($tc);
	if ($tc[0] != false)
		$tcz[$i++] = $tc;
	
}
$tcz = (array_values($tcz));
foreach ($tcz as $t) {
	$date = $t[0];
	$text = utf8_decode($t[1]);
	$amount = $t[2];
	$balance = $t[3];
	$amount = str_replace(".","",$amount);$amount = str_replace(",",".",$amount);$amount = floatval($amount);
	$balance = str_replace(".","",$balance);$balance = str_replace(",",".",$balance);$balance = floatval($balance);


	$p = array('Date'=>$date,'Skattekonto_Balance'=>$balance,'Account'=>9900,'ContraAcc'=>6805,'Dimension'=>'Revi','Amount'=>-1*$amount,'Desc'=>$text);
	$p['Memo'] = 'Dankonto.php UID: ' . md5(json_encode($p));
	if (strtotime($p['Date']) >= strtotime($begin)) {
		$q = "select * from curses_priv_dat_json where contents like '%" . $p['Memo'] . "%' and contents not like '%Hidden%';";
		$res = mysqli_query($link, $q);
		if (!$res) die(mysqli_error($link));
		if (mysqli_num_rows($res) < 1) {
			$pp = json_encode($p);
			echo $pp;
			$res = mysqli_query($link, "insert into curses_priv_dat_json (contents) values ('$pp');");
			if (!$res) die(mysqli_error($link));
		}	

	}
}
function strip_whites($str) {
	$retval = array();
	$ex = explode("\n",$str);
	$i = 0;
	foreach ($ex as $s) {
		$t = trim($s);
		if (strlen($t) > 2) {
			$retval[$i++] = $t;
		}
	}
	$retval[0] = strtotime($retval[0]);
	if ($retval[0] != false)
		$retval[0] = date("Y-m-d",$retval[0]);
	return $retval;
}

?>
