<?php
$begin = getenv("LEDGER_BEGIN");
$end = getenv("LEDGER_END");
$period = getenv("LEDGER_PERIOD");
$tpath = getenv("tpath");
$ls = dirToArray("$tpath");
$bn = basename("$tpath");


// UDSKRIFT AF FEJLKONTO
$table = "<b>Manglende bilag kan ej konteres</b><br><mark>Disse poster bedes du hurtigst muligt fremsende bilag og/eller forklaring på.</mark><br>(bilagene afleveres bedst med angivning af beløb og/eller ID)<br>";
$table .= "<table border=1><tr><td>ID</td><td>dato</td><td>tekst</td><td>belob</td><td>kommentar</td>\n";
foreach ($ls as $file) {
	$data = json_decode(file_get_contents("$tpath/$file"),true);
	$date = $data['Date'];
	if ($date < $begin || $date > $end) continue;
	if (has_fejl($data)) {
		$table .= print_trans($data);
	}
}
$table .= "</table><br>";

// UDSKRIFT AF MANGLENDE BILAG
$table .= "<b>Poster er konteret, men har ej et bilagsnummer tilknyttet...</b><br><mark>Dette er acceptabelt for poster der ikke kræver faktura f.eks. bankgebyrer, bankrenter, private hævninger, løbende huslejebetalinger jf. kontrakt m.v. - øvrige poster bedes du hurtigst muligt indsende dokumentation for</mark>";
$table .= "<table border=1><tr><td>ID</td><td>dato</td><td>tekst</td><td>belob</td><td>kommentar</td><td>Konto</td><td>Moms</td><td>Konto</td><td>Moms</td></tr>\n";
foreach ($ls as $file) {
	$data = json_decode(file_get_contents("$tpath/$file"),true);
	$date = $data['Date'];
	if ($date < $begin || $date > $end) continue;
	if (is_sale_or_expense($data) && stristr($data['Reference'],'CSV') && !generally_exempt($data)) {
		$table .= print_trans($data,true);
	}
}
$table .= "</table><br>";

// SIGNATUR
$table .= "<br>Med venlig hilsen<br>Olsens Revision ApS<br>Fortunstræde 1, 2.<br>1065 København K<br>+45 2586 4573<br><br><img src=https://olsensrevision.dk/wp-content/uploads/elementor/thumbs/olsens-onf38ivvwko6z6ujk7ubpjc3t0n8teweq5uwfuvuhs.png>";

// MAIL DET - #ide lav et preview først
mailit($table,"Fejlkonto $bn $begin - $end");
function generally_exempt($data) {
	if (stristr($data['Description'],'Rente')) return true;
	if (stristr($data['Description'],'Gebyr')) return true;
	return false;
}
function is_sale_or_expense($data) {
	foreach ($data['Transactions'] as $curtrans) {
		if (stristr($curtrans['Account'],'Indtægter') || stristr($curtrans['Account'],'Udgifter'))
			return true;
	}
	return false;
}



function mailit($message,$subject,$from="jorgen@olsensrevision.dk")  {
$fromName = 'Olsens Revision ApS'; 
echo "Indtast modtager (blank for olsenit@gmail.com): ";
$fd = fopen("PHP://stdin","r");$str = trim(fgets($fd));fclose($fd);
if ($str == "") $to = "olsenit@gmail.com"; else $to = $str;
 
// Additional headers 
$headers = "MIME-Version: 1.0" . "\r\n"; 
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
$headers .= 'From: '.$fromName.'<'.$from.'>'; 
 
// Send email 
if(mail($to, $subject, $message, $headers)){ 
   echo 'Email has sent successfully.'; 
}else{ 
   echo 'Email sending failed.'; 
}
}
function print_trans($array,$showacc = false) {
	$date = $array['Date'];	
	$desc = $array['Description'];	
	$uid = $array['UID'];	
	$amount = $array['Transactions'][0]['Amount'];
	$comment = $array['Comment'];
	$retval = "<tr><td>$uid</td><td>$date</td><td>$desc</td><td>$amount</td><td>$comment</td>";
	if ($showacc) {
		$retval .= "<td>" . $array['Transactions'][0]['Account'] . "</td><td>" . showmoms($array['Transactions'][0]['Func']) . "</td>";
		$retval .= "<td>" . $array['Transactions'][1]['Account'] . "</td><td>" . showmoms($array['Transactions'][1]['Func']) . "</td>";
	}
	$retval .= "</tr>\n";
	return $retval;
}
function showmoms($moms) {
	if ($moms == "") return "Ingen";
	if ($moms == "i") return "Købsmoms";
	if ($moms == "u") return "Salgsmoms";
	if ($moms == "iv") return "EU Varer";
	if ($moms == "iy") return "EU Ydelser";
	
}
function has_fejl($array) {
	if (!isset($array['Transactions'])) return false;
	foreach ($array['Transactions'] as $curtrans) {
		if (stristr($curtrans['Account'],'fejl'))
			return true;
	}
	return false;
}
function dirToArray($dir) {
   $result = array();
   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) continue;
	if (stristr($value,".trans"))
            $result[] = $value;
         } 

   }


   

   return $result;

}

?>
