<head>
<meta charset=utf8>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">


</head><body>
<form method=POST action=https://olsensrevision.dk/form.php enctype="multipart/form-data">


<?php
	$tpath = getenv("tpath");
	$bn = basename($tpath);
	echo "<input type=hidden name=tpath value=\"$bn\">";
	echo "<table class='table table-striped' width=700><tr><td>Dato</td><td>Reference</td><td>Tekst</td><td>Konto</td><td>&nbsp;</td><td>Beløb</td><td>Spørgsmål</td><td>Svar</td><td>Filupload</td>";
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
		if (stristr($line[2],"Åbning")) continue;
		echo "<tr>";
		$col = 0;
		foreach ($line as $field) {
			$x = explode("Filename: ",$field);
			if (isset($x[1])) {
				$fn = explode("\\n",$x[1])[0];
				$jd = json_decode(file_get_contents("$tpath/$fn"),true);
				$ufn = str_replace(".","dotdotdot",($fn));
			}
			else
				$ufn = "Ukendt";
			if ($col == 1) {
				$bilag = getbilag($field);
				echo "<td>$bilag</td>";
			}
			else if ($col == 5)
				echo "<td><p align=right>". niceval($field * -1) . "</p></td>";
			else if ($col == 6) {
				$gq = getquestion($line);
				echo "<td>$gq</td><td><input type=text name=\"file[$ufn ::::: $gq]\"></td>";
			}
			else if ($col == 7) {
				echo "<td><input type=file name=\"$ufn\"></td>";
			}
			else if ($col == 8) continue;
			else
				echo "<td>$field</td>";
			$col++;
		}
		echo "</tr>";
		$row++;
	}
	echo "</table><br>";
	echo "<br><input type=submit value=Indsend></form>";
	function getfn($fn,$row,$desc) {
		global $tpath;
		if (file_exists("$tpath/$fn")) {
		$retval = "<h3><a name=$row>$desc [$row]</h3>";
		return $retval . nicetable(json_decode(file_get_contents("$tpath/$fn"),true),$fn) . "</a>";
		}
		else
		return "";
	}
function getbilag($field) {
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

function niceval($a) {
	if ($a == "") return "&nbsp;";
	return number_format(floatval($a),2,",",".");
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
