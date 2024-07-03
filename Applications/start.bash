#!/bin/bash
function selectp() {
	valg=$(echo -e "1d\n1w\n1m\n1q\n1y"|fzf)
	eval "$valg"
}

function 1d() {
       export LEDGER_BEGIN=$(date -d "today" +"%Y-%m-%d")
       export LEDGER_END=$(date -d "tomorrow" +"%Y-%m-%d")
       saveconf
}
function 1m() {
       export LEDGER_BEGIN=$(date -d "-1 month" +"%Y-%m-%d")
       export LEDGER_END=$(date -d "tomorrow" +"%Y-%m-%d")
       saveconf
}
function 1q() {
       export LEDGER_BEGIN=$(date -d "-3 month" +"%Y-%m-%d")
       export LEDGER_END=$(date -d "tomorrow" +"%Y-%m-%d")
       saveconf
}
function 1y() {
       export LEDGER_BEGIN=$(date -d "-1 year" +"%Y-%m-%d")
       export LEDGER_END=$(date -d "tomorrow" +"%Y-%m-%d")
       saveconf
}
source /svn/svnroot/aliases
argument="$1"
if [ "$argument" == "igangv" ]; then
	export nochange=1
	local="Åbn Lokal"
	export noperiod=1
	export LEDGER_BEGIN=1970-01-01;export LEDGER_END=2099-12-31
	if [ "$select_regnskab" == 1 ]; then
		sr
	else
		sr igangv
	fi
	echo ready to enter transaction
	php /svn/svnroot/Applications/newl.php entry
	export LEDGER_ADD_TIME_TO_PAYEE=1
	php /svn/svnroot/Applications/key.php ledger b 

elif [ "$argument" == "mindmap" ]; then
	hmm

elif [ "$argument" == "screenshots" ]; then
	mkdir -p /data/screens
	vi /data/screens/
elif [ "$argument" == "screenshot" ]; then
	mkdir -p /data/screens
	tmux command-prompt -p 'Angiv navn for skærmdump:' -I '/data/screens/' 'capture-pane -S -32768 ; save-buffer %1 ; delete-buffer'
elif [ "$argument" == "finalize" ]; then
	sr
	php /svn/svnroot/Applications/key_book.bash
elif [ "$argument" == "igangvbal" ]; then
	local="Åbn Lokal" sr "igangv"
	1d
	LEDGER_ADDTIME_TO_PAYEE=1 l print -S payee|sed "s,\x1B\[[0-9;]*[a-zA-Z],,g">$tpath/.hl
	hledger-ui -t --all --theme=terminal -f $tpath/.hl 
elif [ "$argument" == "jootidsbal" ]; then
	export LEDGER_BEGIN
	export LEDGER_END
	selectp
	local="Åbn Lokal" sr "igangv"
	l b -S amount >/dev/null
	local="Åbn Lokal" sr "tidsregnskab"
	1d
	LEDGER_ADDTIME_TO_PAYEE=1 l print -S payee|sed "s,\x1B\[[0-9;]*[a-zA-Z],,g">$tpath/.hl
	hledger-ui --theme=terminal -T -f $tpath/.hl --watch --depth=4
elif [ "$argument" == "anyentry" ]; then
	sr
	LEDGER_BEGIN=$(date +%Y-%m-%d --date="-1 year")	php /svn/svnroot/Applications/newl.php entry
elif [ "$argument" == "stuffbal" ]; then
	sr "stuff"
	1d
	l print -S payee>$tpath/.hl
	hledger-ui --theme=terminal -T -f $tpath/.hl
elif [ "$argument" == "hlui" ]; then
	sr
	lp
	hledger-ui --theme=terminal -f curl -T
elif [ "$argument" == "stuff" ]; then
	sr "stuff"
	1d
	l print -S payee>$tpath/.hl
	php /svn/svnroot/Applications/key.php entry
