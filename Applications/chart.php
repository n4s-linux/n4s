<?php
function pie($data,$title) {
	$labels = array();
	$datapoints = array();
	$i = 0;
	foreach ($data as $label => $val) {
		$labels[] = $label . " - DKK " . number_format($val,0,"",".") ;
		$datapoints[] = abs($val);
	}
	chart(uniqid(),$title,$labels,$datapoints);
}
function loadsome() {
// The problem is that web browser gives as the page loaded before the canvas has finished, To avoid this should generate some additional loads at the end of the HTML code
?>
<script>
if ( location.href.toUpperCase().indexOf("HTTP") != 0) {
 var delay = 1000; // Delaying up load (in milliseconds).
  delay = new Date().getTime() + delay,
  xhttp = new XMLHttpRequest();

 while (new Date().getTime() < delay ) {
  xhttp.open("GET", location.href, true);
  xhttp.send();
 }
}
</script>
<?php
}
function getlabels($labels) {
	$s = "";
	foreach ($labels as $curlabel) {
		$s .= "'$curlabel'" . ",";
	}
	return substr($s,0,-1);
}
function getdatapoints($data,$title) {
	$s = "";
	$s = "datasets: [{\n\tlabel: '$title',\n\tdata: [";
	foreach ($data as $curdata) {
		$s .= "$curdata" . ",";
	}
	$s = substr($s,0,-1) . "],\n\tborderWidth: 1\n\t}]\n\t}";
	return $s;
}
function chart($id,$title,$datalabels,$datapoints,$unit="DKK") {
?>
<span style="width: 2480; height: 3508">
<h2><?=$title;?></h2>
  <canvas id="<?=$id?>"></canvas>
</span>
<p style="page-break-after: always;">&nbsp;</p>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  ctx = document.getElementById('<?=$id?>');

  new Chart(ctx, {
    type: 'pie',
    data: {
	labels : [<?php echo getlabels($datalabels)?>],
	<?php echo getdatapoints($datapoints,"$unit");?>
    ,
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
<?php 
}
