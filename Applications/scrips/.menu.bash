export session_history="/home/joo/regnskaber/transactions_stuff/.sessions/session_$(echo ${STY#*.})"
php /svn/svnroot/Applications/key.php ledger b > /dev/null&
source /svn/svnroot/Applications/nicebal.bash
#aktivitet=$(head -n1 "$tpath/.doing.txt")
source $tpath/.tags.bash
source /svn/svnroot/Applications/ol.bash
source /svn/svnroot/Applications/vitouch.bash
function regnstyk() {
	uuid=$(uuidgen)
	tmpfn=/tmp/regnstyk_$uuid
	fn="$1"
	(echo -n 0
	sed '/^LOG/q;' $fn|while read line; do echo -n $(echo "$line"|grep -Po "^\+\\d+");done
	sed '/^LOG/q;' $fn|while read line; do echo -n $(echo "$line"|grep -Po "^\-\\d+");done
	echo
	) > $tmpfn
	cat $tmpfn
	cat $tmpfn|bc -l
	rm $tmpfn
}
source /svn/svnroot/Applications/difffile.bash

LINES=$(echo "$(echo -n $(stty size | cut -d" " -f1))"-2|bc)
source /svn/svnroot/Applications/key_tas.bash

#function tas () 
#{ 
#    if [ "$1" == "" ]; then
#        echo "tas [tag] [kommando] [evt ekstra param]";
#        return;
#    fi;
#    LEDGER_SORT=-date,-payee LEDGER_ADDTIME_TO_PAYEE=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 LEDGER_DEPTH=999 /usr/bin/ledger --related --payee-width=40 -f "$tpath/curl" -l "payee =~ /#$1/" $2 $3 $4
#}
reviewcount=$(grep "#review" *.trans 2>/dev/null|grep Description|grep -v "old value"|grep -v "New Value"|grep -v "console edit"|wc -l)

#bash $tpath/.top7d.bash

#bash $tpath/.top7d_tanker.bash

LEDGER_DEPTH=5
valg="et - easy transaction
rap
recent
watchbudget
xact
watch
tags
edit sessiontag
tags incl dupes
query
budgetdiff
pl
edit budget
memo ($aktivitet)
review ($reviewcount)
Plot
gnome-motd
tænk"
if [ "$1" != "" ]; then
	valg="$1"
else 
	lastmenu="$(head -n1 $tpath/.lastmenu)"
	tmp="/tmp/foo.bar.bid"
	echo "$lastmenu" > "$tmp"
	echo "$valg"|grep -v "$lastmenu" >> $tmp
	valg=$(cat $tmp|fzf)
	unlink $tmp
fi
echo "$valg"> "$tpath/.lastmenu"
if [[ "$valg" == "memo"* ]]; then
	vi "$tpath/.doing.txt"
	exit
elif [ "$valg" == "tænk" ]; then
	bash $tpath/.tanke.bash
	exit
elif [ "$valg" == "gnome-motd" ]; then
	vi ~/.motd
	exit
elif [ "$valg" == "plot" ]; then
	konto="$((echo CUSTOM;LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 LEDGER_DEPTH=999 ledger -f $tpath/curl accounts)|fzf)"
	if [ "$konto" == "CUSTOM" ]; then
		echo -n "Indtast konto/konti: "
		read konto < /dev/tty
	fi
	bash $tpath/.plot.bash "$konto"
	exit

elif [ "$valg" == "et - easy transaction" ]; then
	source $tpath/.add.bash
	et
	exit
elif [ "$valg" == "edit sessiontag" ]; then
	bash /home/joo/regnskaber/transactions_stuff/.menu.bash tags "$(echo -n $(echo $STY|cut -d. -f2-))"
	exit
elif [ "$valg" == "rap" ]; then
	./.rap.bash
	exit
elif [[ "$valg" == "review ("* ]]; then
php /svn/svnroot/Applications/key.php search 1970-01-01 2099-12-31 $(grep "#review" *.trans|grep Description|grep -v "old value"|grep -v "New Value"|grep -v "console edit"|awk '{print $1}'|sed 's/://g'|fzf --tac)
elif [ "$valg" == "recent" ]; then
#	php /svn/svnroot/Applications/key.php search "$(echo -n $(find . -type f -printf "%a %p\n" | sort -n|grep -v lastmenu|grep -v git|grep -v ./curl|grep -v .budget.ledger|grep -v chart_of_account|grep -v ledger_accounts|grep -v .swp|fzf --tac|awk '{print $NF}'))"
	vi "$(echo -n $(find . -type f -printf "%a %p\n" | sort -n|grep -v lastmenu|grep -v git|grep -v ./curl|grep -v .budget.ledger|grep -v chart_of_account|grep -v ledger_accounts|grep -v .swp|fzf --tac|awk '{print $NF}'))"
	exit
elif [ "$valg" == "xact" ]; then
	echo -n "Søg efter: "
	read search
	$tpath/.xact.bash "$search"
	exit
