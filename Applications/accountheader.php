<?php
function printheader($parameter = "Saldobalance",$orientation="Portrait") {                                                                                                                                                                                                                                                                                                                                                            
global $tpath;
global $begin;
global $end;
global $realend;
?><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<html charset=utf8>
<style type="text/css" media="print">
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
@media print {
<?php if ($parameter == "Saldobalance") {?>
        div {
    break-inside: avoid;
}
        table {
    break-inside: avoid;
        }
<?php }?> 
    table tbody tr td:before,
    table tbody tr td:after {
        content: ""; 
        height: 4px;
        display: block;
    }   
@media print{@page {size: <?php echo $orientation;?>}}
</style>
<?php 

echo "<p align=right>";
echo "København " . date("Y-m-d") . "</p><center><br>";
for ($i = 0;$i<12;$i++)echo "<br>";
require_once("/svn/svnroot/Applications/prettyname.php");
echo "<br><b>$parameter</b><br>" . prettyname($tpath) . "</center>";
echo "<center><br><b>Periode:</b><br>";
$realend = date("Y-m-d", strtotime("$end -1 day"));
echo "$begin - $realend";
echo "</center>";
echo "<p style=\"page-break-after: always;\">&nbsp;</p>";

}
?>
