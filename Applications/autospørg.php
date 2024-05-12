<?php
	ob_start();
	system('find /data/regnskaber|grep .trans$');
	$files = explode("\n",trim(ob_get_clean()));
	$ask = array();
	foreach ($files as $curfile) {
		$fgc = file_get_contents($curfile);
		$data = json_decode($fgc,true);
		if ($data == false) continue;
		if (!isset($data["Transactions"])) continue;
		if (!stristr(json_encode($data['Transactions']),"fejl")) continue;
		if (!isset($data["Comment"]) || $data["Comment"] == "") continue;
		$ask[dirname($curfile)][] = $data;
	}
	foreach ($ask as $curcustomer => $questions) {
		$bn = basename($curcustomer);
		$html = "<h3>Fejlkonto $bn</h3><br>\n";
		$html .= "<table border=1><tr><td>Ref</td><td>Date</td><td>Text</td><td>Account</td><td>Contra</td><td>Amount</td><td>Comment</td><td bgcolor=yellow>Answer</td></tr>\n";
		foreach ($questions as $curquestion) {
			if (strtotime($curquestion["Date"]) < strtotime("2023-01-01")) continue; //ignore old postings
			if (!isset($curquestion["Transactions"]) || count($curquestion["Transactions"]) < 1) continue;
			foreach ($curquestion["Transactions"] as $curtrans) {
				if (!stristr($curtrans["Account"],"fejl")) continue;
				$shortacc = end(explode(":",$curtrans["Account"]));
				$pamount = number_format($curtrans["Amount"],2,",",".");
				$shortcontra = getcontra($curquestion);
				$html .= "<tr><td>$curquestion[Reference]&nbsp;</td><td>$curquestion[Date]</td><td>$curquestion[Description]</td><td>$shortacc</td><td>$shortcontra</td><td><p align=right>$pamount</p></td><td>$curquestion[Comment]</td><td bgcolor=yellow>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>\n";
			}
		}
		$html .= "</table>";
		sendmail("joo@0lsen.com","Fejlkonto $bn",$html);
	}
function sendmail($to,$subject,$message) {
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=utf8';
// Additional headers
$headers[] = "To: $to";
$headers[] = 'From: n4s@0lsen.com';
//$headers[] = 'Cc: birthdayarchive@example.com';

// Mail it
mail($to, $subject, $message, implode("\r\n", $headers));
}
function getcontra($trans) {
	$rv = "";
	foreach ($trans["Transactions"] as $curtrans) {
		if (!stristr($curtrans["Account"],"fejl")) $rv .= end(explode(":",$curtrans["Account"])). "\n";
	}
	return trim($rv);
}
?>
