<?php
function csv_to_array($filename='', $delimiter=';') {

    ini_set('auto_detect_line_endings',TRUE);
    if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
        {
            if (!$header) {
                $header = $row;
            }
            else {
                if (count($header) > count($row)) {
                    $difference = count($header) - count($row);
                    for ($i = 1; $i <= $difference; $i++) {
                        $row[count($row) + 1] = $delimiter;
                    }
                }
		$data[] = array_combine2($header, $row);
            }
        }
        fclose($handle);
    }
    return $data;
}
        function array_combine2($arr1, $arr2) {
            $count1 = count($arr1);
            $count2 = count($arr2);
            $numofloops = $count2/$count1;
               
            $i = 0;
            while($i < $numofloops){
                $arr3 = array_slice($arr2, $count1*$i, $count1);
                $arr4[] = array_combine($arr1,$arr3);
                $i++;
            }
           
            return $arr4;
        }
