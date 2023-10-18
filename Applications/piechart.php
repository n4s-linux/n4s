<?php
require_once ('/svn/svnroot/Libraries/jpgraph-4.4.2/src/jpgraph.php');
require_once ('/svn/svnroot/Libraries/jpgraph-4.4.2/src/jpgraph_pie.php');
require_once ('/svn/svnroot/Libraries/jpgraph-4.4.2/src/jpgraph_pie3d.php');
function pie($piedata,$title) {
	$op = exec("whoami");
	$filename = "/home/$op/tmp/pie.png";
	$graph = new PieGraph(750,450);
	$graph->clearTheme();

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
	return b64img($filename,$title);
}
function b64img($filename,$title) {
$b64 = base64_encode(file_get_contents($filename));
?>
<div>
  <img src="data:image/png;base64, <?=$b64?>" alt="Piechart" />
</div>
<?php
}
?>
