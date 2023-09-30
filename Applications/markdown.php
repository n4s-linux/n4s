<?php
$op = system("whoami");

foreach (getjson($argv[1]) as $curblock) {
	handleblock($curblock);
}



function handleblock($block) {
	if ($block['t'] == "Header") handleheader($block);
	else if ($block['t'] == "CodeBlock") return handlecode($block);
	else return handleunhandled($block);
}
function handleunhandled($block) {
	echo "unhandled $block[t]\n";
}
function handlecode($block) {
	print_r($block);
}
function handleheader($block){
$return getheadertext($block['c'][2]);
return false;
}
function getjson($fn) {
	global $op;
	$cmd = "pandoc $fn -f markdown -t json -o ~/tmp/markdown.out";
	system($cmd);
	return json_decode(file_get_contents("/home/$op/tmp/markdown.out"),true)['blocks'];
}
?>
