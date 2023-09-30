
<?php
require_once("niceval.php");
?>

<head>
<meta charset=utf8>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">


</head><body>


<?php
	$tpath = getenv("tpath");
	$bn = basename($tpath);
	echo "<input type=hidden name=tpath value=\"$bn\">";
	echo "<table class='table table-striped' width=700><tr><td>Dato</td><td>Reference</td><td>Tekst</td><td>Konto</td><td>&nbsp;</td><td>Beløb</td>";
	$row = 1;
/*
fgetcsv(
    resource $stream,
    ?int $length = null,
    string $separator = ",",
    string $enclosure = "\"",
    string $escape = "\\"
): array|false

*/
	$data = "";
	$gq = "Mangler bilag";
	echo "<pre>";
	while ($line = fgetcsv(STDIN,null,",","\"","\\")) {
		if (skipline($line)) continue;
		echo "<tr>";
		$col = 0;
		foreach ($line as $field) {
			$x = explode("Filename: ",$field);
			if (isset($x[1])) {
				$fn = explode("\\n",$x[1])[0];
				$jd = json_decode(file_get_contents("$tpath/$fn"),true);
				$ufn = htmlspecialchars($fn);
			}
			else
				$fn = "";
			if ($col == 1) {
				$bilag = getbilag($field);
				echo "<td>$bilag</td>";
			}
			else if ($col == 5)
				echo "<td><p align=right>". niceval($field * -1) . "</p></td>";
			else if($col==6) continue;
			else
				echo "<td>$field</td>";
			$col++;
		}
		echo "</tr>";
		$row++;
	}
	echo "</table><br>";
	function getfn($fn,$row,$desc) {
		global $tpath;
		if (file_exists("$tpath/$fn")) {
		$retval = "<h3><a name=$row>$desc [$row]</h3>";
		return $retval . nicetable(json_decode(file_get_contents("$tpath/$fn"),true),$fn) . "</a>";
		}
		else
		return "";
	}
function skipline($line ) {
	$date = $line[0];
	$ref = $line[1];
	$desc = $line[2];
	$acc = $line[3];
	$tpath = getenv("tpath");
	if (!file_exists("$tpath/.skipbytext"))
		file_put_contents("$tpath/.skipbytext",implode("\n",array('GEBYR','Lønoverførsel','Gebyrer ifølge nota','Kontanthævningsgebyr')));
	$skipbytext = explode("\n",file_get_contents("$tpath/.skipbytext"));
	if (in_array($desc,$skipbytext))
		return true;
	if (stristr($acc,"Fejl"))
		return true;
	if (stristr($acc,"Aktiver")) return true;
	if (stristr($acc,"Passiver")) return true;
	if (stristr($acc,"Egenkapital")) return true;

	return false;
}
function getbilag($field) {
	return $field;
	if (stristr($field,"csv-")) return "<font color=red>MANGLER</font>";
	return "<font color=green>$field</font>";
}
function getquestion($line)  {
	$arr["Description"] = $line[2];
	if (stristr($arr['Description'],"hvordan beta") || stristr($arr['Description'],"hvordan mod")) 
		return "Mangler betalingsdato + middel";
	else
		return "Manglende bilag";
}

function getresponse() {
	require_once("/svn/svnroot/Applications/proc_open.php");
	$op = exec("whoami");
	$fn = "/home/$op/tmp/getresponse.json";	
	exec_app("rm $fn;touch $fn;vim $fn");
	$data = json_decode(file_get_contents("$fn"),true);
	$bn = basename(getenv("tpath"));
	if (!isset($data['tpath'])) die("fejl, data response har ikke nogen kundeidentifikation (tpath)\n");
	if ($bn != $data['tpath']) die("fejl, afbryder $bn != $data[tpath]\n");
	foreach ($data['file'] as $key => $val) {
		$x = explode(":::::",$key);
		$fn = trim($x[0]);
		if ($fn == "") continue;
		$spm = $x[1];
		$svar = trim($key);
		if ($svar == "") continue;
		$fd = file_get_contents($fn) or die("kan ikke læse fil $fn\n");
		$fd = json_decode($fd,true);
		$fd['Svar'][] = array('svartidspunkt'=>date("Y-m-d"),'spørgsmål'=>$spm,'svar'=>$val);
		print_r($fd);
	}
}
?>
