#!/bin/bash
cd $tpath
ls *.trans|while read i
do
	php /svn/svnroot/Applications/showcomment.php "$i"
done