elif [ "$valg" == "pl"  ]; then
	(LEDGER_DEPTH=2 LEDGER_ADDTIME_TO_PAYEE=1 php /svn/svnroot/Applications/key.php ledger -E b --flat & 2>/dev/null) 2>/dev/null
	fn=~/.mn_account
	echo 'Indtægter
	Udgifter
	Aktiver
	Passiver
	Egenkapital'|while read i 
	do
		ledger -f $tpath/curl b --flat "$i:";echo " - ";echo " - " 
	done|fzf --tac|awk '{print $2}' > $fn
	account=$(echo -n $(cat "$fn"))
	clear
	if [ "$account" != "-" ]  && [ "$account" != "" ]; then
		LEDGER_DEPTH=2 LEDGER_ADDTIME_TO_PAYEE=1 php /svn/svnroot/Applications/key.php ledger --payee-width=60 --account-width=5 --date-format='%t' r $account --related --invert -S payee|sort|sed 's/:/./g'|grep --color -E '^|.*23:5.*'|./.color_dosha.bash|fzf --ansi --tac && bash .menu.bash PL
	fi	
	#bash .menu.bash PL
	exit
elif [ "$valg" == "watchbudget" ]; then
while true;
	 do
		LEDGER_ADD_BUDGET=1 LEDGER_ADDTIME_TO_PAYEE=1 nicebal >/tmp/nbal; 
		clear
		cat /tmp/nbal; 
		sleep 5;
 	done
	exit
elif [ "$valg" == "budgetdiff" ]; then
#LEDGER_ADD_BUDGET
	fn=~/.mn_account
	echo 'Indtægter
	Udgifter
	Aktiver
	Passiver
	Egenkapital'|while read i 
	do
		LEDGER_ADD_BUDGET=1 LEDGER_ADDTIME_TO_PAYEE=1 php /svn/svnroot/Applications/key.php ledger -E b --flat "$i:";echo " - ";echo " - " 
	done|fzf --tac|awk '{print $2}' > $fn
	account=$(echo -n $(cat "$fn"))
	clear
	if [ "$account" != "-" ]  && [ "$account" != "" ]; then
		LEDGER_ADD_BUDGET=1 LEDGER_ADDTIME_TO_PAYEE=1 php /svn/svnroot/Applications/key.php ledger --date-format='%t' r $account --related --invert -S payee|sort|sed 's/:/./g'|grep --color -E '^|.*23:5.*'|./.color.bash|fzf --ansi --tac    && bash .menu.bash BudgetDiff 
	fi	
	exit
elif [ "$valg" == "edit budget" ]; then
	vi "$tpath/budget.bash"
	exit

elif [ "$valg" == "query" ]; then
	LEDGER_ADDTIME_TO_PAYEE=1
	vim "$tpath/.losesearchquery"
	query=$(echo -n $(grep -v "^#.*" "$tpath/.losesearchquery"))
	fn="$query"
	cmd="r kasse"
	echo "cmd ($cmd):"
	qf="$tpath/.queryfile"
	vi "$qf"
	nycmd="$(cat "$qf")"
	if [ "$nycmd" == "" ]; then
		cmd="r kasse"
	else
		cmd="$nycmd"
	fi
	ctas "$query" $cmd
	$tpath/.offeraward.bash "#$fn" 1
	exit
elif [ "$valg" == "tags" ]; then
	if [ "$2" == "" ]; then
		fn=$(gettags)
	else
		fn="$2"
	fi
	bn=$(basename "$fn")
	echo "$bn" >> ~/tmp/lastbn_menu
	echo -e "Logger: " > "$tpath/.tags/.$fn.lr"
	pushd . >/dev/null
	cd $tpath/.tags/
	grep "$fn" *|sort >> "$tpath/.tags/.$fn.lr"
	popd >/dev/null

	timebefore=$(date +%s)
	ofn="$fn"
	fn=$(echo -n $(echo "$tpath/.tags/$fn"|awk '{print $1}')) 
	bn=$(basename "$fn")
	bbn=${bn:0:5}
	notitle=$notitle vitouch "$fn" "$tpath/.tags/.$ofn.lr" "$tpath/.tags/$ofn.diff" "$tpath/.tags/.$ofn.lr.diff"
	exit
elif [ "$valg" == "tag" ] || [ "$valg" == "Tag" ]; then #only for command line entering of an extra search param
	fn=$(echo -n $(grep -L "#dupl" $tpath/.tags/*|grep -i "$2"|while read i; do echo -n $( basename "$i")" ";stat --printf="%s (%y)\n" "$i"; done|fzf)|awk '{print $1}')

	echo -e "Logger: " > "$tpath/.tags/.$fn.lr"
	tas "#"$fn r kasse >> "$tpath/.tags/.$fn.lr"
	echo -e "\nWeekly: " >> "$tpath/.tags/.$fn.lr"
	tas "#"$fn r kasse -W >> "$tpath/.tags/.$fn.lr"& 2>/dev/null
	vitouch $(echo -n $(echo "$tpath/.tags/$fn"|awk '{print $1}')) "$tpath/.tags/.$fn.lr" 
	exit
elif [ "$valg" == "watch" ]; then
	while true
	do
		$tpath/.watch.bash Dosha |fzf --ansi --tac
	done
	exit
fi


