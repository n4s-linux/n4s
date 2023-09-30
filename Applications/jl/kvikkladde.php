<head><title>Kvik-kladdde</title><meta charset="UTF-8"></head><?php
	$pid = getmypid();
	$json = json_decode(file_get_contents("/svn/svnroot/tmp/kladde.json"),true);
	require_once("/svn/svnroot/Applications/proc_open.php");
	$transaktioner = $json['data'];
	$i = 0;
	$keys = array('Dato','Bilagsnr','Tekst','Konto','F1','Beløb','Modkonto','F2','Dimension');
	foreach ($transaktioner as $transaktion) {
		foreach ($transaktion as $key => $val) {
			if (!in_array($key,$keys))
				array_push($keys,$key);
		}
	}
	echo "<form action=http://localhost:8000/ method=POST><table border=1><input type=hidden name=submit value=1><tr>";
	foreach ($keys as $key) {
		echo "<td>$key</td>";
	}
	$i = 0;
	foreach ($transaktioner as $transaktion) {
		echo "<tr>";
		foreach ($keys as $key) {
			$width = colwidth($key);
			echo "<td><input size=$width type=text name=\"$key-$i\" value=\"";
			if (isset($transaktion[$key]))
				echo $transaktion[$key];
			echo "\"></td>";
		}
		echo "</tr>\n";
		$i++;
	}
	echo "</tr>\n";
	if (isset($_POST['xtra'])) {
		$y = 0;
		for ($y = $y;$y<$_POST['xtra'];$y++) {
		echo "<tr>";
		foreach ($keys as $key) {
			$width = colwidth($key);
			echo "<td><input size=$width type=text name=\"$key-$i\" value=\"";
			echo "\"></td>";
		}
		echo "</tr>\n";
		$i++;
		}
	}
	
	echo "</table><br>Er du færdig?<input type=checkbox name=done value=0><br>Indsæt ekstra rækker:&nbsp;<input type=text value=0 name=xtra><input type=submit></form>";
	unset($transaktioner);
	if (isset($_POST['submit'])) {
	foreach ($_POST as $trans => $val) {
		$exp = explode("-",$trans);
		if (!isset($exp[1]))
			continue;
		$key = $exp[0];
		$no = $exp[1];
		$transaktioner[$no][$key] = $val;
	}
	$t = array();
	foreach($transaktioner as $trans) {
		if (isset($trans['Dato']) && strlen($trans['Dato']))
			array_push($t,$trans);
	}
	$t = array('data'=>($t));
	file_put_contents("/svn/svnroot/tmp/kladde.json",json_encode($t, JSON_PRETTY_PRINT |JSON_UNESCAPED_UNICODE ));
	flush();
	if (isset($_POST['done']) && $_POST['done'] == 0) {
		file_put_contents("/svn/svnroot/tmp/kladde2.json",json_encode($t, JSON_PRETTY_PRINT |JSON_UNESCAPED_UNICODE ));
		exec_app("kill $pid;");
		echo "Opdateret fil\n";
	}
	}
function colwidth($colname) {
	$known = array('Dato'=>10,'Bilagsnr'=>4,'Tekst'=>20,'Konto'=>5,'F1'=>5,'Modkonto'=>5,'F2'=>5,'Dimension'=>5);
	if (isset($known[$colname]))
		return $known[$colname];
	else
		return 5;
}
?>
