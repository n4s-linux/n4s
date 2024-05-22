<?php
$today = date("Y-m-d");
$hfn = getenv("HOME")."/tmp/.hf.$today";
if (!file_exists($hfn)) {
	$hf = array();
}
else
	$hf = json_decode(file_get_contents($hfn),true);

if (isset($argv[1])&&$argv[1] == "add" ) {
	$x = explode("||",$argv[2]);
	$var = explode("=",$x[0]);
	$key = $var[0]; $value = $var[1];
	array_shift($x);
	$na = array();
	$na["Variables"] = array($key=>$value);
	$na["Timestamp"] = time();
	$na["Description"] = getenv("hdesc");
	if (!isset($na["Params"]))
		$na["Params"] = array();
	foreach ($x as $param) {
		array_push($na["Params"],$param);	
	}
	array_push($hf,$na);
	file_put_contents($hfn,json_encode($hf,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
}
else {
	require_once("/svn/svnroot/Applications/fzf.php");
	$list = "";
	foreach ($hf as $curhf) {
		$list .= date("Y-m-d H:i",$curhf["Timestamp"]) . "\t" . $curhf["Description"] . "\n";
	}
	$retval = fzf($list);
}

?>
