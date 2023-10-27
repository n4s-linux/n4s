<?php

function get_func($account,$bal,$amount) {
        require_once("/svn/svnroot/Applications/fzf.php");
        $func = fzf("Ingen\nu\ni\niy\niv\nrep\n", "Vælg funktion (moms)", "--border=sharp");
        if ($func == "Ingen")
                return "";
        else
                return ($func);
}

?>
