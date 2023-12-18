<?php
function rewrite($str) {
        global $tpath;
        if (!file_exists("$tpath/.rewrite.json")) return $str;
        $rewrite = json_decode(file_get_contents("$tpath/.rewrite.json"),true);
	if (!$rewrite) { echo "fefjl i .rewrite.json"; return $str;}
        $s = $str;
        foreach ($rewrite as $search => $replace) {
                $s = str_replace($search,$replace,$s);
        }
        return $s;
}
?>
