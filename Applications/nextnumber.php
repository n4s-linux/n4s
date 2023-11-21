<?php
	function getnextnumber($path) {
		if (!file_exists("$path/.nextnumber")) {
			$nextnumber = 1; file_put_contents("$path/.nextnumber",1); 
		}
		else {
			$nextnumber = trim(file_get_contents("$path/.nextnumber"));
		}
		return $nextnumber;
	}
	function getnextcbnumber($path) {
		if (!file_exists("$path/.nextcbnumber")) {
			$nextnumber = 1; file_put_contents("$path/.nextcbnumber",1); 
		}
		else {
			$nextnumber = trim(file_get_contents("$path/.nextcbnumber"));
		}
		return $nextnumber;
	}
?>
