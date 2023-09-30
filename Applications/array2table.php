<?php

    function build_table($array){
    // start table
    $html = '<table border=1>';
    // header row
    $html .= '<tr>';
    foreach($array[0] as $key=>$value){
            $html .= '<th>&nbsp;';
		if ($key != "Filename")
		    $html .= htmlspecialchars($key);
	    $html .= '</th>';
        }
    $html .= '</tr>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
            $html .= '<td>&nbsp;';
		if ($key2 != "Filename")
		    $html .= substr(htmlspecialchars($value2),0,15);
            $html .= '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

?>
