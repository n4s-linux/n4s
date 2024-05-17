<?php
function readonly($fn) {
        global $tpath;
	if (!file_exists("$tpath/$fn")) return false;
        $j = json_decode(file_get_contents("$tpath/$fn"),true);
        if (isset($j["Status"]) && $j["Status"] == "Locked") $rv = true;
        else
                $rv = false;
        return $rv;
}
?>
