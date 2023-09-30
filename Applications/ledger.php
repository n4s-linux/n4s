<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<?php
$fields = array("LEDGER_BEGIN","LEDGER_END","LEDGER_DEPTH","LEDGER_FILE");
$commands = array("bal","reg","csv");
echo "<table class='table table-striped' height=100% width=100%><tr><td width=75%>";
showstuff();
echo "</td><td width=25%>";
showmenu();
echo "</td></tr></table>";

function showstuff() {
//	if ($_GET['cmd'] == "bal")
		$foreaches = array("Indtægter","Udgifter","Aktiver","Passiver","Egenkapital","Fejl");
//	else
//		$foreaches = array("");
	foreach ($foreaches as $forhver) {
		$cmd = "";
		foreach ($_GET as $var => $val) {
			if (($forhver == "Aktiver" || $forhver == "Passiver" || $forhver == "Egenkapital") && $var == "LEDGER_BEGIN" && $cmd == "bal") {
				continue;
			}
			else {
				if (strlen($val)) $cmd .= "$var=$val ";
			}
		}
		$cmd .= "LEDGER_SORT=account,date ledger --no-total --payee-width=45 --account-width=60 -B  ";
		$cmd .= "--balance-format=\"<tr><td><p align=left>%a</p></td><td><p align=right>%(to_int(total))</p></td>\n\" ";
		$cmd .= "--register-format=\"<tr><td>%(account)</td><td>%(date)</td><td>%(code)</td><td>%(payee)</td><td>%(to_amount(display_amount))</td><td>%(to_amount(total))</td></tr>\" ";
		$cmd .= $_GET['cmd'] . " $forhver";
//		echo "<h4><b>$forhver</b></h4>";
		echo "<table class='table table-striped'>";
		if ($_GET['cmd'] == "reg") echo "<tr><td>Account</td><td>Date</td><td>Code</td><td>Payee</td><td>Amount</td><td>Total</td></tr>";
		ob_start();
		system($cmd);
		if ($_GET['cmd'] == "bal")
			$new = str_replace("$forhver:","",ob_get_contents());
		else
			$new = ob_get_contents();
		ob_end_clean();
		if ($_GET['cmd'] == "csv") echo "<pre>";
		echo $new;
		echo "</table><br><br><br>";
	}	
}

function showmenu() {
	global $fields;
	global $commands;
	echo "<form action=ledger.php method=GET>";
echo "<div class='form-group'>";

	echo "<table class='table table-striped'>";
	foreach ($fields as $field) {
		if (isset($_GET[$field]))
			$fv = $_GET[$field];
		else
			$fv = defaultfieldvalue($field);
		echo "<tr><td>$field</td><td>";
		echo "<input class=\"form-control col-9\" width=250 type=text value=\"$fv\" name=$field>";
		echo "</td></tr>";
	}
echo "<tr><td>Kommando</td><td><select name=cmd>";
	foreach ($commands as $field) {
		$fv = $field;
		echo "<option value=\"$fv\" ";
		if (isset($_GET["cmd"]) && $_GET["cmd"] == $fv) echo "selected";
		echo ">$fv</option>";
	}
	echo "</select></td></tr></table>";


	echo "<input type=submit></form>";
}
function defaultfieldvalue($field) {
	return "";
}
?>
