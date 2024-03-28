<?php
$ANSI_CODES = array(
        "off"        => 0,
        "bold"       => 1,
        "italic"     => 3,
        "underline"  => 4,
        "blink"      => 5,
        "inverse"    => 7,
        "black"      => 30,
        "red"        => 31,
        "green"      => 32,
        "yellow"     => 33,
        "blue"       => 34,
        "magenta"    => 35,
        "cyan"       => 36,
        "white"      => 37,
        "black_bg"   => 40,
        "red_bg"     => 41,
        "green_bg"   => 42,
        "yellow_bg"  => 43,
        "blue_bg"    => 44,
        "magenta_bg" => 45,
        "cyan_bg"    => 46,
        "white_bg"   => 47
    );
$tpath = getenv("tpath");
function pick() {
	$fzf = "";
	global $ANSI_CODES;
	global $op;
	foreach ($ANSI_CODES as $key => $dontcare) {
		$key = set($key,$key);
		$fzf .= "$key\n";
	}	
	$retval = fzf($fzf,"Vælg farve til de valgte poster --height=25","--tac --ansi");
	return $retval;
}
    function set($str, $color)
    {
$ANSI_CODES = array(
        "off"        => 0,
        "bold"       => 1,
        "italic"     => 3,
        "underline"  => 4,
        "blink"      => 5,
        "inverse"    => 7,                                                                                                                                                                                                                                                                   
        "hidden"     => 8,
        "black"      => 30, 
        "red"        => 31, 
        "green"      => 32, 
        "yellow"     => 33, 
        "blue"       => 34, 
        "magenta"    => 35, 
        "cyan"       => 36, 
        "white"      => 37, 
        "black_bg"   => 40, 
        "red_bg"     => 41, 
        "green_bg"   => 42, 
        "yellow_bg"  => 43, 
        "blue_bg"    => 44, 
        "magenta_bg" => 45, 
        "cyan_bg"    => 46, 
        "white_bg"   => 47
    ); 
	$x = explode(",",$color);
	if (isset($x[1]))
		$color=$x[1];
	else
		$color=$x[0];
        $color_attrs = explode("+", $color);
        $ansi_str = "";
        foreach ($color_attrs as $attr) {
            $ansi_str .= "\033[" . $ANSI_CODES[$attr] . "m";
        }
        $ansi_str .= $str . "\033[" . $ANSI_CODES["off"] . "m";
        return $ansi_str;
    }
function setcolor($fn,$farve) {
	global $tpath;
	global $op;
	if (file_exists("$fn")) {
		$data = file_get_contents("$fn");
		$data = json_decode($data,true);
		$data['color'] = $farve;
		$op = system("whoami");
		array_push($data['History'],array('desc'=>"Ændret farve til $farve",'updatedby'=>$op,'date'=>date("Y-m-d H:m")));
		file_put_contents($fn,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
	}
	else
		echo "$tpath/$fn eksisterer ikke\n";
}
function getbg($fn) {
	global $tpath;
	if (file_exists("$tpath/$fn")) {
		$data = file_get_contents("$tpath/$fn");
		$data = json_decode($data,true);
		if (isset($data['color'])) {
			return $data['color'];
		}
	}
	return "off";
}
