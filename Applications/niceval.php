<?php function niceval($a,$html = true) {                                                                                                                                                                                                
        if ($a == "" && $html) return "&nbsp;";
        return str_pad(number_format(floatval($a),2,",","."),15," ",STR_PAD_LEFT);
}
?>
