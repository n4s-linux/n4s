<?php
$periods = array(
	array('begin'=>'2021-01-01','end'=>'2021-02-01'),
	array('begin'=>'2021-02-01','end'=>'2021-03-01')
);
$tpath=getenv("tpath");
foreach ($periods as $period => $parray) {
	system("LEDGER_DEPTH=999 LEDGER_BEGIN=$parray[begin] LEDGER_END=$parray[end] ledger -f \"$tpath/curl\" xml > /tmp/.l.xml");
	$data = simplexml_load_file("/tmp/.l.xml");
	$data = json_decode(json_encode($data,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true);
print_r($data);die();
	foreach ($data['accounts'] as $curacc) {
		print_r($curacc['account']);
die();
	}
}
?>
