#!/bin/bash
STD_IN=$(</dev/stdin)
line=$(echo "$STD_IN"|sed -e 's/^[ \t]*//')
if [ "$1" == "preview" ]; then
	tpath=/data/regnskaber/kalender noend=1 LEDGER_DATE_FORMAT=%Y-%m-%d LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger r "$2"
	#start=$(date -dlast-monday +%Y-%m-%d)
	#next=$(date -dmonday +%Y-%m-%d)
	#tpath=/data/regnskaber/kalender noend=1 LEDGER_DATE_FORMAT=%Y-%m-%d LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger r "$2"
	exit
fi

ht=$(cat ~/regnskaber/kalender/.hashtags_available|fzf --preview-window=bottom --preview="bash /svn/svnroot/Applications/cal.bash preview {}\$")
if [ "$ht" == "" ]; then
	exit
fi
export tmuxline="$line"
caltekst="$ht\t#planlagt $(date) af $(whoami)" tpath=/data/regnskaber/kalender noend=1 LEDGER_DATE_FORMAT=%Y-%m-%d LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger csv Opgaver -D|caltekst="$ht -  #Planlagt $(date) $(whoami)" php /svn/svnroot/Applications/findtime.php
tpath=/data/regnskaber/kalender noend=1 LEDGER_DATE_FORMAT=%Y-%m-%d LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger b
