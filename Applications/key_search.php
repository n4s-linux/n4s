<?php
require_once("proc_open.php");
$home =getenv("HOME");
exec_app("vim $home/.search.php");
$code = file_get_contents("$home/.search.php");
$dir = getenv("tpath");
   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (!is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
		if (!endsWith($value,".trans")) continue;
		$data = json_decode(file_get_contents("$dir/$value"),true);
		if (isset($data["Transactions"]))
			$t = $data["Transactions"];
		else
			$t = array();
		$desc = $data["Description"];
		$date = $data["Date"];
		$tt = json_encode($t);
		$match = false;
		$c = "if ($code) \$match = true;";
		eval ($c);
		if ($match){
			echo "$dir/$value\n";	
		}
         }
      }
   }
function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}
?>

