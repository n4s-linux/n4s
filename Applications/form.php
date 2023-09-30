hej
<?php
if (isset($_GET["token"])){
    include("modules/ledger_login/ledger_login.php");
    $auth->loginWithToken();
}
else
    header("location: ledger_login.php");
$l1 = array("Indtægter","Udgifter","Aktiver","Passiver","Fejl/Analyse");
foreach ($l1 as $toplevel) {
	echo "<form action=form.php method=GET>";
	echo "<input type=submit value=$toplevel>";
	echo "<input type=hidden name=toplevel value=$toplevel>";
	echo "</form>";
}
?>
