<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<form action=htmlkasserapport.php>
<table class="table table-striped">
<tr><td>Dato</td><td><input readonly type=text name=dato value=<?=$_GET['dato'];?>></td></tr>
<tr><td>Firma</td><td><input readonly type=text name=firma value="<?=$_GET['firma'];?>"></td></tr>
<?php $morgen = $_GET["morgen"];$kort=$_GET["kort"];$mobilepay=$_GET["mobilepay"];$aften=$_GET["aften"];$diff=$_GET["diff"];
$udgifter=$_GET["udgifter"];
$udgifterdesc=$_GET["udgifterdesc"];

$oms = $_GET["oms"];
?>
<tr><td>Kassebeholdning - Morgen</td><td><input value="<?=$morgen?>" type=text name=morgen></td></tr>
<tr><td>Omsætning - Total - Incl moms</td><td><input type=text name=oms value="<?=$oms?>"></td></tr>
<tr><td>Heraf kortbetalinger</td><td><input type=text name=kort value="<?=$kort?>"></td></tr>
<tr><td>Heraf mobilepay</td><td><input value="<?=$mobilepay?>" type=text name=mobilepay></td></tr>
<tr><td>Kontante udgifter</td><td><input value="<?=$udgifter?>" type=text name=udgifter></td></tr>
<tr><td>Udgifter - Beskrivelse</td><td><textarea name=udgifterdesc><?=$udgifterdesc?></textarea></td></tr>
<tr><td>Kassedifference</td><td><input value="<?=$diff?>" type=text name=diff></td></tr>
<tr><td>Kassebeholdning - Aften</td><td><input value="<?=$aften?>" type=text name=aften></td></tr>

<input type=hidden name=uid value=<?=$_GET['uid']?>>
</form>
<?php

if (isset($_GET['oms'])) {
	system("mkdir -p kasserapporter");
    $date = $_GET['dato'];
    $uid = $_GET['uid'];
}
	$g = $_GET;
    foreach (array("morgen","oms","mobilepay","udgifter","kort","diff") as $curelement) {
    	if ($g[$curelement] == "") $g[$curelement] = 0;
    }
    $aftenbeholdning = $g["morgen"] + $g["oms"] - $g["mobilepay"] - $g["udgifter"] - $g["kort"] - $g["diff"];
    if ($g["aften"] != $aftenbeholdning) {
    	echo "<tr><td>Beregnet aftenbeholdning</td><td>$aftenbeholdning</td></tr>";
        echo "<tr><td>Beregning</td><td>". $g["morgen"] . " - " . $g["oms"] . " - " . $g["mobilepay"] . " - " . $g["udgifter"]. " - " .  $g["kort"] . " - " . $g["diff"] . ")</td></tr>";
        echo "<tr><td>Din aftenbeholdning</td><td>$g[aften]</td></tr>";
        $diff = $g["aften"] - $aftenbeholdning;
        echo "<tr><td>Diff</td><td>$diff</td></tr>";
    }
    else {
    		if ($g["morgen"] != "") {
        	echo "<tr><td><font color=green><b>Dagsrapport stemmer</b></font></td><td>&nbsp;</td></tr>";
        if (!isset($_GET['godkend']))
    		echo "<tr><td>Godkend</td><td><input type=checkbox name=godkend></td></tr>";
            }
        else {
            file_put_contents("kasserapporter/$uid" . "_" . $date,json_encode($_GET,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
                		echo "<tr><td><font color=green>Er indsendt</td><td>&nbsp;</td></tr>";


        }
    }
    
?></table>
<?php if (isset($_GET["godkend"])) 
echo "Tak for din kasserapport !";
else
echo "<input type=submit name=Gem>";
?>

