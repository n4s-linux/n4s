<?php

	require_once("vendor/autoload.php");
	$Parsedown = new Parsedown();
	$public = array(array('db'=>'stuff','tag'=>'bankskifte'));
	require_once("getpublic.php");
	$public = getpublic();
print_r($public);die();
	$posts = array();
	foreach ($public as $curfile) {
		$fn = "/home/joo/regnskaber/$curfile[db]/.tags/$curfile[tag]";
		$mt = filemtime($fn);
		$date = date("Y-m-d",$mt);
		if (!isset($posts[$date])) $posts[$date] = array();
		$txt = file_get_contents($fn);
		$txt = $Parsedown->text($txt);
		array_push($posts[$date],array("date"=>$date,"fn"=>basename($fn),"contents"=>$txt));
	}
	$index = "<table border=1>";
	foreach ($posts as $postdate) {
		foreach ($postdate as $curpost) {
			$link = "site/poster/$curpost[fn].html";
			file_put_contents("/home/joo/tmp/$link",$curpost['contents']);
			$index .= "<tr><td><a href=$link>$curpost[date]</a></td><td>$curpost[fn]</td></tr>";
		}
	}
	$index .= "</table>";
	file_put_contents("/home/joo/tmp/site/index.html",$index);
?>
