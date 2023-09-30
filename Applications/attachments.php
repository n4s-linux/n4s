<?php
$usr = getenv("RTUSER");
$pass = getenv("RTPASSWORD");
$q = argv[1];
	$query = urlencode("$q");
$rtserver=getenv("RTSERVER");
$data = file_get_contents("$rtserver/rt/REST/1.0/search/ticket?query=$query&user=$usr&pass=$pass&format=l");
$data = explode("\n",$data);
$attachments_array = array();
foreach ($data as $dd) {
	$d = explode("id: ticket/",$dd);
	if (isset($d[1])) {
		$url = "http://$rtserver/rt/REST/1.0/ticket/$d[1]/attachments?user=$usr&pass=$pass&format=l";
		$attachments = file_get_contents($url);
		$attachments = explode("Attachments:",$attachments);
		if (isset($attachments[1])) {
			$attachment = explode("\n",$attachments[1]);
			foreach ($attachment as $curatt) {
				$curatt = trim($curatt);
				$x = explode(":",$curatt);
				if (isset($x[1])) {
					$fn= explode(")",explode("(",$x[1])[1])[0];
					$type = $x[1];
					$excluded_t = array("text/plain","text/html","multipart/alternative");
					$relevant = true;
					foreach ($excluded_t as $exclude) {
						if (stristr($type,$exclude))
							$relevant = false;
					}
					if (stristr($x[1],"Unnamed"))
						$relevant = false;
					if (!$relevant)
						continue;
					$type = utf8_encode($type);
					$type = trim(explode("(",$type)[0]);
					array_push($attachments_array,array('link'=>"http://$rtserver/rt/REST/1.0/attachment/$x[0]/content",'id'=>$x[0],'filename'=>$type,'source'=>"ticket/$d[1]"));
				}
			}
		}
	}
}
print_r($attachments_array);
?>
