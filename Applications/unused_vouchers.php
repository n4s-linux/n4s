<?php
require_once("/svn/svnroot/Applications/array2table.php");
if (isset($argv[1]) && $argv[1] == "selected") {
	$sel = str_replace("┃","",file_get_contents("/tmp/selection_cur"));
	$sel = trim(explode("│",$sel)[0]);
	$ids = json_decode(file_get_contents("/tmp/unused_ids.txt"),true);
	if (isset($ids[$sel]) && isset($ids[$sel]["Filename"])) {
		if (isset($ids[$sel]["Filename"]))
			file_put_contents("/tmp/.current_voucher",$ids[$sel]['Filename']);
		else
			echo "error: no filename for " . file_get_contents("/tmp/selection_cur");
		//echo build_table(array($ids[$sel]['data']));
		$fn = trim(str_replace("\n","",$ids[$sel]['Filename']));
		echo "\nFilename: " . $fn. "\n";
		file_put_contents("/tmp/curfn",$fn);
		system("chmod 777 /tmp/curfn");
	}
die();
}
$data = file_get_contents("/tmp/.unused_vouchers");
//unlink("/tmp/.unused_vouchers");
require_once("fields.php");
$i = 0;
$field_numbers = array();
$field_numbers[$i++] = "ID";
foreach ($fields as $curfield) {
	$field_numbers[$i++] = $curfield;	
}
$lines = explode("\n",$data);
$elements = array();

foreach ($lines as $line) {
	$x = (explode("-_-",$line));
	//if ($x[0] != 0) continue;
	array_shift($x);
	$fn = implode("-_-",$x);
	$cols = explode("-_-",$line);
	$i = 0;
	$curarray = array();
	if (count($cols) < 2) continue;
	foreach ($cols as $curcol) {
			$curcol = str_replace(".pdf","",$curcol);
		//if (trim($curcol) != "") {
			if (isset($field_numbers[$i]))
				$curarray[$field_numbers[$i++]] = $curcol;
		//}
	}
	$curarray["Filename"] = "$fn";
	array_push($elements,$curarray);

	
}
echo "<table border=2>";
$ids = array();
$i = 0;
foreach ($elements as $element) {
		if (count($element) < 5) continue;

		echo "<tr>";
		echo "<td>$i</td>";
		$a = "";
		if (count($element) < 3) continue;
		foreach ($element as $key=>$val) {
			if ($key == "Filename") continue;
				$a .= "<td>$val&nbsp;</td>";
		}	
		$ids[$i] = array('Filename'=>trim($element["Filename"]),'data'=>$element);
		$i++;
		echo "$a</tr>";
	}
echo "</table>";
file_put_contents("/tmp/unused_ids.txt",json_encode($ids,JSON_PRETTY_PRINT));
?>
