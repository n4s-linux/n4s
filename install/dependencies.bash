#!/bin/bash

function exitprg() {
    echo "Error @ Dependencies: $1"
    exit 1
}

# Read words from the file
cat /svn/svnroot/Libraries/Dependencies_debian.txt | tr ' \n' '\n ' | while read -r i; do
    apt-get -y install "$i" || exitprg "couldn't install $i"
done

