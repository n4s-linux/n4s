<?php
$op = exec("whoami");
function bootstrap() {
return '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous"><font face=Purisa>';
}
function sortbyimportance($a,$b) {
	return strtotime($a['Date']) > strtotime($b['Date']);
	$amount = abs($a['Transactions'][0]['Amount']);
	$amount2 =abs($b['Transactions'][0]['Amount']);
	return ($amount < $amount2);
}
function hasfejl($d) {
	$t = $d['Transactions'];
	foreach ($t as $curtrans) {
		if (stristr($curtrans['Account'],'fejl')) return true;
	}	
	return false;
}
function getspm($data) {
	unset($data['History']);
	print_r($data);
	echo "Indtast spørgsmål til kunde: ";
	$fd = fopen("php://stdin","r");
	return trim(fgets($fd));
	fclose($fd);
	return "";
}
	function showdata($data) {
		global $tpath;
		if (!isset($data['Spørgsmål'])) {
			$data['Spørgsmål'] = getspm($data);
			array_push($data['History'],array('Dato'=>date("Y-m-d H:m"),'Tekst'=>'Tilføjet spørgsmål ' . $data['Spørgsmål']));
			file_put_contents($tpath."/".$data['Filename'],json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
		}
		$amount= number_format(abs($data['Transactions'][0]['Amount']),2,".",",");
		$r = "<h3>$data[Date] - kr $amount - " . $data['Description'] . "</h3>\n";
		$r .= "\n";
		//$r .= "|Konto|Beløb|Moms|\n|---|--:|--:|\n";
		$r .= "<table class='table-striped' ><tr><td>konto</td><td><p align=right>beløb</p></td><td>moms</td></tr>";
		foreach ($data['Transactions'] as $key) { // foreach transaction
			$r .= "<tr>";
			foreach (array('Account','Amount','Func') as $cur) {
				$kcur = $key[$cur];
				if ($cur == "Amount")
					$kcur = "<p align=right>" . number_format($key[$cur],2,".",",") . "</p>";

				$r .= "<td>$kcur</td>";
			}
			
			$r .= "</tr>\n";
		}
		$r .= "</table>\n";
		$r .= "<table class=table><tr><td>Spørgsmål</td><td>$data[Spørgsmål]</td></tr><tr><td>Svar</td><td><input type=text name=$data[UID]></td></tr></table><p style='page-break-after: always;'>&nbsp;</p>
";
		return $r;
	}
	$tpath=getenv("tpath");
	system("cd $tpath;ls *.trans > ~/tmp/trans.list");
	$data = explode("\n",file_get_contents("/home/$op/tmp/trans.list"));
	foreach ($data as $curfile) {
		if ($curfile == "") continue;
		$d[$curfile] = json_decode(file_get_contents($curfile),true);
	}
	$html = "";
	usort($d,"sortbyimportance");
	foreach ($d as $data) {
		$begin =getenv("LEDGER_BEGIN");$end = getenv("LEDGER_END");
		if (strtotime($data['Date']) > strtotime($end) || strtotime($data['Date']) < strtotime($begin)) continue;
		if ($data['Transactions'][0]['Amount'] == 0) continue;
		if (((isset($data['Comment']) && strlen($data['Comment']) && stristr($data['Comment'],"#"))|| isset($data['Spørgsmål']) && strlen($data['Spørgsmål'])) || hasfejl($data))
			$html .= showdata($data) . "<hr>";
	}
	//$ticket = newticket($data['Description'] . "(" . $data['Date'] . ")" );
	$ticket = 15351;
	$file = "/home/joo/mailspm.html";
	file_put_contents($file,$html);
	$cmd = "rt correspond -e -ct html -a $file $ticket -m 'OlsensRevision - Spørgsmål til Transaktion' < /dev/tty";
require_once("/svn/svnroot/Applications/proc_open.php");
	exec_app("$cmd");
	echo "new ticket $ticket\n";

function newticket($subject) {
	global $op;
	 $cmd = "rt create -t ticket set subject='$subject' add requestor=olsenit@gmail.com |awk '{print $3}'";
	system("$cmd > /home/$op/tmp/newticket.id");
	return intval(file_get_contents("/home/$op/tmp/newticket.id"));
}
?>
