<?php
require_once("parsedown-1.7.4/Parsedown.php");
$Parsedown = new Parsedown();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">


<?php
echo $Parsedown->text(file_get_contents("$argv[1]"));
?>
