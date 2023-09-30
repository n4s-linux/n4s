<?php 
function shortacc($konto) {
        $x = explode(":",$konto) ;
        $retval = "";
        foreach ($x as $word) {
		$retval .= strtolower(mb_substr($word,0,1));
        }
        return $retval;
}
?>
