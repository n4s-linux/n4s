#!/bin/bash
noend=1 LEDGER_DEPTH=5 LEDGER_BEGIN=1970/1/1 LEDGER_END=$(echo -n $(date +%Y-%m-%d -d "tomorrow")) 
php /svn/svnroot/Applications/key.php ledger accounts|while read account
do
	ledger -f $tpath/curl r "$account" --depth=5
	cat /svn/svnroot/Applications/reviewtypes.dat|fzf --height=10
done

