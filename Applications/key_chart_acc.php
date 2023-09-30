<?php
global $path;
	$accounts = array('Fejlkonto'=>array(
	'Uhåndterede debitorbetalinger'=>[],'Uhåndterede kreditorbetalinger'=>[]),
	'Aktiver'=>array(
		'Likvider'=>array('Interbank','Bank'=>[],'Kasse'=>[],'Kort'=>[]),
		'Omsætningsaktiver'=>array('Igangværende arbejder'=>[],'Debitorer'=>[],'Varelager'=>[]),
		'Anlægsaktiver'=>array(
			'Goodwill'=>[],'Andre tilgodehavender'=>array('Huslejedepositum'=>[],'Diverse'=>[]),
			'Driftsmidler'=>
				array('Primo'=>[],'Tilgang'=>[],'Afgang'=>[],'Afskrivninger'=>[]),
			'Indretning'=>
				array('Primo'=>[],'Tilgang'=>[],'Afgang'=>[],'Afskrivninger'=>[])
		)
	)
	,'Passiver'=>array(
		'Moms'=>array('Primo'=>[],'Salgsmoms'=>[],'Købsmoms'=>[],'Momsafregning'=>[]),
		'Ansatte'=>array('Løn'=>[],'Skat'=>[],'Am-bidrag'=>[],'ATP'=>[]),
		'Skattekonto'=>[],
		'Kreditorer'=>array('Div'=>[]),
		'Mellemregning med anpartshaver'=> [],
		'Skattekonto'=>[],
		'Skyldige omkostninger'=> [],
		'Selskabsskat'=>[],
		'Anden gæld'=>[],
		'Ansatte'=>array('Nettoløn'=>[],'A-skat'=>[],'ATP'=>[],'Pension'=>[],'Feriepenge'=>[])
	),'Indtægter'=>array('Salg momspligtigt'=>[],'Salg momsfrit'=>[],'Diverse indtægter'=>[]),
	'Udgifter'=>array(
			'Renteudgifter'=>array('Bank'=>[],'Skat'=>[],'Kreditorer'=>[],'Diverse'=>[]),
										'Lokaleomkostninger'=>array('Husleje'=>[],'El, vand og varme'=>[],'Vedligeholdelse'=>[]),
										'Autodrift'=>array('Brændstof'=>[],'Vægtafgift'=>[],'Vedligeholdelse'=>[],'Forsikring'=>[]),
                  'Direkte omkostninger'=>[],
                   'Personaleomkostninger'=>array('Løn'=>[],'ATP'=>[],'AER'=>[],'Barsel'=>[],'Diverse'=>[]),
                    'Administration'=>array('Ej fradragsberettigede omk','Kontingenter'=>[],'Avishold'=>[],'Kontorartikler'=>[],'Gebyrer'=>[],'Mindre anskaffelser'=>[],'Revisor'=>[],'Advokat'=>[],'Edb'=>[],'Webhosting'=>[],'Telefoni'=>[],'Internet'=>[]),
										'Reklame & Markedsføring'=>array('Rejseomkostninger'=>[],'Repræsentation'=>[],'Annoncer & Reklamer'=>[],'Gaver'=>[],'Diverse'=>[])
                   ),
	'Egenkapital'=>array('Reserve'=>[],'Overført resultat'=>[],'Primo'=>[],'Overført resultat'=>[],'Selskabskapital'=>[],'Årets indskud'=>[],'Overkurs'=>[],'Årets hævninger'=>[],'Mellemregning'=>[],'Øvrige hævninger'=>[])
);
if (getenv("igv") == "1") {
$accounts = array("Igangværende arbejder"=>[],'Udført arbejde'=>[]);	
}
$afile = "$path/aliases";
if (file_exists($afile) && file_get_contents($afile) != false) {
	$aliases = json_decode(file_get_contents("$afile"),true);
	if ($aliases == false)
		die("aliases file has been corrupted, aborting mission\n");
}
else {
	$aliases = array();
	//echo "'$afile' was empty...\n";
}
	
if (!file_exists("$path/chart_of_account")) {
	system("mkdir -p $path/");
	file_put_contents("$path/chart_of_account",json_encode($accounts,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)."\n");
}
else
	$accounts = json_decode(file_get_contents("$path/chart_of_account"),true);
require_once("key.php");
if (isset($data))
	$data = loadall($path);
$cmd = "LEDGER_DEPTH=999 ledger -f \"$path/curl\" accounts > \"$path/.ledger_accounts.txt\"";
system($cmd);
$ledger_accounts = explode("\n",file_get_contents("$path/.ledger_accounts.txt"));
#system("rm \"$path/.ledger_accounts.txt\""); // nok bedre at lade være hvis der køres flere på samme tid
$tree = $accounts;

foreach ($ledger_accounts as $acc) { // here we just combine the chart of accounts with the added accounts :-)
	$parts = explode(":",$acc);
	if ($parts[0] == "") continue;
	if (isset($parts[1]) && $parts[1] != "") if (!isset($tree[$parts[0]][$parts[1]])) $tree[$parts[0]][$parts[1]] = [];
	if (isset($parts[2]) && $parts[2] != "") if (!isset($tree[$parts[0]][$parts[1]][$parts[2]])) $tree[$parts[0]][$parts[1]][$parts[2]] =  [];
	if (isset($parts[3]) && $parts[3] != "") if (!isset($tree[$parts[0]][$parts[1]][$parts[2]][$parts[3]])) $tree[$parts[0]][$parts[1]][$parts[2]][$parts[3]] = [];
	if (isset($parts[4]) && $parts[4] != "") if (!isset($tree[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]])) $tree[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]] =  [];
	if (isset($parts[5]) && $parts[5] != "") if (!isset($tree[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]])) $tree[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] =  [];


}
$accounts = $tree;
file_put_contents("$path/chart_of_account",json_encode($accounts,JSON_PRETTY_PRINT));

?>
