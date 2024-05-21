<?php
if (isset($argv[1]) && $argv[1] == "getfunc") {
	$op = exec("whoami");
	file_put_contents("/home/$op/tmp/getfunc",get_func($argv[2],0,0));
}

function get_func($account,$bal,$amount) {
        require_once("/svn/svnroot/Applications/fzf.php");
        //$func = fzf("Ingen\nu\ni\niy\niv\nrep\n", "Vælg funktion (moms)", "--border=sharp");
        $func = fzf("Ingen\nu\ni\niy-eu\niv-eu\niy-abr\niv-abr\nrep\n", "Vælg funktion (moms)", "--border=sharp");
        if ($func == "Ingen")
                return "";
        else
                return ($func);
}

?>
