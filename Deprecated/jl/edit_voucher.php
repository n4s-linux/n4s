<?php
function edit_voucher($d,&$update) {
$update = 0;
$original_data = $d;
if (!defined("ESCAPE_KEY")) {
define("ESCAPE_KEY", 27);
define("BACKSPACE_KEY",263);
define("ENTER_KEY",13);
define("DELETE_KEY",330);
}
$ncurse = ncurses_init();
ncurses_clear();
$win = ncurses_newwin(10, 30, 7, 25);
ncurses_wborder($win,0,0, 0,0, 0,0, 0,0);
$exit = false;
$current_index = -1;
while (!$exit) {
ncurses_refresh();// paint both window
ncurses_attron(NCURSES_A_REVERSE);

ncurses_mvaddstr(0,0,"Redigering af bilag");

ncurses_attroff(NCURSES_A_REVERSE);
$y = 0;
$i = 0;
$lock = -1;
foreach ($d as $dat => &$val) {
	if ($dat == "id")
		$lock = $i;
	if ($current_index != -1 && $current_index == $i) ncurses_attron(NCURSES_A_BOLD);
	$y += 2;
	ncurses_mvaddstr($y,0,$i);
	ncurses_mvaddstr($y,5,$dat);
	if ($dat != "id") ncurses_attron(NCURSES_A_UNDERLINE);
	ncurses_mvaddstr($y,20,str_repeat(" ",20));
	ncurses_mvaddstr($y,20,$val);
	if ($dat != "id") ncurses_attroff(NCURSES_A_UNDERLINE);
	if ($current_index != -1 && $current_index == $i) ncurses_attroff(NCURSES_A_BOLD);
	if ($current_index == -1) {
		ncurses_mvaddstr(23,0,"Tryk linienummer for redigering. Aktiv linie fremhæves. ");
		ncurses_mvaddstr(24,0,"Tryk d for discard (forkast ændringer), og w for write and next/exit (skriv)");
	}
	else {
		ncurses_mvaddstr(23,0,str_repeat(" ",60));
		ncurses_mvaddstr(24,0,str_repeat(" ",80));
		ncurses_mvaddstr(24,0,"Du kan nu ændre værdi. Slet feltet indhold på DEL. Tryk ENTER for færdig");
	}
	$i++;

}


$pressed = ncurses_getch();// wait for a user keypress
$char = chr($pressed);
if ($current_index == -1) {
	if (is_numeric($char) && intval($char) <= count($d)) {
		if (intval($char) != $lock)
			$current_index = intval($char);
	}
	else if ($char == "q") {
		ncurses_end();
		$update = 0;
		return $original_data;

	}
	else if ($char == "w") {
		ncurses_end();
		$update = 1;
		return $d;
	}
}
else {
	if ($pressed == BACKSPACE_KEY || $pressed == DELETE_KEY) {
		$i = 0;
		foreach ($d as $data => &$val) {
			if ($i == $current_index) {
				if ($pressed == BACKSPACE_KEY)
					$val = substr($val,0,-1);
				else
					$val = "";
				break;
			}
			$i++;
		
		}
	}
	else if ($pressed == ENTER_KEY || $pressed == ESCAPE_KEY) 
		$current_index = -1;
	else {
		$i = 0;
		foreach ($d as $data => &$val) {
			if ($i == $current_index) {
				$val .= $char;
				break;
			}
			$i++;
		
		}
		$val = preg_replace('/[^(\x20-\x7F)]*/','', $val);
	}

}
}

}

?>
