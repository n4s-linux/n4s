<?php
$out = file_get_contents("/tmp/out");
$regex = "/.*<a href='\#(.+?)'>.*/";
preg_replace_callback($regex, "call_func_links" , $out);
function call_func_links($matches) {
	$str = $matches[0];
	$acc = $matches[1];
	$md = md5($acc);
	echo str_replace("<a href='#$acc'>","<a href='#$md'>",$str);
}
?>
