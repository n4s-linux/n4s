<?php
$out = file_get_contents("/tmp/out2");
$regex = "/.*<a name='#back-(.+?)'>.*/";
$regex = '/<a name=\'#back-(.+?)\'>/';
preg_replace_callback($regex, "call_func_links" , $out);


function call_func_links($matches) {
global $out;
	$str = $matches[0];
	$acc = $matches[1];
	$md = md5($acc);
	echo str_replace("<a name='#back-$acc'>","<a name='#back-$md'>",$str);
}
?>
