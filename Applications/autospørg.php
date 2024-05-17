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
		//if (!isset($data["Comment"]) || $data["Comment"] == "") continue;
		$ask[dirname($curfile)][] = $data;
	}
	$url = "https://github.com/n4s-linux/n4s-gratis-regnskab";
	$version = "<a href=$url>n4s" . exec("cd /svn/svnroot/;echo $(git log |wc -l)/1000|bc -l|perl -pe 's/ ^0+ | 0+$ //xg'") . "🐧</a>";
	foreach ($ask as $curcustomer => $questions) {
		$count = 0;
		$bn = basename($curcustomer);
//		$html = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">';
		$html = "<style>"  .file_get_contents("/svn/svnroot/Applications/bs.css") . "</style>";
		$html .= "<h3>Autogenereret Fejlkonto $bn - $version</h3><br>\n";
		$html .= "<table class=table border=1><tr><td>Ref</td><td>Date</td><td>Text</td><td>Account</td><td>Contra</td><td>Amount</td><td>Comment</td><td bgcolor=yellow>Answer</td></tr>\n";
		foreach ($questions as $curquestion) {
			if (strtotime($curquestion["Date"]) < strtotime("2024-01-01")) continue; //ignore old postings
			if (!isset($curquestion["Transactions"]) || count($curquestion["Transactions"]) < 1) continue;
			foreach ($curquestion["Transactions"] as $curtrans) {
				if (!stristr($curtrans["Account"],"fejl")) continue;
				$shortacc = (explode(":",$curtrans["Account"]));
				$shortacc=end($shortacc);
				$pamount = number_format($curtrans["Amount"],2,",",".");
				$shortcontra = getcontra($curquestion);
				if (!isset($curquestion["Comment"]))$curquestion["Comment"] = ""; 
				$html .= "<tr><td>$curquestion[Reference]&nbsp;</td><td>$curquestion[Date]</td><td>$curquestion[Description]</td><td>$shortacc</td><td>$shortcontra</td><td><p align=right>$pamount</p></td><td>$curquestion[Comment]</td><td bgcolor=yellow>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>\n";
				$count++;
			}
		}
		$html .= "</table>";
		if ($count>0) sendmail("joo@0lsen.com","Fejlkonto $bn",$html);
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
		if (!stristr($curtrans["Account"],"fejl")) { $x = explode(":",$curtrans["Account"]);$x = end($x); $rv .= "$x\n";}
	}
	return trim($rv);
}
?>
