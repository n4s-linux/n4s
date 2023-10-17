<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_pie.php');
require_once ('jpgraph/jpgraph_pie3d.php');
$piedata['Administration'] = 25;
$piedata['Lokaleomkostninger'] = 25;
$piedata['Direkte omkostninger'] = 25;
$piedata['Renteudgifter'] = 25;
$title = "Udgifter efter art";
pie($piedata,"/home/joo/tmp/test.png","tester");
function pie($piedata,$filename,$title) {
	$graph = new PieGraph(600,400);
	$graph->clearTheme();
	$graph->SetShadow();

	$graph->title->Set($title);
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$datapoints = array();
	$legends = array();
	foreach ($piedata as $key => $val) {
		array_push($datapoints,$val);
		array_push($legends,$key);
	}

	$data = $datapoints;
	$p1 = new PiePlot3D($data);
	$p1->SetLegends($legends);

	$p1->SetSize(0.5);
	$p1->SetCenter(0.45);

	$graph->Add($p1);
	$graph->Stroke($filename);
}
?>