elif [ "$argument" == "watch" ]; then
	sr "stuff"
	clear
	1d
	while true
	do
		clear
		./.watch.bash 
		sleep 360
	done



elif [ "$argument" == "search2" ]; then
	(
	php /svn/svnroot/Applications/newl.php r|grep -v Resultatdisponering|grep -v Egenkapital:Periodens|fzf --tac --ansi --multi|vi -  -c 'if !argc() | qa | endif'
	)

elif [ "$argument" == "search" ]; then
	(
	LEDGER_BEGIN=1970-01-01 LEDGER_END=2099-12-31 ledger -f $2/curl -S payee,date select date,account,amount,payee "where payee=~/$3/" -S date
	)|fzf --header=Opslag
	#tpath="$2" noend=1 LEDGER_NOTTERMINAL=Fejlkonto:NotCalculatedYet LEDGER_END=2099-01-01 LEDGER_BEGIN=$(date +%-%m-%d --date="-1 year") LEDGER_DEPTH=5 php /svn/svnroot/Applications/key.php ledger select date,account,amount,payee "where payee=~/$3/"|grep -v Resultatdi|grep -v Egenkapital:Periodens|fzf --tac
elif [ "$argument" == "tag" ]; then
	pushd .>/dev/null
	db=$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")
	#sr transactions_stuff
	local="Åbn Lokal" sr $db
	cd $tpath
	tag="$2"
	$tpath/.menu.bash tags "$tag"
	popd 2>/dev/null
	stty sane
elif [ "$argument" == "mine" ]; then
	pushd .>/dev/null
	#sr transactions_crm
	db=$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")
	local="Åbn Lokal" sr $db
	cd $tpath
	mineorall
elif [ "$argument" == "grepsearchtag" ]; then
	pushd .>/dev/null
	#sr transactions_crm
	db=$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")
	local="Åbn Lokal" sr $db
	cd $tpath
	grepsearchtag
elif [ "$argument" == "business" ]; then
	pushd .>/dev/null
	#sr transactions_crm
	#cd $tpath
	if [ "$db" == "" ]; then
		db=$(ls -1td ~/regnskaber/*/.tags|sed 's/\/\.tags//g'|while read i; do basename "$i"; done|fzf -1 --header="Vælg database")
		if [ "$?" == 130 ]; then
			exit
		fi
	else
		tpath=$( ls -d ~/regnskaber/"$db")
	fi
	noperiod=1 local="Åbn Lokal" sr "$db"
	notitle=$notitle $tpath/.menu.bash tags "$tag"
	tag="$(cat ~/tmp/lastbn_menu)"
	echo "$tag | $db"  >> ~/tmp/journal_history
	popd 2>/dev/null
	stty sane
elif [ "$argument" == "code" ]; then
	if [ "$2" == "" ]; then
		fn=$(find /svn/svnroot/ -type f|fzf --no-mouse --cycle);
	else
		fn="$2"
	fi
	bn=$(basename "$fn")
	bn=${bn:0:5}
	vim "$fn" -c "set ft=md"
	echo "$fn" > ~/tmp/.editcode
elif [ "$argument" == "shell" ]; then
	bash
elif [ "$argument" == "calshow" ]; then
(
echo KALENDER
echo

LEDGER_PAYEE_WIDTH=50
LEDGER_ACCOUNT_WIDTH=15
	termcmd=terminal tpath=/data/regnskaber/kalender noend=1 LEDGER_BEGIN=$(date +%Y-%m-%d) LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger r Opgaver -S date
echo
)|less
elif [ "$argument" == "regnskab" ]; then
	bash --rcfile <(echo '. ~/.bashrc; local="Åbn lokal" sr')
elif [ "$argument" == "nyregnskab" ]; then
	bash --rcfile <(echo '. ~/.bashrc; local="Åbn lokal" sr NEW')
elif [ "$argument" == "edit" ]; then
	bn="$2"
	fn="$3"
	sr "$bn"
	ss "$fn"
fi
