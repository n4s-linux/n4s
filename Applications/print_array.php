<?php
function print_array($ar,$lvl=0) {
return;
        $retval = array();
$hclz = array();
        $nhcl = 1;
        //if (has_children($ar)) {
                foreach ($ar as $key => $val) {
                        for ($i = 0;$i<$lvl*3;$i+=1) echo "\t";
                        preg_match( '/[A-Z]/', $key, $matches, PREG_OFFSET_CAPTURE );
                        //$hcl = $matches[0][0];
                        if (!isset($matches[0][0])) {
                                $hcl = $nhcl++;
                        }
                        else {
                                if (!in_array($matches[0][0],$hclz)) {
                                        $hcl = $matches[0][0];
                                        array_push($hclz,$hcl);
                                }
                                else
                                        $hcl = $nhcl++;
                        }
                        echo "$hcl - $key\n";
                        $retval[$hcl] = $key;
                }
        return $retval;
        //}
        /*else {
                foreach ($ar as $key) {
                        for ($i = 0;$i<$lvl*3;$i+=1) echo "\t";
                        echo "\t*$key\n";
                }
        }*/
}
?>
