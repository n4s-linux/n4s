<?php
$changed_trans = array();
$changed_account = array();
$kk_sortmode = "desc";
$kk_sorting = "Dato";
	$qw_lc = array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m');
	$qw_hc = array('Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M');
function shortcut($y) {
	global $qw_lc;
	global $qw_hc;
	if ($y < 27)
		return $qw_lc[$y-1];
	else if ($y< 52)
		return ($qw_hc[$y-26]);
	else
		return "";
}
function minus_1_dag($x) {
	$date = strtotime ("$x -1 day");
	return date("Y-m-d",$date);
}
function sortbyacc($a,$b) {
	return $a["nr"] > $b["nr"];
}
function slice_sum($slice) {
	$retval = 0;
	foreach ($slice as $s) 
		$retval += $s["Beløb"];
	return $retval; 
}
function s_Dato($a,$b) {
	global $kk_sortmode;
	if ($kk_sortmode == "desc")
		return strtotime($a['Dato']) < strtotime($b['Dato']);
	else
		return strtotime($a['Dato']) > strtotime($b['Dato']);
}
function s_Belob($a,$b) {
	global $kk_sortmode;
	if ($kk_sortmode == "desc")
		return floatval($a['Beløb']) < floatval($b['Beløb']);
	else
		return floatval($a['Beløb']) > floatval($b['Beløb']);
}
function kontokort_form($konto,$primo,$transaktioner,$select) {
	global $kk_sorting;
	global $kk_sortmode;
	global $qw_lc;
	global $qw_hc;
	beginning:
	global $changed_trans;
	$retval = null;
	$ncurse = ncurses_init();
	ncurses_clear();
	global $begin;
	global $data;
	$win = ncurses_newwin(0, 0, 0, 0);
	ncurses_wborder($win,0,0, 0,0, 0,0, 0,0);
	$current_index = -1;
        $max_y = 0;$max_x = 0;
        ncurses_getmaxyx($win,$max_y,$max_x);
        $max_y -= 3;
	if ($max_y > 51) $max_y = 51;
	$pg = 0;
	file_put_contents("/svn/svnroot/tmp/trans.json",json_encode($transaktioner, JSON_PRETTY_PRINT));
	$transaktioner=array_merge(array(array('Func'=>"",'Dato'=>minus_1_dag($begin),"Bilagsnr"=>"9999","Tekst"=>"Overført fra tidl. periode","Beløb"=>$primo)),$transaktioner);
	$pressed = null;
	while (chr($pressed) != "q") {
		ncurses_clear();
		$saldo = 0;
               	if ($pg < 0) $pg = 0;
               	if ($select < 0) {
			if ($pg > 0) { $pg--;$select = $max_y-1;}
			else
				$select = 0;
		}
		if ($select > $max_y -1) {$pg++; $select = 0;}
		//ncurses_mvaddstr(0,70,"$pg / $select");
               	ncurses_refresh();// paint both window
               	$cur_y = 1;
               	if ($pg == 0) $slice_interval = 0; else $slice_interval = (($pg+1) * $max_y) - $max_y;
		$overfort = 0;
		for ($z = 0;$z < $pg ;$z++) {
			//ncurses_mvaddstr(16,70,"yes $pg");
			$temp_slice = array_slice($transaktioner,($z)*$max_y,$max_y);
			$overfort += slice_sum($temp_slice);
		}
		//ncurses_mvaddstr(5,70,danish_number($overfort));
	ncurses_mvaddstr(0,0,"Dato");
	ncurses_mvaddstr(0,12,"Bilag");
	ncurses_mvaddstr(0,20,"Tekst");
	ncurses_mvaddstr(0,39,"Beløb");
	ncurses_mvaddstr(0,53,"Func");
	ncurses_mvaddstr(0,66,"Saldo");
	ncurses_attron(NCURSES_A_BOLD);
	ncurses_mvaddstr(0,77,"Shortcut");
	ncurses_attroff(NCURSES_A_BOLD);
		$slice = array_slice($transaktioner,$slice_interval,$max_y);
		if ($overfort != 0)
			$slice=array_merge(array(array('Func'=>"",'Dato'=>$begin,"Bilagsnr"=>"9999","Tekst"=>"Overført fra forrige side","Beløb"=>$overfort)),$slice);
		$i = -1;
		$curtrans = array();
		ncurses_mvaddstr(0,88,"s".$pg);
		if ($pg > 0 && count($slice) < 2) {$pg -=1;continue;}
		foreach ($slice as $t) {
			$i++;
			if ($select == $i) {
				ncurses_attron(NCURSES_A_UNDERLINE);
				ncurses_attron(NCURSES_A_BOLD);
				$curtrans = $t;
			}
			ncurses_mvaddstr($cur_y,0,$t["Dato"]);
			ncurses_mvaddstr($cur_y,12,$t["Bilagsnr"]);
			ncurses_mvaddstr($cur_y,20,$t["Tekst"]);
			ncurses_mvaddstr($cur_y,39,danish_number($t["Beløb"]));
			ncurses_mvaddstr($cur_y,53,$t["Func"]);
			$saldo += floatval($t["Beløb"]);
			ncurses_mvaddstr($cur_y,63,danish_number($saldo));
			ncurses_attron(NCURSES_A_BOLD);
			ncurses_mvaddstr($cur_y,77,shortcut($cur_y));
			ncurses_attroff(NCURSES_A_BOLD);
			if (isset($t['id']) && in_array($t['id'],$changed_trans)) {
				ncurses_mvaddstr($cur_y,70,"*ÆNDRET");
			}
			$cur_y++;
			if ($select == $i) { ncurses_attroff(NCURSES_A_BOLD); ncurses_attroff(NCURSES_A_UNDERLINE);}
		}
		/*for ($i = $i+1;$i<$max_y;$i++) {
			ncurses_mvaddstr($cur_y++,0,str_repeat(" ",$max_x));
		}*/
		ncurses_mvaddstr($max_y +2,0,"Piletaster (naviger), S (sortering), s (rækkefølge) ENTER/BACKSPACE (sideskift), + for zoom (valgt transaktion))");
		$pressed = ncurses_getch();
		file_put_contents("/svn/svnroot/tmp/pressed",$pressed);		
		if ($pressed == NCURSES_KEY_DOWN) $select++;
                else if ($pressed == NCURSES_KEY_UP) $select--;
                else if ($pressed == NCURSES_ENTER_KEY) { $pg++; ncurses_clear();$select = 0;}
                else if ($pressed == NCURSES_BACKSPACE_KEY) { $pg--; ncurses_clear();$select = 0;}
		else if (!is_numeric(chr($pressed))  && (preg_match("/[A-Z]/",chr($pressed)) || preg_match("/[a-z]/",chr($pressed)))) {
			if(preg_match('/[A-Z]/',chr($pressed))) 
					$select = strpos(implode($qw_hc),chr($pressed))+25;
			else
					$select = strpos(implode($qw_lc),chr($pressed));
		}
		else if ($pressed == 27) { // ESCAPE_KEY 
			ncurses_end();return;
		}
                else {
                        switch (chr($pressed)) {
				case "S":
					ncurses_end();
					$fields = array("Dato","Belob");
					print_r($fields);
					$kk_sorting = $fields[console_input("Vælg en sorterings-kolonne")];
					usort($transaktioner,"s_".$kk_sorting);
					$pg = 0;
					$select = 0;
					break;
				case "s":
					if ($kk_sortmode == "desc") $kk_sortmode = "asc"; else $kk_sortmode = "desc";
					usort($transaktioner,"s_".$kk_sorting);
					$pg = 0;
					$select = 0;
					//kontokort_form($konto,$primo,$transaktioner,$select+1);
					break;
				case "+":
					ncurses_clear();
					$transen = find_trans($curtrans);
					validate(true);save(true,false,false);
					kontokort($konto,$select);ncurses_clear();return;
					/*
					if (isset($transen['update']) && $transen['update'] == 1) {
						$retval = "repeat";
						file_put_contents("/svn/svnroot/tmp/data.json",json_encode($data,JSON_PRETTY_PRINT));
						file_put_contents("/svn/svnroot/tmp/recalc.json",json_encode($transaktioner,JSON_PRETTY_PRINT));
						ncurses_clear();validate(true);save(true,false,false);
						array_push($changed_trans,$transen['id']);
						global $changed_account;
						if (isset($transen['Konto']) && strlen($transen['Konto']))
							array_push($changed_account,$transen['Konto']);
						if (isset($transen['Modkonto']) && strlen($transen['Modkonto']))
							array_push($changed_account,$transen['Modkonto']);
					} 
					break;
                                case "q":
                                        ncurses_end();
                                        return;
                                case "j":
                                        $select++;
                                        break;
				case "J";
					$select += 5;
					break;
                                case "k":
                                        $select--;
                                        break;
				case "K":
					$select-=5;
					break;*/

                        }
                }
	}
	ncurses_clear();
	ncurses_end();
	return $retval;
}

