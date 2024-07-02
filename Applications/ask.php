<?php

	function askamount($konto,$bal) {
		require_once("/svn/svnroot/Applications/math.php");
		$bal = $bal *-1;
		$rv = getstdin("💵 Amount for $konto ($bal)");
		$rv = str_replace(",",".",$rv); // tillad komma, bliver blot tolket som punktum der er iso locales default decimalseparator
		if ($rv == "") $rv = $bal;
		return evalmath($rv);
	}
	function inputbox($question) {
		$op = system("whoami");
		require_once("/svn/svnroot/Applications/proc_open.php");
		system("tmux popup -E \"whiptail --inputbox \"$question\" 8 40 > ~/tmp/inputbox.out\"");
		return file_get_contents("/home/$op/tmp/inputbox.out");
	}
	function question($question) {
		$fd = fopen("php://stdin","r");
		$retval = "";
		while ($retval == "") {
		echo "$question: ";
			$retval = trim(explode("\n",fgets($fd))[0]);
		}
		fclose($fd);
		return $retval;
	}
	function askref() {
		global $tpath;
		if (file_exists("$tpath/.lastref")) {
			$curref= intval(trim(file_get_contents("$tpath/.lastref")));
			if ($curref> 0)  $curref++;
			else  $curref= "" ;
		}
		else $curref= "";
		if ($curref!= "") {
			$rv = getstdin("📜 Enter reference (. for empty) [$curref]");
		}
		else
			$rv = trim(getstdin("Indtast reference"));
		if ($rv == ".") $rv = "";
		else if ($rv == "") $rv = $curref;
		file_put_contents("$tpath/.lastref",$rv);
		return strval($rv);
	}

	function askdesc($hash) {
		global $tpath;
		if (file_exists("$tpath/.lastdesc_$hash")) {
			$curdesc = file_get_contents("$tpath/.lastdesc_$hash");
		}
		else $curdesc = "";
		if ($curdesc != "") {
			$rv = getstdin("🖎 Enter Description [$curdesc]");
			if (trim($rv) == "") $rv = $curdesc;
		}
		else
			$rv = getstdin("🖎 Enter Description");
		file_put_contents("$tpath/.lastdesc_$hash",$rv);
		return $rv;
	}
	function askdate() {
		global $tpath;
		if (!file_exists("$tpath/.lastdate"))
			$curdate =date("Y-m-d");
		else
			$curdate = file_get_contents("$tpath/.lastdate");
		if (stristr($tpath,"igang")) $curdate = date("Y-m-d");
		$s = getstdin("🗓️ Enter Date [$curdate]");
		$retval = ($s == "") ? $curdate : $s;
		file_put_contents("$tpath/.lastdate",$retval);
		return $retval;
	}
	function getstdin($prompt) {
		echo "$prompt: ";
		$fd = fopen("PHP://stdin","r");
		$s = trim(fgets($fd));
		fclose ($fd);
		return $s;
	}
?>
