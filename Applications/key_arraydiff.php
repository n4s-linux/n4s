<?php
$home = getenv("HOME");
$histfile = "$home/.bash_history";
$tpath = getenv("tpath");
array_shift($argv);
$history =array();
foreach ($argv as $file) {
	$oldfn = ".".$file.".old";
	$oldfn = dirname($file) . "/." . basename($file) . ".old";
	$new = $file;
	$op = exec("whoami");
	if (!isset($argv[0]))
		die("usage: file1 file2 file3\n");
	$old = json_decode(file_get_contents($oldfn),true);
	$orgdarray = $old;
	$orgdarray["History"][] = array("Desc"=>"Changed transaction manually in editor","Date"=>date("Y-m-d H:i"),"op"=>$op);
	if ($old == false)
		die("cannot decode $oldfn\n");
	$olddata = $old;
	$old = flatten($old);
	$darray = json_decode(file_get_contents($new),true);
	if (!$darray)
		die("cannot decode $new\n");
	if (!isset($darray["History"]))
		$darray["History"] = array();
	$history = array();
	$new = flatten($darray);
	$diff = array_diff($old,$new);
	$diff2 = array_diff($new,$old);
	require_once("/svn/svnroot/Applications/autorule.php");
	if (isset($olddata['mkrule'])) {
		autorule($diff);
		unset($darray['mkrule']);
	}
if (!empty($diff)) {
	foreach ($diff2 as $key => &$val) {
		$newval = $val;
		$val = "console edit - New Value for $key: '" . $val . "'";
		$sk = substr($key,0,4);
		//file_put_contents($histfile,"# " . time() . "\n",FILE_APPEND);
		//file_put_contents($histfile,"$sk=$newval\n",FILE_APPEND);;
	}
	foreach ($diff as $key => &$val) {
		$oldval = $val;
		$val = "console edit - Deleted old value from $key: '" . $val . "'";
		$sk = substr($key,0,4);
	}	
	$diff = array_merge_recursive($diff,$diff2);
	$time = date("Y-m-d H:i");
	foreach ($diff as $curdiff) {
		if (!is_array($curdiff)) continue;
		foreach ($curdiff as $k => $val) {
		file_put_contents("/data/regnskaber/.log",$op . "$time: " ."$k => $val\n",FILE_APPEND);
		}
		
	}
	foreach ($diff as $key){
		if (!is_array($key)) continue;
		foreach ($key as $k => $val) {
			$h = array("desc"=>$val,"date"=>date("Y-m-d H:i:s"),'updatedby'=>$op);
			array_push($history,$h);
		}
	}
}
	if (!empty($diff)) {
		$darray["History"] = array_merge($darray["History"],$history);
		$nfn = str_replace(".trans","",basename($file));
		$uid = date("Y-m-dTHi");
		$nfn = "$tpath/.archive/$nfn" . "_" . $uid . ".trans";
		system("mkdir -p $tpath/.archive");
		file_put_contents($file,json_encode($darray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));	
		file_put_contents($nfn,json_encode($orgdarray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));	
	}


	if (isset($darray['USEFILE'])) {
		$fn = file_get_contents("/tmp/.current_voucher");
		$fn = "vouchers/$fn";
		$fileh = array('desc'=>"attached file manually: $fn",'date'=>date("Y-m-d H:i:s"),'updatedby'=>$op);
		$darray["Filereferences"][] = array('filepath'=>$fn,'upload_date'=>date("Y-m-d"),'upload_by'=>$op);
		unset($darray["USEFILE"]);
		array_push($darray["History"],$fileh);
		file_put_contents($file,json_encode($darray,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));	
	}




}
function flatten($array, $prefix = '') {
    $result = array();
    foreach($array as $key=>$value) {
        if(is_array($value)) {
            $result = $result + flatten($value, $prefix . $key . '.');
        }
        else {
            $result[$prefix . $key] = $value;
        }
    }
    return $result;
}
?>