function balance_form($balance = null,$summer = null) {
	beginning:
	$exit = false;
	$current_index = -1;
	$ncurse = ncurses_init();
	ncurses_clear();
	$win = ncurses_newwin(0, 0, 0, 0);
	ncurses_wborder($win,0,0, 0,0, 0,0, 0,0);
	$max_y = 0;$max_x = 0;
	ncurses_getmaxyx($win,$max_y,$max_x);
	$max_y -= 3;
	$select = -1;
	$edit_mode = "";
	$curamount = "";
	$curvat = "";
	$cur_key = "";
	$pg = 0;
	while (!$exit) {
	$call = balfunc();
	$balance = $call['bal'];
	$summer = $call['sum'];
	global $data;
	global $changed_trans;
	static $acc_buffer = array();
	static $value_buffer = array();
	global $qw_lc;
	global $qw_hc;
	$changed_trans = array();
	//$changed_account = array();
	global $kontoplan;
	$kp = $kontoplan['kontoplan'];
	$ks = $kontoplan['Sumfra'];
	if (!defined("ESCAPE_KEY")) {
		define("ESCAPE_KEY", 27);
		define("BACKSPACE_KEY",263);
		define("ENTER_KEY",13);
		define("DELETE_KEY",330);
		define("KEY_DOWN",258);
		define("KEY_UP",259);
	}
	$bal = array();
	$maxaccwidth = 0;
	$sumz = array();
	foreach ($summer as $key => $val) {
		$val['nr'] = $key;
		$val["type"] = "sum";
		array_push($sumz,$key);
		array_push($bal,$val);
		if (strlen($kp[$val["nr"]]) > $maxaccwidth) $maxaccwidth = strlen($kp[$val["nr"]]);
	}
	foreach ($balance as $key => $val) {
		$val['nr'] = $key;
		$val["type"] = "acc";
		array_push($bal,$val);
		if (strlen($kp[$val["nr"]]) > $maxaccwidth) $maxaccwidth = strlen($kp[$val["nr"]]);
	}
	$maxaccwidth += 8;
	usort($bal,"sortbyacc");
	//$exit = false;
	//$current_index = -1;
	ncurses_mvaddstr(0,0,"Nr");
	ncurses_mvaddstr(0,9,"Konto");
	ncurses_mvaddstr(0,$maxaccwidth+1,"Primo");
	ncurses_mvaddstr(0,$maxaccwidth+15,"Periode");
	ncurses_mvaddstr(0,$maxaccwidth+30,"Ultimo");
	ncurses_attron(NCURSES_A_BOLD);
	ncurses_mvaddstr(0,$maxaccwidth+85,"Genvej");
	ncurses_attroff(NCURSES_A_BOLD);
	//while (!$exit) {
		if ($pg < 0) $pg = 0;
		if ($select < 0) $select = 0;
		ncurses_refresh();// paint both window
	ncurses_mvaddstr($max_y -1,0,"Piletaster (naviger) (eller brug genvej), Enter/Backspace sideskift, + for zoom (valgt konto), . for sync");
	$x = 0;
	$balance = 0;
	foreach ($acc_buffer as $key =>$val) {
		$v = trim(danish_number($value_buffer[$key]['Beløb']));
		$balance += $value_buffer[$key]['Beløb'];
		if (strlen($value_buffer[$key]['Momskode']))
			$v .= "[" . $value_buffer[$key]['Momskode'] . "]";
		ncurses_mvaddstr($max_y,$x,"$key => $val ($v)");
		$x += 23;
	}
	if ($balance == 0) {
		if (count($acc_buffer) == 2) {
			ncurses_end();
			$new_t['Bilagsnr'] = console_input("Bilagsnr");
			$new_t["Dato"] = console_input("Dato");
			$new_t["Tekst"] = console_input("Indtast tekst");
			ncurses_init();
			$i = 0;
			foreach ($acc_buffer as $key=> $val) {
				if ($i == 0) {
					$new_t['Konto'] = $acc_buffer[$key];
					$new_t['Beløb'] = $value_buffer[$key]['Beløb'];
					$new_t['F1'] = $value_buffer[$key]['Momskode'];
					$i++;
				}
				else {
					$new_t["Modkonto"] = $acc_buffer[$key];
					$new_t["F2"] = $value_buffer[$key]["Momskode"];
					$new_t["update"] = 1;
				}
				
			}	
			ncurses_end();
			array_push($data,$new_t);
			$acc_buffer = array();
			$value_buffer = array();
			$balance = 0;
			save(true);
			goto beginning;
		}
	}
	ncurses_attron(NCURSES_A_REVERSE);
	$curvalue = ($edit_mode == "Beløb") ? $curamount : $curvat;
	ncurses_mvaddstr($max_y,$x,str_pad("Balance: $balance, $edit_mode: $curvalue",40));
	ncurses_attroff(NCURSES_A_REVERSE);
	ncurses_mvaddstr(0,0,"Nr");
	ncurses_mvaddstr(0,9,"Konto");
	ncurses_mvaddstr(0,$maxaccwidth+1,"Primo");
	ncurses_mvaddstr(0,$maxaccwidth+15,"Periode");
	ncurses_mvaddstr(0,$maxaccwidth+30,"Ultimo");
		$cur_y = 1;
		if ($pg == 0) $slice_interval = 0; else $slice_interval = (($pg+1) * $max_y) - $max_y;
		file_put_contents("/svn/svnroot/tmp/maxy",$max_y);
		$slice = array_slice($bal,$slice_interval,$max_y);
		if ($select > count($slice) +1)
			$select -= 2;
		file_put_contents("/svn/svnroot/tmp/pg",$pg);
		file_put_contents("/svn/svnroot/tmp/int",$slice_interval);
		file_put_contents("/svn/svnroot/tmp/slice",json_encode($slice,JSON_PRETTY_PRINT));
		$i = -1;
		foreach ($slice as $kontobal) {
			$i++;
			if ($select == $i) {
				ncurses_attron(NCURSES_A_UNDERLINE);
				$cur_acc = $kontobal['nr'];
			}
			if ($kontobal["type"] == "sum") {
				ncurses_attron(NCURSES_A_STANDOUT);
			}
			ncurses_mvaddstr($cur_y,9,$kp[$kontobal['nr']]);
			ncurses_mvaddstr($cur_y,2,$kontobal['nr']);	
			ncurses_attron(NCURSES_A_BOLD);
			ncurses_mvaddstr($cur_y,0,shortcut($cur_y));
			ncurses_attroff(NCURSES_A_BOLD);
			if (!isset($kontobal["Periode"])) $kontobal["Periode"] = 0;
			ncurses_mvaddstr($cur_y,$maxaccwidth+1,danish_number($kontobal['Primo']));
			ncurses_mvaddstr($cur_y,$maxaccwidth+15,danish_number($kontobal['Periode']));
			ncurses_mvaddstr($cur_y++,$maxaccwidth+30,danish_number($kontobal['Saldo']));
			global $changed_account;
			if (in_array($kp[$kontobal['nr']],$changed_account)) {
				ncurses_mvaddstr($cur_y,90,"*ÆNDRET");
			}
			file_put_contents("/svn/svnroot/tmp/changed.json",json_encode($changed_account));
			if ($kontobal["type"] == "sum") ncurses_attroff(NCURSES_A_STANDOUT);	
			if ($select == $i) ncurses_attroff(NCURSES_A_UNDERLINE);
		}
		/*for ($i = $i+1;$i<$max_y;$i++) {
			ncurses_mvaddstr($cur_y++,0,str_repeat(" ",$max_x));
		}*/
		$pressed = ncurses_getch();
		file_put_contents("/svn/svnroot/tmp/pressed",$pressed);
		file_put_contents("/svn/svnroot/tmp/select",$select . " ..." . $cur_y);
		if ($edit_mode == "Momskode") {
			if ($pressed != ENTER_KEY) {
				$curvat.= chr($pressed);
			}
			else {
				$edit_mode = "";
			$acc_buffer[$cur_key] = $cur_acc;	
			ncurses_end();
			ncurses_clear();
			$value_buffer[$cur_key]['Beløb'] = str_replace(",",".",$curamount);
			$curamount = "";
			if ($value_buffer[$cur_key]['Beløb'] == ".")
				$value_buffer[$cur_key]['Beløb'] = -$balance;
				$value_buffer[$cur_key]['Momskode'] = $curvat;

			$curvat = "";
			ncurses_init();



		}
		}
		else if ($edit_mode == "Beløb") {
			if ($pressed != ENTER_KEY) {
				$curamount .= chr($pressed);
			}
			else {
				$edit_mode = "Momskode";
			}
		}
		else if ($pressed == NCURSES_KEY_DOWN) $select++;
		else if ($pressed == NCURSES_KEY_UP) $select--;
		else if ($pressed == NCURSES_ENTER_KEY)	$pg++;
		else if ($pressed == NCURSES_BACKSPACE_KEY) $pg--;
		else if (is_numeric(chr($pressed)) && $edit_mode == "") { // ENTER EDIT MODE BELØB
			$edit_mode = "Beløb";
			$cur_key = chr($pressed);
		}
		else if (!is_numeric(chr($pressed))  && (preg_match("/[A-Z]/",chr($pressed)) || preg_match("/[a-z]/",chr($pressed)))) { // TRYK AF GENVEJSTAST
			if(preg_match('/[A-Z]/',chr($pressed))) 
					$select = strpos(implode($qw_hc),chr($pressed))+25;
			else
					$select = strpos(implode($qw_lc),chr($pressed));
		}
		else if ($pressed == ESCAPE_KEY) {
			ncurses_end(); return;
		}
		else if ($pressed == 16) { // Kontrol + p
					balance("bal");
					require_once("/svn/svnroot/Applications/proc_open.php");
					exec_app("((html2ps /svn/svnroot/tmp/balance.html -o > /svn/svnroot/tmp/balance.ps;ps2pdf /svn/svnroot/tmp/balance.ps /svn/svnroot/tmp/balance.pdf;firefox -new-tab /svn/svnroot/tmp/balance.html) 2>/dev/null)&");
		}
		else {
			switch (chr($pressed)) {
				case "q":
					ncurses_end();
					return;
				case "+": 
					ncurses_clear();
					/*if (balance("kk",$cur_acc) == "repeat")
						goto beginning;
					*/
					kontokort($cur_acc);
					ncurses_clear();
					break;
					break;
				case "j":
					$select++;
					break;
				case "J":
					$select+=5;
					break;
				case "K":
					$select-=5;
					break;
				case "k":
					$select--;
					break;
				case ".":
					sync(false);
					break;
			}
		}
	}
}
