<?php
$op = exec("whoami");
$tpath = getenv("tpath");
$orgtpath = getenv("orgtpath");
echo "t=$tpath ot=$orgtpath\n";
$coc = json_decode(file_get_contents("$orgtpath/chart_of_account"),true)['Indtægter'];
$valg = "";
foreach ($coc as $key=>$val) $valg .= "Indtægter:$key\n";
require_once("/svn/svnroot/Applications/fzf.php");
$konto = fzf($valg,"Vælg en indtægtskonto");
require_once("/svn/svnroot/Applications/get_func.php");
$bal = 0;
$func = get_func($konto,$bal,$bal);
echo "Indtast tekst: ";
$fd = fopen("PHP://stdin","r");
$tekst = trim(fgets($fd));
fclose($fd);
echo "Indtast beløb: ";
$fd = fopen("PHP://stdin","r");
$belob = floatval(trim(fgets($fd)));
fclose($fd);


$data['Description'] = $tekst;
$fn = "il-" . date("s") . ".trans";
$data['Filename'] = $fn;
$data['History'] = array(array('date'=>date("Y-m-d H:%m"),'op'=>$op,'Description'=>'Oprettet fakturalinie'));
$data['Date'] = date("Y-m-d");
$data['UID'] = uniqid();
$data['Reference'] = "MANUEL";
$data['Transactions'][0]['Account'] = $konto;
$data['Transactions'][0]['Func'] = $func;
$data['Transactions'][0]['Amount'] = -$belob;
$data['Transactions'][1]['Account'] = "Fakturalinier:$op";
$data['Transactions'][1]['Func'] = "";
$data['Transactions'][1]['Amount'] = $belob;
file_put_contents("$tpath/$fn",json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
