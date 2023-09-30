<head>
<style>
pre {
    white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word;       /* Internet Explorer 5.5+ */
}
</style></head>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<?php
function footer() {
?><pre>
<hr>
<span style='background-color:yellow'>Overblik eksporteret <?php echo date("Y-m-d H:m");?></p>
Med venlig hilsen
Jørgen Olsen
Tlf. 2586 4573

<b>Olsens Revision ApS</b>
Fortunstræde 1, 2.
1065 København K
Tlf. 2586 4573
CVR 3308 3181</p>
<?php
}
require_once("Parsedown.php");
$pd = new Parsedown();

	$f=$_GET['f'];
	$data = file_get_contents("/data/regnskaber/transactions_crm/.tags/$f");
//	$explosion = explode("cmd:",$data);
	$view = $data;
	$viewx = explode("\n",$view);
	$new = "";
	$fcs = "FCCCCCCCS";
	$fce = "FCSSSSSEEE";
	foreach ($viewx as $curline) {
		if (stristr($curline,"✔")) {
			$contents = trim($curline);
			$new .= str_replace($contents,"$fcs $contents $fce",$curline). "\n";
		}
		else if (stristr($curline,"🙈")) continue; //skip these lines, means internalinfo
		else
			$new .= $curline . "\n";
	}
	$view = $new;
	$view = $pd->text($view);
	$view = str_replace("$fcs","<font color=green>",$view);
	$view = str_replace("$fce","</font>",$view);
	$view = str_replace("Catalin","<p style='background-color:yellow'>Catalin",$view);
	$cmd = "bash /svn/svnroot/Applications/viewtime.bash $_GET[f]";
	$dato = date("Y-m-d");
	echo "<style>@page { margin: 0; }</style><p align=left><table border=0><tr><td><p align=left><img width=125 src=https://olsensrevision.dk/wp-content/uploads/elementor/thumbs/olsens-onf38ivvwko6z6ujk7ubpjc3t0n8teweq5uwfuvuhs.png></p></td></tr></table></p><meta title='Olsens Revision Sagsoverblik' charset=utf8>$view<br>";
		$newexplosion = explode("\n",$cmd);
		foreach ($newexplosion as $runme) {
			if (strlen(trim($runme))) {
				system("$runme");
			}
		}
	footer();
	?>
