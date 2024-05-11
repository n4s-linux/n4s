<?php
	function addresult($x) {
		$income = 0;
		foreach ($x as $ct) {
			$nt = array();
			foreach ($ct['Transactions'] as $curtrans) {
				if ($curtrans['Amount'] == 0) continue;
				if (substr($curtrans['Account'],0,strlen("Indtægter")) == "Indtægter" || substr($curtrans['Account'],0,strlen("Udgifter")) == "Udgifter") {
					if (empty($nt)) {
						$nt = $ct;
						unset($nt["Transactions"]);
					}
					$nt["Description"] = "🖥️ $nt[Description]";
					$nt['Transactions'][] = array('Account'=>'Egenkapital:Periodens resultat','Amount'=>$curtrans['Amount'],'id'=>"virt");
					$nt['Transactions'][] = array('Account'=>'Resultatdisponering:Periodens resultat','Amount'=>-floatval($curtrans['Amount']),'id'=>"virt");
				}
			}
			if (!empty($nt)) array_push($x,$nt);
		}
		return $x;
	}
?>
