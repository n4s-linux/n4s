<?php
function tlasort($a,$b) {
	$a = trim(explode(":",$a)[0]);
	$b = trim(explode(":",$b)[0]);
        $score = array('Indtægter'=>0,'Udgifter'=>1,'Resultatdisponering'=>2,'Aktiver'=>4,'Egenkapital'=>5,'Passiver'=>6,"Fejlkonto"=>10);
	if (isset($score[$a])) $sa = $score[$a]; else $sa = 999;
	if (isset($score[$b])) $sb = $score[$b]; else $sb = 999;
	return ($sa > $sb) ? -1 :1;
}
?>
