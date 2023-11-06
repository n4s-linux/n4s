<?php 
function evalmath($equation)
{
        if ($equation == "") return "";
    $result = 0;
    // sanitize imput
    $equation = preg_replace("/[^a-z0-9+\-.*\/()%]/","",$equation);
    // convert alphabet to $variabel
    $equation = preg_replace("/([a-z])+/i", "\$$0", $equation);
    // convert percentages to decimal
    $equation = preg_replace("/([+-])([0-9]{1})(%)/","*(1\$1.0\$2)",$equation);
    $equation = preg_replace("/([+-])([0-9]+)(%)/","*(1\$1.\$2)",$equation);
    $equation = preg_replace("/([0-9]{1})(%)/",".0\$1",$equation);
    $equation = preg_replace("/([0-9]+)(%)/",".\$1",$equation);
    if ( $equation != "" ){
        if ($equation == "0") return 0;
        $result = @eval("return " . trim($equation) . ";" );
    }
    if ($result == null) {
        throw new Exception("Unable to calculate equation");
    }
    return $result;
   // return $equation;
}
?>
