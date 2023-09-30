<?php
$SVNROOT="/svn/svnroot";
require_once("CLInput.php");
function selectregnskab() {
touch("/home/joo/Regnskabsfil.xml");
return "/home/joo/Regnskabsfil.xml";
global $SVNROOT;
$input = new CLInput('Vælg regnskab', 'Press Ctrl-C to quit');
$i=0;
if ($handle = opendir('/home/joo/Dropbox/XMLVoucher/')) {
    while (false !== ($file = readdir($handle)))
    {
        if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml')
        {
	if ($fie != "template.xml" && $file != ".xml") {
		echo "'$file'\n";
            $thelist[$i] = str_replace(".xml","",$file);
		$i++;
	}
        }
    }
}
    closedir($handle);
	$thelist[$i++] = "Nyt regnskab";
$option = $input->select($thelist,"Vælg et regnskab");
if ($thelist[$option] == "Nyt regnskab") {
	$thelist[$option] = $input->text("Indtast navn på nyt regnskab");
	system("cd $SVNROOT/Applications/xmlvoucher/;cp xmltemplate /home/joo/Dropbox/XMLVoucher/\"$thelist[$option]\".xml");
}
$regnskabsfil = $thelist[$option] . ".xml";
$input->done();
return "/home/joo/Dropbox/XMLVoucher/$regnskabsfil";
}
?>
