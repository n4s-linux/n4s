<?php
	$op = exec("whoami");
	ob_start();
	system("find /data/regnskaber -name \*.trans -mtime -90|grep -v \.archive|grep -v newvouchers|grep -v \.bak");
	$data = explode("\n",trim(ob_get_clean()));
	$stats = array();
	foreach ($data as $curfile) {
		$j = json_decode(file_get_contents($curfile),true);
		if ($j == null)continue;
		$t = json_encode($j["Transactions"]);
		if (stristr($t,"fejlkonto")) {
			$bn = basename(dirname($curfile));
			if (!isset($stats[$bn]["Fejlkonto"])) $stats[$bn]["Fejlkonto"] = 0;
			$stats[$bn]["Fejlkonto"] += 1;
		}
	}
	asort($stats);
	$stats = array_reverse($stats);
	ob_start();
	system("find /data/regnskaber -name \*.unhandledvouchers -mtime -90");
	$d = ob_get_clean();
	$x = explode("\n",trim($d)) ;
	foreach ($x as $curx) {
		$bn = basename(dirname($curx));
		$count = intval(file_get_contents($curx));
		$stats[$bn]["Bilag"] = $count;
	}
	$h = "<table border=1><tr><td>Regnskab</td><td>Fejlkonto</td><td>Bilag</td></tr>";
	foreach ($stats as $name => $curstat) {
		if (!isset($curstat["Fejlkonto"])) $curstat["Fejlkonto"] = "&nbsp;";
		if (!isset($curstat["Bilag"])) $curstat["Bilag"] = "&nbsp;";
		$h .="<tr><td>$name</td><td>$curstat[Fejlkonto]</td><td>$curstat[Bilag]</td></tr>";
	}
	$h .= "</table>";
	file_put_contents("/home/$op/tmp/whattodo.html",$h);
	require_once("/svn/svnroot/Applications/proc_open.php");
	exec_app("w3m -dump ~/tmp/whattodo.html|less");
?>
