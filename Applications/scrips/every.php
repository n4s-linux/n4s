<?php

if ($handle = opendir(getenv("tpath"))) {
    while (false !== ($entry = readdir($handle))) {
error_reporting(0);
        $js = json_decode(file_get_contents(getenv("tpath")."/".$entry),true);
error_reporting(E_ALL);
	if (!isset($js['Comment'])) continue;
	$desc = $js['Comment'];
	if (stristr($desc,"#every")) {
		$p = explode("#every ",$desc)[1];
		every($p,$js['Transactions'],$js);
	}
    }
    closedir($handle);
}
function every($p,$t,$d,$defaultacc = "Aktiver:Likvider:Bank") {
	$momser = "";
	$lasttrans = null;
	$i = 1;
	for ($i < 1;$i < 24;$i++) {
		$date = date("Y-m-01",strtotime("+$i months"));
		if ($p == 'month') {
			echo "$date $d[Description] (every $p)\n";
			foreach ($t as $curtrans) {
				if ($curtrans['Account'] == $lasttrans)
					$curtrans['Account'] = $defaultacc;
				echo "\t$curtrans[Account]  $curtrans[Amount]\n";
				if ($curtrans['Func'] == "i"||$curtrans['Func']=="u") {
					$momser .= "$date $d[Description] every $p\n";
					$curtrans['Amount'] = -$curtrans['Amount'] / 5;
					$momser .= "\t$curtrans[Account]  $curtrans[Amount]\n";
					$momser .= "\tPassiver:Moms:Budgetteret\n";
				}

				$lastrans=$curtrans['Account'];

			}
			echo "\n";
			echo $momser;
			$momser = "";
		}
		else {
			$months = explode(",",$p);
			foreach ($months as $curmonth) {
				$transmonth = date("m",strtotime("+$i months"));
				if ($transmonth == $curmonth) {
					echo ";we got a match $transmonth vs $curmonth\n";
					
				}
			}
		}
	}
}
?>

