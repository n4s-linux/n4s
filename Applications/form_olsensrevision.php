<?php 
function getlink($fn) {
	global $filez;
    if (isset($filez[$fn]))
    return "<a href=\"$filez[$fn]\">Link</a>";
    else
    return "";
}
function pretty_data($d) {
	global $filez;
	$rv = "tpath=$d[tpath]\n";
    $rv .= "<table border=1><tr><td>Fil</td><td>Spørgsmål</td><td>Svar</td><td>Link</td></tr>";
    foreach ($d['file'] as $curfile => $svar) {
    	$x = explode(":::::",$curfile);
        $fn = trim(str_replace("dotdotdot",".",$x[0]));
        if ($fn != "") $link = getlink($fn); else $link = "";
        $spm = $x[1];
        if ($svar == "" && $link == "") continue;
    	$rv .= "<tr><td>$fn</td><td>$spm</td><td>$svar</td><td>$link</td></tr>\n";
    }
    $rv .= "</table>";
    return $rv;
}
$ip = "https://olsensrevision.dk/uploads";
$fn = time() . ".json";

file_put_contents($fn, $data);


$uploaddir = './uploads';
foreach ($_FILES as $fn => $curfile) {
		$fn = str_replace("dotdotdot",".",$fn);
		if ($curfile['name'] == "") continue;
    	$dest = $uploaddir . "/" . date("Y-m-d") . "_".uniqid() . "_". str_replace(" ", "_",$curfile['name']);
        
        $tmp = $curfile['tmp_name'];
        move_uploaded_file($tmp,$dest);
        
        $filez[$fn] = "$ip/" . (basename("$dest"));
       

}
$data = pretty_data($_POST);
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
mail("olsenit@gmail.com", "Spørgeskema $_POST[tpath]",$data . "\n\n",$headers);

  $files = glob("./uploads/"."*");
  $now   = time();

  foreach ($files as $file) {
    if (is_file($file)) {
      if ($now - filemtime($file) >= 60 * 60 * 24 * 360) { // 2 days
        unlink($file);
      }
    }
  }
  echo "<h1>Tak for dit svar</h1>$data";

?>
