<?php
$lastmode = null;
$rownum = 1;
setlocale(LC_ALL,"da_DK.UTF-8");
setlocale(LC_ALL, "");
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$lastkey = null;
$menu = null;
$main = null;
$bot = null;
$pagenum = 0;
function ui($data) {
	global $bot;
	global $rownum;
	global $lastkey;
	global $main;
	global $menu;
	global $pagenum;
	ncurses_init();
	ncurses_noecho();
	ncurses_getmaxyx (STDSCR, $Height, $Width);
	global $menu;
	$mode = "Indtægter";
	while (true) {
		$main = drawmain($main,$Width,$Height - 9,$mode,$data);
		$menu = drawtopmenu($menu,$Width,$mode);
		$bot = drawbotmenu($bot,$Width,$Height,$mode);
		$lastkey = ncurses_getch();
		switch ($lastkey) {
			case 261:
				$mode = switchmode($mode,'next');
				break;
			case 260:
				$mode = switchmode($mode,'previous');
				break;
			case 338:
				$pagenum++;
				$rownum = 1;
				break;
			case 339:
				$pagenum--;
				if ($pagenum < 0)$pagenum=0;
				break;
			case 258:
				$rownum++;break;
			case 259:
				if ($rownum > 1) $rownum--;
				break;
		}
	}
}
function switchmode($mode, $valg) {
	$typer = explode(":","Indtægter:Udgifter:Aktiver:Passiver:Fejlkonto");
	$i = 0;
	foreach ($typer as $curtype) {
		if ($curtype == $mode) {
			$index = $i;
		}	
		$i++;
	}
	if ($valg == "next") {
		if (isset($typer[$index +1])) return $typer[$index+1];
		else return $typer[0];
	}
	else if ($valg == "previous") {
		if (isset($typer[$index-1 ])) return $typer[$index-1];
		else return $typer[count($typer)-1];
	}
}
function getbal($mode,$data) {
	global $begin;
	global $end;
	$bal = array();
	foreach ($data as $curdata) {
		foreach ($curdata['Transactions'] as $curtrans) {
			$acc = $curtrans['Account'];
			$date = $curdata['Date'];
			$amount = floatval($curtrans ['Amount']);
			if (substr($acc,0,strlen($mode.":")) == $mode.":") {
				if (isset($bal[$acc])) $bal[$acc] += $amount;
				else $bal[$acc] = $amount;
			}
		}
	}
	return $bal;
}
function gettotal($mode,$data) {
	$bal = 0;
	foreach ($data as $curtrans) {
		foreach ($curtrans['Transactions'] as $ct) {
			if (substr($ct['Account'],0,strlen($mode)) == $mode)
				$bal += $ct['Amount'];
		}
	}
	return $bal;
}
function drawmain($main = null,$Width,$Height,$mode='Indtægter',$data) {
	global $rownum;
	global $menu;
	global $pagenum;
	global $lastmode;
	global $bot;
	if ($main == null) { 
		$main = ncurses_newwin($Height,$Width,5,0);
		ncurses_wborder($main,0,0,0,0,0,0,0,0);
	}

	$bal = getbal($mode,$data);
	$pagesize = $Height - 3;
	$slice = array_slice($bal,$pagenum * $pagesize,$pagesize);
	if (empty($slice)) {
		$pagenum = 0;
		$slice = array_slice($bal,$pagenum*$pagesize,$pagesize,true);
	}
	file_put_contents("/home/joo/tmp/slice",json_encode($slice,JSON_PRETTY_PRINT));
	$maxwidth = 0;
	foreach ($slice as $curslice=>$curbal) {
		if (strlen($curslice) > $maxwidth)
			$maxwidth= strlen($curslice);	
	}
	if ($menu != null)ncurses_mvwaddstr($menu,2,35,"$count    ");
	if ($rownum > count($slice)) $rownum = count($slice);
	$y = 1;
	ncurses_wclear($main);
	$pagebal = 0;
	foreach ($slice as $curslice => $curbal) {
		$pagebal += $curbal;
		$curslice = substr($curslice,strlen($mode)+1);
		if ($y == $rownum) { ncurses_wattron($main,NCURSES_A_STANDOUT); }
		ncurses_mvwaddstr($main,$y,2,str_pad($curslice,$maxwidth," ",STR_PAD_RIGHT));
		ncurses_mvwaddstr($main,$y++,$maxwidth+1,str_pad(number_format($curbal,2),15," ",STR_PAD_LEFT));
		if ($y-1 == $rownum) { ncurses_wattroff($main,NCURSES_A_STANDOUT); }
	}
	ncurses_wattron($main,NCURSES_A_UNDERLINE);
	ncurses_mvwaddstr($main,$Height-3,2,str_pad("Side total",$maxwidth," ",STR_PAD_RIGHT));
	ncurses_mvwaddstr($main,$Height-3,$maxwidth+1,str_pad(number_format($pagebal,2),15," ",STR_PAD_LEFT));
	ncurses_mvwaddstr($main,$Height-2,2,str_pad("$mode total",$maxwidth," ",STR_PAD_RIGHT));
	$modetotal = gettotal($mode,$data);
	ncurses_wattron($main,NCURSES_A_BOLD);
	ncurses_mvwaddstr($main,$Height-2,$maxwidth+1,str_pad(number_format($modetotal,2),15," ",STR_PAD_LEFT));
	ncurses_wattroff($main,NCURSES_A_BOLD);
	ncurses_wattroff($main,NCURSES_A_UNDERLINE);
	ncurses_wborder($main,0,0,0,0,0,0,0,0);
	ncurses_wrefresh($main);
	$lastmode = $mode;
	return $main;
}
function drawtopmenu($win = null,$Width,$mode) {
	global $lastkey;
	global $pagenum;
	global $rownum;
	if ($win == null) $win = ncurses_newwin(5,$Width,0,0);
	global $begin; global $end;
	ncurses_mvwaddstr($win,1,25,"begin: " . $begin);
	ncurses_mvwaddstr($win,1,1,"end: $end");
	ncurses_mvwaddstr($win,2,1,"laskey: $lastkey");
	ncurses_mvwaddstr($win,2,19,"pagenum: $pagenum");
	ncurses_mvwaddstr($win,3,19,"rownum: ". str_pad($rownum,5," "));
	ncurses_wborder($win,0,0,0,0,0,0,0,0);
	ncurses_wrefresh($win);
	return $win;
}

function drawbotmenu($win = null,$Width,$Height,$mode) {
	global $lastkey;
	global $pagenum;
	global $rownum;
	if ($win == null) $win = ncurses_newwin(4,$Width,$Height-4,0);
	ncurses_wborder($win,0,0,0,0,0,0,0,0);
	ncurses_wrefresh($win);
	return $win;
}
