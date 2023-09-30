<?php
function remainder($dividend, $divisor) {
    if ($dividend == 0 || $divisor == 0) return 0;

    $dividend .= '';
    $remainder = 0;
    $division = '';
   
    // negative case
    while ($dividend < 0) {
        $dividend += $divisor;
        if ($dividend >= 0) return $dividend;
    }
   
    // positive case
    while (($remainder.$dividend)*1 > $divisor) {
        // get remainder big enough to divide
        while ($remainder*1 < $divisor) {
            $remainder .= $dividend[0];
            $remainder *= 1;
            $dividend = substr($dividend, 1);
        }
       
        // get highest multiplicator for remainder
        $mult = floor($remainder / $divisor);

        // add multiplicator to division
        $division .= $mult.'';

        // subtract from remainder
        $remainder -= $mult*$divisor;
    }
   
    // add remaining zeros if any, to division
    if (strlen($dividend) > 0 && $dividend*1 == 0) {
        $division .= $dividend;
    }
   
    return $remainder;
}