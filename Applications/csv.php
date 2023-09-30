<?php
stream_set_blocking(STDIN, 0);
$csv_ar = fgetcsv(STDIN, null,",","\"") or die("lool\n");
print_r($csv_ar);
die();
if (is_array($csv_ar)){
  print "CVS on STDIN\n";
} else {
  print "Look to ARGV for CSV file name.\n";
}
?>
