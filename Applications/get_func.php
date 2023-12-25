<?php

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
