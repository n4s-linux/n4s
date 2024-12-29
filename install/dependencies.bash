#!/bin/bash
function exitprg() {
	echo "Error @ Dependencies: $1"
	exit 1
}
cat /svn/svnroot/Libraries/Dependencies_debian.txt|while read i
do
apt-get -y install "$i" || exitprg "couldnt install $i"
done
