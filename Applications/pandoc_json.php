<?php
$fn = $argv[1];
$op =get_current_user(); 
$cmd = ("pandoc -f markdown \"$fn\" -t json -o ~/tmp/.pandoc.json");
system("$cmd");
$json = json_decode(file_get_contents("/home/$op/tmp/.pandoc.json"),true)['blocks'];
foreach ($json as $block) {
	if ($block['t'] == "Header")  {
		$curheader = processheader($block);
	}
	else if ($block['t'] == "BulletList")
		$data[$curheader] = processbulletlist($block);
	else if ($block['t'] == "CodeBlock" ) 
		$data[$curheader] = processcodeblock($block);
	else if ($block['t'] == "Para" ) 
		$data[$curheader] = processpara($block);
	else
		$data[$curheader] = processother($block);
}
unset($data['Stamdata']);
//unset($data['Bogføringsvejledning']);
//unset($data['Logins']);
insertheader();
bootstrap();
display_data($data);

function bootstrap() {
echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">';
}



function display_data($data) {
	foreach ($data as $header => $data) {
		echo "<h3>$header</h3>$data<br><br>";
	}
}

function processpara($block) {
	$rv = "";
	foreach ($block['c'] as $items) {
		if ($items['t'] == "Str") $rv .= $items['c'];
		else if ($items['t'] == "SoftBreak") $rv .= " ";
		else if ($items['t'] == "Space") $rv .= " ";
		else processother($block);
		//else die("unhandled exception $items[t]\n");
	}	
	return $rv;
}
function processheader($block){
	return ($block['c'][2][0]['c']);
}
function processother($block) {
	echo "other unhandled<pre>\n";
	print_r(json_encode($block));
	echo "</pre>";

}
function processbulletlist($block) {
	foreach ($block['c'] as $item ) {
		$items[] = assemble_words($item);
	}
	$rv = "<table class=table>";
	foreach ($items as $item) {
		$rv .= "<tr>";
		$x = explode(":",$item) ;
		foreach ($x as $curcol) { $curcol = trim($curcol) ; $rv .= "<td>$curcol</td>"; }
		$rv .= "</tr>";
	}
	$rv .= "</table>";
	return $rv;
}
function assemble_words($items) {
	$retval = "";
	foreach ($items[0]['c'] as $item) {
		if ($item['t'] == "Str")
			$retval .= $item['c'];
		else if ($item['t'] == "Space" )
			$retval .= " ";
		else
			$retval .= "\n";
	}
	return $retval;
}
function processcodeblock($block) {
	$rv = "<pre><code>";
	$lines = explode("\n",$block['c'][1]);
	foreach ($lines as $line) {
		$items = explode("✔ @",$line);
		$color = (isset($items[1])) ? "green" : "yellow";
		$rv .= "<font style='background-color:$color'>$items[0]</font><br>";
	}
	$rv .="</code></pre>";
	return nl2br($rv);
}
function insertheader() {
?>
<style>                                                                                                                                                                                                                                                                                                                                               
* {
 font-family: Latin Modern Mono Bold;
}
.logins {
display: none
}
logins {
display: none 
}
@media print{@page {size: landscape}}
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
@media print {
    pre {
        white-space: pre-wrap;
    }   
}

pre code {
  white-space : pre-wrap !important;
}

</style>

<?php
}
?>
