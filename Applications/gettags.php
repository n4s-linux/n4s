<?php 
function getcomingdeadlines($kundenr) {
	setlocale("LC_ALL","da_DK.UTF-8");
	$deadlinez = array_unique(explode("|",gettags($kundenr,"|",false)));	
	require_once("deadlinecodes.php");
	global $deadlines;
	$retval = "<table border=1><tr><td>Deadline ultimo</td><td>Frist</td></tr>";
	$rows = 0;
	$rowz = array();
	foreach ($deadlinez as $deadline) {
		$d = explode("###",$deadline);
		if (isset($d[1]))
			$dl = trim($d[1]);
		else
			continue;
		for ($i = 1;$i < 4;$i++) {
			$month = strtotime("+$i month");
			$monthno = date("n",$month);
			$monthname= strftime("%B %Y",$month);
//			print_r($deadlines[$dl]);
error_reporting(0);
			if (is_array($deadlines[$dl]) &&in_array($monthno,$deadlines[$dl])) {
				array_push($rowz,"<tr><td><font color=green>$monthname</font></td><td><font color=green>$deadlinedesc[$dl]</font></td></tr>");
				$rows++;
			}
error_reporting(E_ALL);
		}

	}
	$rowz = array_unique($rowz);
	foreach ($rowz as $rrr) 
		$retval .= $rrr;
	$retval .="</table>";
	if ($rows == 0)
		return "";
	else
		return "<h3>Nedenfor kommende deadlines som vi tager ansvar for:</h3><hr>$retval";

}
function gettags($kundenr,$delimiter = "<br>",$link = true,$mode = "current") {
        $res = mysqli_query($GLOBALS["___mysqli_ston"], "select id,memo from todo where kundenr=$kundenr and memo like '%###%'");
        $retval = "";
	$rv2 = "";
	$unique = array();
        while ($row = mysqli_fetch_assoc($res )) {
                $lines = explode("\n",$row['memo']);
                foreach ($lines as $line) {
                        if (substr($line,0,3) == "###" && !strstr($line,"-")) {
				if (in_array($line,$unique))
					continue;
				else
					array_push($unique,$line);
                                if ($link)
					$retval .= "<a target='_blank' href=http://jodb.mikjaer.com/svnroot/Applications/webinterface.php?kundenr=$kundenr&contents=1&sagid_f=$row[id]&customf=>";
				$retval .= $line;
				if ($link) $retval .="</a>";
				$retval .="$delimiter";
			}
			else if (substr($line,0,3) == "###") {
				if ($link)
					$rv2 .=  "<a target='_blank' href=http://jodb.mikjaer.com/svnroot/Applications/webinterface.php?kundenr=$kundenr&contents=1&sagid_f=$row[id]&customf=>";	
				$rv2 .= $line . $delimiter;
				if ($link) $rv2 .= "</a>";
			}
                }
        }
        if ($mode == "current")
		return $retval;
	else if ($mode == "done")
		return $rv2;
}
?>
