<?php
$data = array("Aktiver:Likvider:Bank"=>5000,"Egenkapital:Overført resultat"=>-5000,"Indtægter:Revi-salg"=>-5000,"Aktiver:Omsætningsaktiver:Debitorer"=>5000);
	// WINDOW *newwin(int nlines, int ncols, int begin_y, int begin_x);
ncurses_init();

ncurses_newwin(24,80,0,0);
//ncurses_noecho();
$char = null;
$row = 0;
$select = 0;
while (true)  {
	$row = 0;
	$i = 0;
	foreach ($data as $key=>$val) {
		if ($i == $select) ncurses_attron(NCURSES_A_UNDERLINE);
		ncurses_mvaddstr($row,0,$key);
		ncurses_mvaddstr($row,40,$val);
		if (!isset($change[$key])) $change[$key] = "0";
		ncurses_mvaddstr($row,60,$change[$key]);
		if ($i == $select) ncurses_attroff(NCURSES_A_UNDERLINE);
		$row++;
		$i++;
	}
	ncurses_mvaddstr(40,50,$char. "       ");
	$char = (ncurses_getch());
	ncurses_mvaddstr(40,50,$char. "       ");
	if ($char == 113 ||$char == 27) exit_curses();
	if ($char == NCURSES_KEY_UP || chr($char) == 'k') $select--;
	if ($char == NCURSES_KEY_DOWN||chr($char)=='j') $select++;
	if (is_numeric(chr($char))) $change[$key] .= chr($char);
	$change[$key] = floatval($change[$key]);

}
ncurses_end();

function getmenu() {
	return "F1 Menu ☰ F2 Journal ☰ F4 Åbn regnskab ☰ F7 Kode";
}
function exit_curses() {
	ncurses_end();die();
}
?>

