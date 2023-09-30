function sanitize_file_name {
    echo -n $1 | perl -pe 's/[\?\[\]\/\\=<>:;,''"&\$#*()|~`!{}%+]//g;' -pe 's/[\r\n\t -]+/-/g;'
}
res=$(echo -e "Drift\nBalance"|fzf --tac --header="Vælg afstemningstype")
if [ "$res" == "Balance" ]; then
	konti="^Aktiver ^Passiver ^Egenkapital ^Fejl"
else
	konti="^Indtægter ^Udgifter"
fi
green=32
red=30
black=15
php /svn/svnroot/Applications/key.php ledger bal $konti --flat --no-total|while read account
do
	bal=$(echo "$account"|awk '{print $1}')
	acc=$(echo "$account"|cut -d' ' -f2-|xargs) #xargs used for trim
	balfn=$(sanitize_file_name ".rc_"$acc"_$LEDGER_BEGIN"_"$LEDGER_END")
	recbal=$(cat "$tpath/$balfn" 2>/dev/null)
	if [ "$recbal" == "" ]; then
		recbal="NA"
	fi
	if [ "$recbal" != "$bal" ]; then
		echo -e "\033[$red;$black""m$acc\t$bal"|column -t
	else
		echo -e "\033[$green;$black""m$acc\t$bal"|column -t
	fi

done|fzf --ansi --multi > ~/tmp/valgafkonto



cat ~/tmp/valgafkonto|while read valg; do
	acc=$(echo "$valg"|awk '{$NF=""}1'|xargs)
	bal=$(echo "$valg"|awk '{print $NF}')
	balfn=$(sanitize_file_name ".rc_"$acc"_$LEDGER_BEGIN"_"$LEDGER_END")
	php /svn/svnroot/Applications/key.php ledger r ^"$acc">  ~/tmp/årsafslutning
	less ~/tmp/årsafslutning
	echo -n "Marker som afstemt ? (j/n): "
	read afslut < /dev/tty
	echo -n "Indtast evt. kommentar: "
	read kommentar </dev/tty
	if [ "$afslut" == "j" ]; then
		date=$(date +%Y-%m-%dT%h%:%M)
		op=$(whoami)
		echo "# $op Afsluttet konto $acc ($date $kommentar) indhold: " >> $tpath/.årsafslutning.log
		cat ~/tmp/årsafslutning|while read line
		do
			echo -e "\t$line"
		done >> $tpath/.årsafslutning.log
		echo >> $tpath/.årsafslutning.log
		echo $bal > "$tpath/$balfn"
	fi
done
