<?php
function getfile($txt) {
  # URL that generated this code:
  # http://txt2re.com/index-php.php3?s=Ref%20:%20Faktura_1215.pdf&-8&30&-37&-31&1

  $re1='(Ref)';	# Variable Name 1
  $re2='(.)';	# Any Single Character 1
  $re3='(:)';	# Any Single Character 2
  $re4='( )';	# Any Single Character 3
  $re5='(.*)(([pP][dD][fF])|([jJ][pP][gG]))$';

  if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4.$re5."/is", $txt, $matches))
  {
      $var1=$matches[1][0];
      $c1=$matches[2][0];
      $c2=$matches[3][0];
      $c3=$matches[4][0];
      $file1=$matches[5][0];
	$ext = $matches[6][0];
      return $file1 . "$ext";
  }

  #-----
  # Paste the code into a new php file. Then in Unix:
  # $ php x.php 
  #-----
	return false;
}
?>
