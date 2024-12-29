#!/bin/bash

function exitprg() {
	d=$(date)
	d=$(echo -ne "$d\t")
    echo "$d error @ Dependencies: $1" >> ~/root/install.log
}

# Read words from the file
cat /svn/svnroot/Libraries/Dependencies_debian.txt | tr ' \n' '\n ' | while read -r i; do
    apt-get -y install "$i" || exitprg "couldn't install $i"
done

