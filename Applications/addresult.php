<?php
	function addresult($x) {
		$income = 0;
		foreach ($x as &$ct) {
			foreach ($ct['Transactions'] as $curtrans) {
				if ($curtrans['Amount'] == 0) continue;
				if (substr($curtrans['Account'],0,strlen("Indtægter")) == "Indtægter" || substr($curtrans['Account'],0,strlen("Udgifter")) == "Udgifter") {
					$ct['Transactions'][] = array('Account'=>'Egenkapital:Periodens resultat','Amount'=>$curtrans['Amount'],'id'=>"virt");
					$ct['Transactions'][] = array('Account'=>'Resultatdisponering:Periodens resultat','Amount'=>-floatval($curtrans['Amount']),'id'=>"virt");
				}
			}
		}
		return $x;
	}
?>
