#!/bin/bash
if [ -z "$tpath" ] ; then
	echo "tpath ikke sat...";
	exit
fi

pushd .
cd "$tpath";
wc=$(LEDGER_DEPTH=99 php /svn/svnroot/Applications/key.php ledger register $@ |wc -l)
if [ "$wc" = "3" ]; then
	echo "Account empty";
	exit
fi
#LEDGER_DEPTH=99 php /svn/svnroot/Applications/key.php ledger register $@|vi -
#exit
LEDGER_DEPTH=99 php /svn/svnroot/Applications/key.php ledger register $@ > /tmp/currap.html
cat /tmp/currap.html|fzf --reverse --multi
#w3m -dump /tmp/currap.html
#rm /tmp/currap.html


popd 
