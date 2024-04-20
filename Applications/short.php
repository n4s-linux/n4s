<?php
	function fgc($s) {if (file_exists($s)) return file_get_contents($s);else return "";}
	function eon() { error_reporting(0);};
	function eoff() { error_reporting(E_ALL);};
?>
