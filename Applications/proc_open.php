<?php

function exec_app($cmd) {
//	require_once("debug.php");
//	debug("Kørt program '$cmd'");
		$descriptors = array(
      		  array('file', '/dev/tty', 'r'),
      		  array('file', '/dev/tty', 'w'),
      		  array('file', '/dev/tty', 'w')
		);

			$process = proc_open($cmd, $descriptors, $pipes);
			proc_close($process);
}
function exec_app_output($cmd) {
	$cwd='/tmp';
	$descriptorspec = array(
	    0 => array("pipe", "r"),
	    1 => array("pipe", "w"),
	    2 => array("file", "/tmp/error-output.txt", "a") );
	$process = proc_open("$cmd", $descriptorspec, $pipes, $cwd);
	$data = stream_get_contents($pipes[1]);
	fclose($pipes[1]);
	return trim($data);
}
?>
