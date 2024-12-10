<?php
	// TODO rewrite foreach folder with changes today to email report: find /data/regnskaber/ -maxdepth 1 -mtime -1
	$tpath = getenv("tpath");
	if ($tpath == "" ) die("requires tpath\n");
	$op = exec("whoami");
	$cmd = ("find $tpath/ -type f -mtime -360 |grep .trans$> /home/$op/tmp/mkactions.php.list");
	system("$cmd");
	date_default_timezone_set('Europe/Copenhagen');
	$op = exec("whoami");
	$filez = explode("\n",trim(file_get_contents("/home/$op/tmp/mkactions.php.list")));
	$h = array();
	if (!file_exists("$tpath/.from"))
		$from = date("Y-m-d",strtotime("-1 month"));
	else
		$from = date("Y-m-d H:i",(file_get_contents("$tpath/.from")));	
	echo "Generating report from $from\n";
	$html = "<table border=1>";
	$htmldata = array();
	echo "from $from\n";
	$lasttime = 0;
	foreach ($filez as $curfile) {
		$d = json_decode(file_get_contents($curfile),true);
		if (isset($d["History"])) {
			foreach ($d["History"] as $curhist) {
				if (!isset($d["Reference"])) $d["Reference"] = "";
				if (isset($curhist["op"])) $curhist["updatedby"] = $curhist["op"];
				if (isset($curhist["Date"])) $curhist["date"] = $curhist["Date"];
				if (isset($curhist["Desc"])) $curhist["desc"] = $curhist["Desc"];
				if (isset($curhist["Description"])) $curhist["desc"] = $curhist["Description"];
				if (!isset($curhist["date"])) continue;
				if (strtotime($curhist["date"]) < strtotime($from)) continue;
				$acc1 = $d["Transactions"][0]["Account"];
				$func1 = $d["Transactions"][0]["Func"];
				if ($func1 != "") $func1 = "[ $func1 ]";
				$acc2 = $d["Transactions"][1]["Account"];
				$func2 = $d["Transactions"][1]["Func"];
				if ($func2 != "") $func2 = "[ $func2 ]";
				$amount = $d["Transactions"][0]["Amount"];
				if (stristr($curhist["desc"],"deleted")) continue;
				if (!isset($curhist["updatedby"]))
					$curhist["updatedby"] = "?";
					$htmldata[] = array("ts"=>$curhist["date"],'html' =>"<td>$d[Description]<br>$d[Reference]</td><td>$d[Date]</td><td>$acc1 $func1</td><td>$acc2 $func2</td><td>$amount</td><td>$curhist[updatedby]</td><td>$curhist[date]</td><td>$curhist[desc]</td>");
			}
		}
		
	}
	if (count($htmldata))
		usort($htmldata,"htmlbydate");
	$last = 0;
	foreach ($htmldata as $curhtml) {
		$diff = strtotime($curhtml["ts"]) - $last;
		$html .= "<tr>" . $curhtml["html"] . "<td>$diff</td></tr>";
		$last = strtotime($curhtml["ts"]);
	}
	$html .= "</table>";
	$from = strtotime("today");
	file_put_contents("$tpath/.from",$from);
	file_put_contents("/home/$op/tmp/actions.html",$html);
	function htmlbydate($a,$b) {
		return strtotime($a["ts"]) < strtotime($b["ts"]);
	}
?>
