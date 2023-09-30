<?php
$cmdz = array();
$delim = "|-|||||||-<___";
function lastcmdz() {
	system("tail -n5 /tmp/cmdz > /tmp/cmdz.5");
	return explode("\n",trim(file_get_contents("/tmp/cmdz.5")));
}
require_once("fzf.php");
$obligatory = array("Comments","Description","Reference","Date");
$args = ($argv);
$count = count($args);
array_shift($args);
//foreach ($args as $curfile) {
for ($i = 0;$i<count($args);$i++) {
$curfile = $args[$i];
if (!file_exists($curfile)) continue;
$ch = "";
$data = (json_decode(file_get_contents($curfile),true));
if (!$data) continue;
while (true) {
$buf = "";
$id = 0;
foreach ($obligatory as $curobl ) {
	if (!isset($data[$curobl]))
		$data[$curobl] = "";
}
foreach ($data as $curdat => $curval) {
	if (!is_array($curdat) && !is_array($curval))
		$buf .= str_pad($curdat,25) . "\t" . $curval . "\n";

}
foreach ($data["Transactions"] as $trans) {
	$acc = str_pad($trans["Account"],25);
	$amount = str_pad($trans["Amount"],10," ",STR_PAD_LEFT);
	$buf .= "Trans-$id\t$acc\t$amount\t$trans[Func]\n";
	$id++;
}
	if (!isset($data["History"]))
		$data["History"] = array();
	$h = array_reverse($data["History"]);
	$history = "";
	foreach ($h as $curh) {
		if (!isset($curh["Date"]))continue;
		$history .= "echo '$curh[Date]: $curh[Desc]';";
	}
	foreach (lastcmdz() as $lc) {
		$cmdz[explode("$delim",$lc)[1]] = explode("$delim",$lc)[0];
		$lc = "CMD\t". explode("$delim",$lc)[1];
		$buf .= "$lc\n";
	}
	$buf .= "Quit\n";
	$fzf = fzf($buf,"Edit $curfile ($i/$count) - ESC for back","--reverse --cycle --preview=\"$history\" --preview-window=down:5");
	$res = trim(explode("\t",$fzf)[0]);
	if ($res == "")break;
	if ($res == "Quit")die("Quitted while viewing $curfile ($i/$count)");
	if (!is_trans($res)) {
		if (isset(explode("CMD\t",$fzf)[1])) {
			$s = explode("CMD\t",$fzf)[1];
			$cmd = $cmdz[$s];
			eval($cmd.";");
			$h = array('Date'=>date("Y-m-d H:m"),"Desc"=>$s);
			array_push($data["History"],$h);
		}
		else {
			$str = askstr($res);
			$old = $data[$res];
			$data[$res] = $str;
			$change = "Ændret $res fra $old til $str";
			$h = array('Date'=>date("Y-m-d H:m"),"Desc"=>$change); array_push($data["History"],$h);$ch .= "$change\n";
			$cmd = "\$data['$res'] = '$str'" . $delim . "$change (repeat)\n";file_put_contents("/tmp/cmdz",$cmd,FILE_APPEND);
		}
	}
	else { // hvis det er en transaktion
		$oldres = $res;
		while (true) {
				$k = "";
				$res = $oldres;
				$id = explode("Trans-",$res)[1];
				if (!isset($data["Transactions"][$id]["P-Start"])) $data["Transactions"][$id]["P-Start"] = "";
				if (!isset($data["Transactions"][$id]["P-End"])) $data["Transactions"][$id]["P-End"] = "";
				foreach ($data["Transactions"][$id] as $key => $val) {
					$key = str_pad($key,15);
					$val = str_pad($val,15," ",STR_PAD_LEFT);
					$k .= "$key\t$val\n";
				}
				$valg = trim(explode("\t",fzf($k,"Vælg transaktion element for $id - ESC for back","--reverse --cycle"))[0]);
				if ($valg == "") break;
				$res = $valg;
				$old = $data["Transactions"][$id][$valg];
				$data["Transactions"][$id][$valg] = askstr($id."-".$valg);
				$str = $data["Transactions"][$id][$valg];
				$change = "Ændret T-$id - $res fra $old til $str";
				$h = array('Date'=>date("Y-m-d H:m"),"Desc"=>$change); array_push($data["History"],$h);$ch .= $change."\n";
				$cmd = "\$data['Transactions'][$id]['$valg'] = '$str'" . $delim . $change . " (repeat)\n";file_put_contents("/tmp/cmdz",$cmd,FILE_APPEND);



		}
	}
}
if ($ch != "" ) {
echo $ch;
echo 'ENTER TO SAVE, ANY OTHER KEY TO ABORT';
system("stty -icanon");
$input = ord(fread(STDIN,1));
system("stty sane");

if ($input == 10)
{
    file_put_contents($curfile,json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	echo "Saved $curfile\n";
}
else
	echo "Aborted changes in $curfile\n";
}
}
function is_trans($str) {
	$a = explode("Trans-",$str);
	return (isset($a[1]));
}
function askstr($q) {
	echo "Ny værdi for $q: ";
	$fd = fopen("PHP://stdin","r");
	return trim(fgets($fd));
	fclose($fd);
}
