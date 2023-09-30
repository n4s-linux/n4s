<?php
	while ($line = fgetcsv(STDIN,null,"\t","\"","\\")) {
		$kunde = basename(str_replace(":","", $line[0]));
		$tag = str_replace("#","",explode(" ",$line[1])[0]);
		$ng = explode(" ",explode("#ng ",$line[1])[1])[0];
		if (strtotime($ng) < time()) continue;
		$amount = -getamount($kunde,$tag,$ng);
		echo gettransactions($ng,$kunde,$tag,$amount);
	}
	function gettransactions($ng,$kunde,$tag,$amount) {
		if (!stristr($ng,"-07-01"))  {

			if (strtotime($ng) > time())
				return "$ng $kunde $tag\n\tIndtægter:Budgetteret:#$tag  $amount\n\tAktiver:Likvider:RevolutDKK\n\n";
			else
				return "";
		}
		else {
			$retval = "";
			$amount = $amount / 3;
			$y = 0;
			$orgng = $ng;
			for ($i = 4;$i<7;$i++) {
				$y++;
				$ng = str_replace("-07-01","-0".$i."-01",$orgng);
				if (strtotime($ng) > time()) {
					$retval .="$ng $kunde $y/3 $tag\n\tIndtægter:Budgetteret:#$tag  $amount\n\tAktiver:Likvider:RevolutDKK\n\n";
					$mamount = $amount * 0.25;
					$retval .="$ng $kunde $y/3 $tag\n\tPassiver:Moms:Salgsmoms  $mamount\n\tAktiver:Likvider:RevolutDKK\n\n";
				}

			}
			return $retval;
		}
	}
	function getamount($kunde,$tag,$ng) {
		if ($tag == "årsregnskabemv") return 2500;
		else if ($tag == "årsregnskabselskab") return 3500;
		else return 750;
	}
?>
