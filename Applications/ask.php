<?php
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
?>
