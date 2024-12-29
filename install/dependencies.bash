#!/bin/bash
ls /svn/svnroot/Libraries/Dependencies_Debian.txt|while read i
do
apt-get y "$i" || exitprg "couldnt install $i"
done
