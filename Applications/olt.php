<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
pre {
    white-space: pre-wrap;
}
</style>
<?php
$path = "/data/ol/t/";
system("ls /data/ol/t/ |grep -v completed|grep -v template > /tmp/tasks");
$tasks = explode("\n",file_get_contents("/tmp/tasks"));
print_r($tasks);
if (!isset($_GET["ref"]))
	die("no customer");
if (!isset($_GET["md5"]))
	die("no hash");
if (md5($_GET["ref"]) != $_GET["md5"])
	die("invalid hash\n");
$sager = array();
$i = 0;
foreach ($tasks as $task) {
		$ref = gettaskfile($task,"1000 - Kunderef");	
		$dl = gettaskfile($task,"9000 - Deadline");
		if ($ref != $_GET["ref"]) continue;
		$cmd = ("(cd $path/$task;ls) > /tmp/filez");
		system($cmd);
		$filez = explode("\n",file_get_contents("/tmp/filez"));
		sort($filez,SORT_NUMERIC);
		$sager[$i]["html"] .= "<table class=\"table-striped\" width=1000 ><tr><td width=200>SagsID</td><td>$task</td></tr>";
		foreach ($filez as $file) {
			if ($file == "") continue;
			$data = file_get_contents("$path/$task/".$file);
			$orgdata = $data;
			$trimdata = trim($data);
			file_put_contents("/tmp/markdown.tmp",$data);
			system("markdown /tmp/markdown.tmp > /tmp/markdown.out");
			$data = trim(file_get_contents("/tmp/markdown.out"));
			unlink("/tmp/markdown.tmp");
			unlink ( "/tmp/markdown.out");
			if ($data == "")
				continue;
			$data = nl2br($data);
			if ($file == "9000 - Deadline") {
				if (strtotime($trimdata) < strtotime("now")) {
					$data = "<font color=red>$data</font>";
				}
				else
					$data = "<font color=green>$data</font>";
			}
			$fs = trim(explode("-",$file)[1]);
			if ($file != "100000 - History" && $file != "1000 - Kunderef") 
				$sager[$i]["html"] .= "<tr><td width=200>$fs</td><td width=600><pre>$data</pre></td></tr>";
			/*else
				$sager[$i]["html"] .= "<tr><td width=200>$file</td><td width=600><div style=\"overflow-y: scroll; height:170px;\" class=form-control><pre>$orgdata</pre></div></td></tr>";
			*/
		}
		$sager[$i]["html"] .= "</table><P style=\"page-break-before: always\">";
		$sager[$i]["deadline"] = $dl;
		$sager[$i]["title"] = gettaskfile($task,"5000 - Vi leverer");
		$sager[$i]["uid"] = md5($sager[$i]["title"]);
		$i++;
}
function dlcmp($a,$b) {
	return strtotime($a["deadline"]) > strtotime($b["deadline"]);

}
usort($sager,"dlcmp");
//echo "<pre>";
/*
foreach ($sager as $sag) {
	print_r($sag["html"]);
}
*/
?>
<ul class="nav nav-tabs" id="myTab" role="tablist">
<?php $y=-1;foreach ($sager as $sag) {$y++;?>
  <li class="nav-item">
    <a class="nav-link <?php if ($y == 0) echo "active"; ?>" id="<?php echo $sag["uid"];?>-tab" data-toggle="tab" href="#<?php echo $sag["uid"];?>" role="tab" aria-controls="<?php echo $sag["uid"];?>" aria-selected="true"><?php echo $sag["title"];?></a>
  </li>
<?php }?>
</ul>
<div class="tab-content" id="myTabContent">
<?php $i = 0;foreach ($sager as $sag) {
	if ($i == 0) $a = " show active"; else $a = "";
?>
  <div class="tab-pane fade<?php echo $a;?>" id="<?php echo $sag["uid"];?>" role="tabpanel" aria-labelledby="<?php echo $sag["uid"];?>-tab"><?php echo $sag["html"];?></div>
<?php $i++;}?>
</div>
<?php





function gettaskfile($taskid, $file) {
	global $path;
	$cmd = ("cd \"$path/$taskid\";cat \"$file\" > /tmp/data");
	system("$cmd");
	$retval = trim(file_get_contents("/tmp/data"));
	unlink("/tmp/data");
	return $retval;
}
?>
