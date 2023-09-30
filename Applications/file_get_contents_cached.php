<?php
function file_get_contents_cached($url,$timeout = 150000,$fn,$debug = false) {
        if (file_exists("/svn/svnroot/tmp/$fn") && time() -filemtime("/svn/svnroot/tmp/$fn") < $timeout) {
                                // if ($debug) echo ("cacher $url\n");
                                return file_get_contents("/svn/svnroot/tmp/$fn");
        }
        else {
                $data = file_get_contents($url);
                file_put_contents("/svn/svnroot/tmp/$fn",$data);
                system("chmod 777 /svn/svnroot/tmp/\"$fn\"");
                // if ($debug) ("henter $url ingen cache\n");
                return $data;
        }
}

?>
