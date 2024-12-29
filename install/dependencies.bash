#!/bin/bash
function exitprg() {
	echo "Error @ Dependencies: $1"
	exit 1
}
ls /svn/svnroot/Libraries/Dependencies_Debian.txt|while read i
do
apt-get y "$i" || exitprg "couldnt install $i"
done
