<?php
function tlasort($a,$b) {
	$a = explode(":",$a)[0];
	$b = explode(":",$b)[0];
        $score = array('Indtægter'=>0,'Udgifter'=>1,'Resultatdisponering'=>2,'Aktiver'=>4,'Egenkapital'=>5,'Passiver'=>6);
        if (isset($score[$a]))                                                                                                                                                                                                                                                                                                                                                                                                             
                $as = $score[$a];
        else
                $as = 7;
        if (isset($score[$b]))
                $bs = $score[$b];
        else
                $bs = 7;
        return ($as > $bs);
}
?>
