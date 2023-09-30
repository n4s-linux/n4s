<?php function time2str($ts) {
    if(!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }
    $diff = time() - $ts;
    if($diff == 0) {
        return 'now';
    } elseif($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 60) return 'lige nu';
            if($diff < 120) return '1 minut siden';
            if($diff < 3600) return floor($diff / 60) . ' minutter siden';
            if($diff < 7200) return '1 time siden';
            if($diff < 86400) return floor($diff / 3600) . ' timer siden';
        }
        if($day_diff == 1) { return 'I går'; }
        if($day_diff < 7) { return $day_diff . ' dage siden'; }
	if($day_diff < 60) { 
		if ((date("m",$ts) == date("m")))
			return ceil($day_diff / 7) . ' uger siden'; 
		else {
			if (date("m") == "1" && date("m",$ts) == "12")
				return "sidste måned";
			else if (date("m") == date("m",$ts) +1)
				return "sidste måned";
		}
	}
       	$mnd = array("Januar","Februar","Marts","April","Maj","Juni","Juli","August","September","Oktober","November","December");
	$retval = $mnd[date("n",($ts))-1];
        if (date("Y",$ts) != date("Y"))
		$retval .= " " . date("Y",($ts));
	return $retval;
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 120) { return 'om et minut'; }
            if($diff < 3600) { return 'om ' . floor($diff / 60) . ' minutter'; }
            if($diff < 7200) { return 'om en time'; }
            if($diff < 86400) { return 'om ' . floor($diff / 3600) . ' timer'; }
        }
        if($day_diff == 1) { return 'i morgen'; }
        if($day_diff < 4) { return date('l', $ts); }
        if($day_diff < 7 + (7 - date('w'))) { return 'næste uge'; }
        if(ceil($day_diff / 7) < 4) { return 'om ' . ceil($day_diff / 7) . ' uger'; }
        if(date('n', $ts) == date('n') + 1) { return 'næste måned'; }
	$mnd = array("Januar","Februar","Marts","April","Maj","Juni","Juli","August","September","Oktober","November","December");
	$retval = $mnd[date("n",($ts))-1];
        if (date("Y",$ts) != date("Y"))
		$retval .= " " . date("Y",($ts));
	return $retval ;
    }
}
?>
