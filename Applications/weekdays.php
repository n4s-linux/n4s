<?php
$year = $argv[1];
$month = $argv[2];
$day_count = $argv[3];
for ($i = 1; $i <= $day_count; $i++) {

        $date = $year.'/'.$month.'/'.$i; //format date
        $get_name = date('l', strtotime($date)); //get week day
        $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

        //if not a weekend add day to array
        if($day_name != 'Sun' && $day_name != 'Sat'){
            $workdays[] = $i;
        }

}
echo count($workdays);
?>
