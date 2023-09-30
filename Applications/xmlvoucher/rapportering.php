<?php
function datomatch($start,$stop,$dato) {
	$start = strtotime($start);
	$stop = strtotime($stop);
	$dato = strtotime($dato);
	return ($dato >= $start && $dato <= $stop);
}?>
