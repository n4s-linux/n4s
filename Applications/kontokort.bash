for i in Indtægter Udgifter Aktiver Egenkapital Passiver
do
	echo "$i:"
	LEDGER_DEPTH=5 ledger --no-pager -f $tpath/curl print|hledger -f /dev/stdin balance ^"$i" --depth=2|sed  's/^/     /'
	echo
	php /svn/svnroot/Applications/key.php ledger print |hledger -f /dev/stdin accounts ^"$i" --depth=2 |while read konto
	do
		echo -e "\t$konto"
		konto=$(echo "$konto"|sed 's/\//\\\//g'|sed 's/ /\\ /g') #first sed escape forward and back slaches, the next escapes spaces
		LEDGER_SORT=account,date LEDGER_DATE_FORMAT="%Y-%m-%d" LEDGER_DEPTH=5 ledger --no-pager -f $tpath/curl r ^"$konto"|sed  's/^/     /'
		echo
	done
echo
echo
done > ~/tmp/kontokort.txt
