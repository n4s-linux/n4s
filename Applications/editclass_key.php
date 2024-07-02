<?php
$op = exec("whoami");
require_once("/svn/svnroot/Applications/proc_open.php");
require_once("/svn/svnroot/Applications/key_config.php");
$path = getenv("tpath");
  $lpath = $path;
unset ($argv[0]);
$fns = "";
exec("ls \"$lpath/\"|grep logic>/tmp/logics_$op");
$files = explode("\n",trim(file_get_contents("/tmp/logics_$op")));
foreach ($argv as $fn) {
    $data = json_decode(file_get_contents($fn),true);
    //$code = system("cat $lpath/" . 'logic');
    if (!isset($data['Transactions'][0])) continue;
//exec("ls \"$lpath\"|grep logic>/tmp/logics_$op");
//if (getenv("missing_vouchers") == 1) exit;
$count = count($files);
$i = 0;
system("mkdir -p $path/.archive");
foreach ($files as $file) {
	//echo "logic on file " . $i++ . " / " . $count . "\n";
    $orghash = md5(json_encode($data));
    $t1 = $data['Transactions'][0];
	if (!isset($data["Transactions"][1])) continue;
    $t2 = $data['Transactions'][1];
	if ($data['Transactions'][0]['Amount'] == 0) continue;
    if (!is_file("$lpath/$file")) continue;
$code = file_get_contents("$lpath/$file");
    eval($code);
    $data['Transactions'][0] = $t1;
    $data['Transactions'][1] = $t2;
        if ($orghash != md5(json_encode($data))) {
		$bn = basename($fn);
		echo "$bn changed\n";
		$fns .= "\"$fn\" ";
		array_push($data['History'],array('op'=>$op,'Date'=>date("Y-m-d H:i"),'Desc'=>"Changed by logic ($file)"));
		file_put_contents("$fn", json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
		$olddata = $data;
		$oldbn = basename("$fn");
		$nf = basename($fn);
		$i = 0;
		$fn = "$path/.archive/$nf" . "_" . "$i.json";
		while (file_exists($fn)) { 
			$i++;
			$fn = "$path/.archive/$nf" . "_" .  "$i.json";                                                                                               
		}
		$olddata["History"][] = array("op"=>$op,"Desc"=>"Archived version of transactionfile by logic ($file)","Date"=>date("Y-m-d H:i"));
		file_put_contents($fn,json_encode($olddata,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

    	}
}

}
?>
