<?php
$colorsrgb = array(
    "black" => array(0, 0, 0),
    "white" => array(255, 255, 255),
    "red" => array(255, 0, 0),
    "green" => array(0, 255, 0),
    "blue" => array(0, 0, 255),
    "yellow" => array(255, 255, 0),
    "cyan" => array(0, 255, 255),
    "magenta" => array(255, 0, 255),
    "gray" => array(128, 128, 128),
    "orange" => array(255, 165, 0),
    "purple" => array(128, 0, 128),
    "brown" => array(165, 42, 42),
    "pink" => array(255, 192, 203),
    "turquoise" => array(64, 224, 208),
    "lime" => array(0, 255, 0),
    "gold" => array(255, 215, 0),
    "silver" => array(192, 192, 192),
    "maroon" => array(128, 0, 0),
    "olive" => array(128, 128, 0),
    "navy" => array(0, 0, 128),
    "teal" => array(0, 128, 128),
    "indigo" => array(75, 0, 130),
    "violet" => array(238, 130, 238),
    "aquamarine" => array(127, 255, 212),
    "khaki" => array(240, 230, 140),
    "salmon" => array(250, 128, 114),
    "coral" => array(255, 127, 80),
    "orchid" => array(218, 112, 214)
);
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
        "problem"     => 41,
        "done"   => 42,
        "waiting"  => 43,
        "blue_bg"    => 44,
        "magenta_bg" => 45,
        "cyan_bg"    => 46,
        "white_bg"   => 47
    );
$ANSI_CODES = array(
    "off"            => 0,
    "bold"           => 1,
    "italic"         => 3,
    "underline"      => 4,
    "blink"          => 5,
    "inverse"        => 7,
    "black"          => 30,
    "red"            => 31,
    "green"          => 32,
    "yellow"         => 33,
    "blue"           => 34,
    "magenta"        => 35,
    "cyan"           => 36,
    "white"          => 37,
    "bright_black"   => 90,
    "bright_red"     => 91,
    "bright_green"   => 92,
    "bright_yellow"  => 93,
    "bright_blue"    => 94,
    "bright_magenta" => 95,
    "bright_cyan"    => 96,
    "bright_white"   => 97,
    "black_bg"       => 40,
    "red_bg"         => 41,
    "green_bg"       => 42,
    "yellow_bg"      => 43,
    "blue_bg"        => 44,
    "magenta_bg"     => 45,
    "cyan_bg"        => 46,
    "white_bg"       => 47,
    "bright_black_bg"   => 100,
    "bright_red_bg"     => 101,
    "bright_green_bg"   => 102,
    "bright_yellow_bg"  => 103,
    "bright_blue_bg"    => 104,
    "bright_magenta_bg" => 105,
    "bright_cyan_bg"    => 106,
    "bright_white_bg"   => 107
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
global $ANSI_CODES;
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
