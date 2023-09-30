<pre><?php
$i = 0;
foreach ($_POST['Date'] as $date) {
	$p = $_POST;
if ($_POST['Beløb'][$i] == ""||$_POST['DebKred'][$i] == ""||$_POST['KontoDrift'][$i] == "")
	continue;
$kontodrift = $_POST['KontoDrift'][$i];
$moms = false;
if (substr($kontodrift,0,1) == ".") {
        $moms = true;
        $kontodrift = substr($kontodrift,1);
}
$alias = exec("php /svn/svnroot/Applications/account_alias.php show " . $kontodrift);
$data = json_decode($alias,true);

$dato = $_POST['Date'][$i];
$debkred = $_POST['DebKred'][$i];
$belob = $_POST['Beløb'][$i];
$belob = exec("echo \"$belob\"|scale=2 bc");
$balancekonto = $_POST['Balancekonto'][$i];
$ref = " ; Ref : " . $_POST['Ref'][$i];
if ($moms) {
	echo "$dato $debkred$ref\n\t";
	echo "$data[postering]  $belob$ref\n\t";
	echo "$balancekonto  -$belob $ref\n\n";
}
else {
        echo "$dato $debkred$ref\n\t";
	$nettobelob = $belob *.8;
        echo "$data[postering]  $nettobelob$ref\n\t";
        echo "$balancekonto  -$belob$ref\n\t";
	echo "$data[moms]$ref\n\n";
}


$i++;
}
//header("location: viewb.html");
?>
