<?php
	// preg_split( string $pattern, string $subject, int $limit = -1, int $flags = 0 ): array|false
	$fn = $argv[1];
	$heading = $argv[2];
	$data = file_get_contents("$fn");
	$data = explode("# $heading\n",$data);
	if (isset($data[1])) $data = $data[1];else $data = "";
	$data = explode("\n# ",$data)[0];
	echo "# $heading\n$data\n";

?>
