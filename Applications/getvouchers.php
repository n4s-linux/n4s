<?php
	require_once("/svn/svnroot/Applications/proc_open.php");
	$op = exec("whoami");
	$tpath = getenv("tpath");
	$ip = exec("ip route get 8.8.8.8 | grep -oP 'src \K[^ ]+'"); // wonder if there is a better way to get an ip - thanks stackoverflowdude
	$cmd = "bilagsviser";
	$date = date("Y-m-d");
	if (!file_exists("/home/$op/tmp/.voucherwarning-$op-$date") && getenv("silent") != "1") {
		exec_app("whiptail --msgbox 'First off, You need xming installed on a windows client.... once that settled, press enter and let me give you the command' 12 65");
		exec_app("whiptail --msgbox 'We Recommend You open either a new terminal split or session and keep this running window as the bilags chooser. Once You are finished with the voucher, please press Alt-v do do the next one' 12 65");
		exec_app("whiptail --msgbox '$cmd' 8 65");
		touch("/home/$op/tmp/.voucherwarning-$op-$date");
	}
	require_once("/svn/svnroot/Applications/proc_open.php");
		if (file_exists("$tpath/.startbilag")) $startbilag = intval(file_get_contents("$tpath/.startbilag"));else $startbilag=8000;
		echo "starting from $startbilag...\n";
	if (exec("whoami") == "joo") {
		if ($tpath == "") die("requires tpath\n");
		$src = getsrc();
		ob_start();
		system("ls \"$src/\"");
		$files = trim(ob_get_clean());
		$files = explode("\n",$files);
		system("mkdir -p $tpath/.newvouchers");
		$next = getnext();
		foreach ($files as $curfile) {
			if (is_dir($curfile)) continue;
			if ($curfile == "") continue;
			system("mv \"$src/$curfile\" \"$tpath/.newvouchers/$next" . "_____$curfile\"");
			$next++;
			file_put_contents("$tpath/.nextvoucher",$next);
		}
	}
	ob_start();
	system("ls $tpath/.newvouchers/|sort -n");
	$files = explode("\n",trim(ob_get_clean()));
	$bilag = array();
	foreach ($files as $curfile) {
		$x = explode("_____",$curfile);
		if (intval($x[0]) < $startbilag) continue;
		$bilag[$x[0]] = $curfile;
	}
	ob_start();
	system("ls $tpath/*.trans");
	$files = explode("\n",trim(ob_get_clean()));
	foreach ($files as $curfile) {
		$j = json_decode(file_get_contents($curfile),true);
		if ($j == null) { fprintf(STDERR,"$j[Filename] is not valid");continue;}
		if (!isset($j["Date"])) {fprint(STDERR,"$j[Filename] has no Date");continue;}
		if (!isset($j["Reference"])) continue;
		$ref = intval($j["Reference"]);
		$delims = array("+",",");
		foreach ($delims as $curdelim) {
			$x = explode($curdelim,$j["Reference"]);
			foreach ($x as $cref) {
				if (isset($bilag[$cref])){ 
					unset($bilag[$cref]);
				}
			}
		}
	}
	$list = "";
	foreach ($bilag as $curkey=>$curbilag) {
		$list .= "$tpath/.newvouchers/$curbilag\n";
	}
	require_once("/svn/svnroot/Applications/fzf.php");
	if (trim($list) == "") die("No vouchers\n");
	if (getenv("silent") != "1") {
		$list = "Include Booked\n" . $list;
		$bilag = fzf($list,"Vælg uhåndteret bilag");
		if ($bilag == "Include Booked") {
			exec_app("bash /svn/svnroot/Applications/bilagall.bash");
			die();
		}
		if ($bilag == "") die("No voucher selected\n");
		exec_app("cp \"$bilag\" ~/tmp/preview.pdf");
		exec_app("php /svn/svnroot/Applications/tagun.php \"$bilag\"");
	}
	else {
		file_put_contents("$tpath/.unhandledvouchers",count(explode("\n",$list)));
	}

	
function getsrc() {
	global $tpath;
	if (!file_exists("$tpath/.bilagsrc")) die("requires source in $tpath/.bilagsrc\n");
	else return trim(file_get_contents("$tpath/.bilagsrc"));
}

function getnext() {
	global $tpath;
	if (!file_exists("$tpath/.nextvoucher")) {
		file_put_contents("$tpath/.nextvoucher",8000);return 8000;
	}
	else {
		$cur = intval(file_get_contents("$tpath/.nextvoucher"));
		return $cur;
	}
}
?>
