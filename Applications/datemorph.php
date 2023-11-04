<?php 
function datemorphology($darray) {                                                                                                                                                                                                                 
        if (!isset($darray['Description']))
                return $darray;
        if (!isset($darray['Date']))
                return $darray;
        if (stristr($darray['Description'],"#igår")) {
                $curdate = strtotime($darray['Date']);
                $yesterday = date("Y-m-d",strtotime("-1 days",$curdate));
                $darray['Date'] = $yesterday;
                $darray['Description'] .= " (added day after)";
        }   
        return $darray;

}
?>
