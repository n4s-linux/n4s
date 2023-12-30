<?php
function str_file_filter(
       $str,
       $sep = '_',
       $strict = false,
       $trim = 75) {
       $str = strip_tags(htmlspecialchars_decode(strtolower($str))); // lowercase -> decode -> strip tags
       $str = str_replace("%20", ' ', $str); // convert rogue %20 into spaces
       $str = preg_replace("/%[a-z0-9]{1,2}/i", '', $str); // remove hexy things
       $str = str_replace("&nbsp;", ' ', $str); // convert all nbsp into space
       $str = preg_replace("/&#?[a-z0-9]{2,8};/i", '', $str); // remove the other non-tag things
       $str = preg_replace("/\s+/", $sep, $str); // filter multiple spaces
       $str = preg_replace("/\.+/", '.', $str); // filter multiple periods
       $str = preg_replace("/^\.+/", '', $str); // trim leading period
       if ($strict) {
               $str = preg_replace("/([^\w\d\\" . $sep . ".])/", '', $str); // only allow words and digits
       } else {
               $str = preg_replace("/([^\w\d\\" . $sep . "\[\]\(\).])/", '', $str); // allow words, digits, [], and ()
       }
       $str = preg_replace("/\\" . $sep . "+/", $sep, $str); // filter multiple separators
       $str = substr($str, 0, $trim); // trim filename to desired length, note 255 char limit on windows
       $str = str_replace("'","",$str);
               $str = str_replace(")","",$str);
                       $str = str_replace("()","",$str);


       return $str;
}
?>
