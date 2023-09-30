<?php
$aliases = array(
  'ms'=>
  array('postering'=>'Udgifter:Administration:Mindre anskaffelser',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
  'do'=>
    array('postering'=>'Udgifter:Administration:Webhosting',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
    'ad'=>
    array('postering'=>'Udgifter:Administration:Advokat',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
      'dm'=>
    array('postering'=>'Udgifter:Direkte omkostninger',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
       'ed'=>
    array('postering'=>'Udgifter:Administration:Edb',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
         'om'=>
    array('postering'=>'Indtægter:Revision',
        'moms'=>'Passiver:Skyldig moms:Salgsmoms',
        'balance'=>'Aktiver:Debitorer'
       ),
           'it'=>
    array('postering'=>'Indtægter:Varesalg',
        'moms'=>'Passiver:Skyldig moms:Salgsmoms',
        'balance'=>'Aktiver:Debitorer'
       ),
        'ar'=>
    array('postering'=>'Indtægter:Revision',
        'moms'=>'Passiver:Skyldig moms:Salgsmoms',
        'balance'=>'Aktiver:Igangværende arbejder'
       ),
          'fr'=>
    array('postering'=>'Udgifter:Fragt',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
            'pu'=>
    array('postering'=>'Udgifter:Personaleomkostninger',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
              'an'=>
    array('postering'=>'Udgifter:Salgsomkostninger:Annonce og reklame',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                'ad'=>
    array('postering'=>'Udgifter:Administration:Advokat',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                  'fo'=>
    array('postering'=>'Udgifter:Administration:Forsikring',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                    'ef'=>
    array('postering'=>'Udgifter:Administration:Ej fradragsberettigede omk',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                     'po'=>
    array('postering'=>'Udgifter:Administration:Porto og gebyrer',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                       'te'=>
    array('postering'=>'Udgifter:Administration:Telefoni',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                         'udv'=>
    array('postering'=>'Udgifter:Administration:Egen udvikling',
        'moms'=>'Udgifter:Administration:Egen udvikling',
        'balance'=>'Udgifter:Løn:Reg egen udvikling'
       ),
                           'dp'=>
    array('postering'=>'Udgifter:Løn:Sygedagpenge',
        'moms'=>'Passiver:Skyldig moms:Salgsmoms',
        'balance'=>'Aktiver:Debitorer:Sygedagpenge'
       ),
                             'gu'=>
    array('postering'=>'Udgifter:Brændstof Gulpladebil',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                               'ko'=>
    array('postering'=>'Udgifter:Administration:Kontorartikler',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                                 're'=>
    array('postering'=>'Udgifter:Salgsomkostninger:Repræsentation',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                                   'pr'=>
    array('postering'=>'Passiver:Mellemregning med anpartshaver',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                                     'eg'=>
    array('postering'=>'Udgifter:Lokaleomkostninger:El, varme og gas',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       ),
                                       'sk'=>
    array('postering'=>'Passiver:Skattekontoen',
        'moms'=>'Passiver:Skyldig moms:Købsmoms',
        'balance'=>'Passiver:Kreditorer'
       )
  );
if (isset($argv[1]) && $argv[1] == 'show') {
	if (isset($aliases[$argv[2]]))
		echo json_encode($aliases[$argv[2]]);
	else
		echo json_encode(array('moms'=>"Ukendt moms $argv[2]",'postering'=>"Ukendt postering $argv[2]"));
}
else if (isset($argv[1]) && isset($argv[2]) && isset($aliases[$argv[1]]) && isset($aliases[$argv[1]][$argv[2]])) {
  echo $aliases[$argv[1]][$argv[2]];
}
  else {
    error_reporting(0);
  echo " ;Ukendt alias $argv[1]-$argv[2]\n\tUkendt";
    error_reporting(E_ALL);
  }
?>
