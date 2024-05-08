<?php
$op = exec("whoami");

foreach (getjson($argv[1]) as $curblock) {
	handleblock($curblock);
}



function handleblock($block) {
	if ($block['t'] == "Header") handleheader($block);
	else if ($block['t'] == "CodeBlock") return handlecode($block);
	else if ($block["t"] == "BulletList") return handlebullet($block);
	else return handleunhandled($block);
}
function handlebullet($block) {
	print_r($block);die();
}
function handleunhandled($block) {
	echo "unhandled $block[t]\n";
}
function handlecode($block) {
	$str = "";
	foreach ($block["c"] as $curblock) {
		if (!is_string($curblock)) continue;
		$str .= $curblock . "\n";
	}
	return trim($str);
}
function getheadertext($block) {
	$str = "";
	foreach ($block as $curblock) {
		if ($curblock["t"] == "Str") $str .= $curblock["c"];
		else if ($curblock["t"] == "Space") $str .= " ";
		else {
			echo "unhandled ";
			print_r($curblock);die();
		}
	}
	return $str;
}
function handleheader($block){
return getheadertext($block['c'][2]);
return false;
}
function getjson($fn) {
	global $op;
	$cmd = "pandoc $fn -f markdown -t json -o ~/tmp/markdown.out";
	system($cmd);
	return json_decode(file_get_contents("/home/$op/tmp/markdown.out"),true)['blocks'];
}
?>
