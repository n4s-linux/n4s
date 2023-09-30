<?php
function csv_to_array($filename='', $delimiter=';',$assoc = true) {

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
		if ($assoc)
			$data[] = array_combine($header,$row);
		else
			$data[] = $row;
            }
        }
        fclose($handle);
    }
    return array('header'=>$header,'rows'=>$data);
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
