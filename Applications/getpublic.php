<?php
function getpublic() {
	$dir = "/home/joo/regnskaber/stuff/.tags";
echo hej;
  $cdir = scandir($dir);

   foreach ($cdir as $key => $value)

   {

      if (!in_array($value,array(".","..")))

      {

         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))

         {

            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);

         }

         else

         {

            $result[date("Y-m-d",filemtime($value))][] = array('db'=>'stuff','fn'=>$value);

         } 

      }

   }

   

   return $result;

}

?>

