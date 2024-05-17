<?php 
function shortacc($konto) {
        $x = explode(":",$konto) ;
        $retval = "";
	$i = 0;
        foreach ($x as $word) {
		if ($i > 0) $retval .= "⇒";
		$retval .= strtolower(mb_substr($word,0,1));
		$i++;
        }
        return $retval;
}
?>
