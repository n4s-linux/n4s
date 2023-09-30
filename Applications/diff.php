<?php 
function diff($new, $old) {
  $tempdir = '/svn/svnroot/tmp/'; // Your favourite temporary directory
  $oldfile = tempnam($tempdir,'OLD_');
  $newfile = tempnam($tempdir,'NEW_');
  file_put_contents($oldfile,$old);
  file_put_contents($newfile,$new);
  $answer = array();
  $cmd = "diff -u $oldfile $newfile";
  exec($cmd, $answer, $retcode);
  unlink($newfile);
  unlink($oldfile);
  if ($retcode == 0)
	return "Ingen ændringer";
  if ($retcode != 1) {
    throw new Exception('diff failed with return code ' . $retcode);
  }
  if (empty($answer)) {
    return 'Ingen ændringer';
  } else {
    return implode("\n", $answer);
  }
}
?>
