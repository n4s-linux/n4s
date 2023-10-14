<?php
	$tpath=getenv("tpath");
 function prettyname($tpath) {                                                                                                                                        
        require_once("/svn/svnroot/Applications/proc_open.php");
        //$cmd = "touch $tpath/.companyname.txt;vim $tpath/.companyname.txt";
        //exec_app("$cmd");

        return trim(file_get_contents("$tpath/.companyname.txt"));

}
?>
