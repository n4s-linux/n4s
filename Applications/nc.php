<?php
$aktuelkonto = null;
$maxwidth=0;
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
$browsepage = 0;
function browse($konto,$data) {
	global $browsepage;
	ncurses_getmaxyx (STDSCR, $Height, $Width);
	$w = ncurses_newwin($Height -9,$Width/2,5,$Width/2);
	ncurses_wborder($w,0,0,0,0,0,0,0,0);
	while (true) {
		$key = ncurses_wgetch($w);
		break;
	}
	ncurses_werase($w);
}
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
		 //int mvwgetch(WINDOW *win, int y, int x);
		$lastkey = ncurses_wgetch($menu);
		switch ($lastkey) {
			case 67:
				$mode = switchmode($mode,'next');
				break;
			case 68:
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
			case 66:
				$rownum++;break;
			case 65:
				if ($rownum > 1) $rownum--;
				break;
			case 10:
			case 13:
				browse($aktuelkonto,$data);
				break;
		}
	}
}
function switchmode($mode, $valg) {
	$typer = explode(":","Indtægter:Udgifter:Aktiver:Passiver:Egenkapital:Fejlkonto");
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
		if (strtotime($curdata['Date']) < strtotime($begin)) continue;
		if (strtotime($curdata['Date']) > strtotime($end))continue;
			$curtrans = $curdata;
			$acc = $curtrans['Account'];
			$date = $curdata['Date'];
			$amount = floatval($curtrans ['Amount']);
			if (substr($acc,0,strlen($mode.":")) == $mode.":") {
				if (isset($bal[$acc])) $bal[$acc] += $amount;
				else $bal[$acc] = $amount;
			}
	}
	return $bal;
}
function gettotal($mode,$data) {
	global $begin;
	global $end;
	$bal = 0;
	foreach ($data as $curtrans) {
		if (strtotime($curtrans['Date']) < strtotime($begin)) continue;
		if (strtotime($curtrans['Date']) > strtotime($end)) continue;
		if (substr($curtrans['Account'],0,strlen($mode)) == $mode) {
				$bal += floatval($curtrans['Amount']);
		}
	}
	return $bal;
}
function drawmain($main = null,$Width,$Height,$mode='Indtægter',$data) {
	global $rownum;
	global $aktuelkonto;
	global $maxwidth;
	global $menu;
	global $pagenum;
	global $lastmode;
	global $bot;
	if ($main == null) { 
		$main = ncurses_newwin($Height,$Width,5,0);
		ncurses_wborder($main,0,0,0,0,0,0,0,0);
	}

	$bal = getbal($mode,$data);
	ksort($bal);
	$pagesize = $Height - 4;
	$slice = array_slice($bal,$pagenum * $pagesize,$pagesize);
	if (empty($slice)) {
		$pagenum = 0;
		$slice = array_slice($bal,$pagenum*$pagesize,$pagesize,true);
	}
	foreach ($slice as $curslice=>$curbal) {
		if (strlen($curslice) > $maxwidth)
			$maxwidth= strlen($curslice);	
	}
	$count = count($slice);
	if ($rownum > count($slice)) $rownum = count($slice);
	if ($rownum ==0)$rownum=1;
	$y = 1;
	ncurses_wclear($main);
	$pagebal = 0;
	foreach ($slice as $curslice => $curbal) {
		$pagebal += $curbal;
		$orgslice = $curslice;
		$curslice = str_pad(substr($curslice,strlen($mode)+1),55," ");
		if ($y == $rownum) { ncurses_wattron($main,NCURSES_A_STANDOUT); }
		ncurses_mvwaddstr($main,$y,2,str_pad($curslice,$maxwidth," ",STR_PAD_RIGHT));
		ncurses_mvwaddstr($main,$y++,$maxwidth+1,str_pad(number_format($curbal,2),15," ",STR_PAD_LEFT));
		if ($y-1 == $rownum) { ncurses_wattroff($main,NCURSES_A_STANDOUT); }
	}
	$modetotal = gettotal($mode,$data);
	if ($modetotal!= 0) {
		ncurses_wattron($main,NCURSES_A_UNDERLINE);
		ncurses_mvwaddstr($main,$Height-3,2,str_pad("Side total",$maxwidth," ",STR_PAD_RIGHT));
		ncurses_mvwaddstr($main,$Height-3,$maxwidth+1,str_pad(number_format($pagebal,2),15," ",STR_PAD_LEFT));
		ncurses_mvwaddstr($main,$Height-2,2,str_pad("$mode total",$maxwidth," ",STR_PAD_RIGHT));
		ncurses_wattron($main,NCURSES_A_BOLD);
		ncurses_mvwaddstr($main,$Height-2,$maxwidth+1,str_pad(number_format($modetotal,2),15," ",STR_PAD_LEFT));
		ncurses_wattroff($main,NCURSES_A_BOLD);
		ncurses_wattroff($main,NCURSES_A_UNDERLINE);
	}
	ncurses_wborder($main,0,0,0,0,0,0,0,0);
	ncurses_wrefresh($main);

	$lastmode = $mode;
	return $main;
}
function drawtopmenu($win = null,$Width,$mode) {
	$v = getversion();
	global $lastkey;
	global $pagenum;
	global $rownum;
	if ($win == null) $win = ncurses_newwin(5,$Width,0,0);
	global $begin; global $end;
	ncurses_wattron($win,NCURSES_A_BOLD);
	ncurses_mvwaddstr($win,1,1,str_pad("Periode: $begin - $end",$Width-3," ",STR_PAD_LEFT));
	$bn = basename(getenv("tpath"));
	ncurses_mvwaddstr($win,1,1,"n4s v $v - $bn");
	ncurses_mvwaddstr($win,2,$Width-6,"S: " . $pagenum . "   ");
	ncurses_mvwaddstr($win,3,$Width-6,"R: $rownum  ");
	ncurses_mvwaddstr($win,3,1,"$mode             ");
	ncurses_wattroff($win,NCURSES_A_BOLD);
	ncurses_wborder($win,0,0,0,0,0,0,0,0);
	ncurses_wrefresh($win);
	return $win;
}

function drawbotmenu($win = null,$Width,$Height,$mode) {
	global $lastkey;
	global $pagenum;
	global $rownum;
	if ($win == null) $win = ncurses_newwin(4,$Width,$Height-4,0);
	ncurses_mvwaddstr($win,1,1,"PGUP/PGDOWN = Skift side - Venstre/Højre = Skift kontogruppe");
	ncurses_mvwaddstr($win,2,1,"Op/ned = Naviger konti - ENTER = Vis detaljer - ESC = Exit");
	ncurses_wborder($win,0,0,0,0,0,0,0,0);
	ncurses_wrefresh($win);
	return $win;
}
function getversion() {
return exec("cd /svn/svnroot/;echo $(git log |wc -l)/1000|bc -l|perl -pe 's/ ^0+ | 0+$ //xg'");
}
