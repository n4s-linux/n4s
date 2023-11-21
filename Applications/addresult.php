<?php
	function addresult($x) {
		$income = 0;
		foreach ($x as &$ct) {
			foreach ($ct['Transactions'] as $curtrans) {
				if (substr($curtrans['Account'],0,strlen("Indtægter")) == "Indtægter" || substr($curtrans['Account'],0,strlen("Udgifter")) == "Udgifter") {
					$ct['Transactions'][] = array('Account'=>'Egenkapital:Periodens resultat','Amount'=>$curtrans['Amount']);
					$ct['Transactions'][] = array('Account'=>'Resultatdisponering:Periodens resultat','Amount'=>-$curtrans['Amount']);
				}
			}
		}
		return $x;
	}
?>
