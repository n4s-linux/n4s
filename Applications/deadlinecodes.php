<?php global $deadlines;
$deadlines = array(
        'MomsKvartal'=>array(11,2,5,8),
        'MomsHalvår'=>array(2,8),
        'MomsMåned'=>array(1,2,3,4,5,6,7,8,9,10,11,12),
        'ÅrsregnskabEMV' => array(6),
        'Månedsløn' => array(1,2,3,4,5,6,7,8,9,10,11,12),
        'ÅrsregnskabSelskab'=>array(5),
	'LønsumKvartal' => array(12,3,6,9),
	'LønsumÅrlig'=> array(7)
	
);
global $deadlinedesc;
$deadlinedesc = array(
        'MomsKvartal'=>"Kvartalsmoms",
        'MomsHalvår'=>"Halvårsmoms",
        'MomsMåned'=>"Moms månedsvis",
	'ÅrsregnskabEMV' => "Udvidet selvangivelse",
        'Månedsløn' => "Månedsløn",
        'ÅrsregnskabSelskab'=>"Årsrapport og selskabsselvangivelse",
	'LønsumKvartal' => 'Lønsumsafgift medarbejdere',
	'LønsumÅrlig' => 'Lønsumsafgift årlig'
);
for ($i = 1;$i<= 12;$i++) {
        $deadlines["Custom$i"] = $i;
	$deadlinedesc["Custom$i"] = "Øvrige frister måned $i";
}
?>
