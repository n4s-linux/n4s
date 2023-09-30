function sanitize_file_name {
    echo -n $1 | perl -pe 's/[\?\[\]\/\\=<>:;,''"&\$#*()|~`!{}%+]//g;' -pe 's/[\r\n\t -]+/-/g;'
}
pushd . >/dev/null
if [ -z "$1" ]; then
	ls ~/|grep transactions > /tmp/list
else
	echo $1 > /tmp/list
fi
cat /tmp/list|while read i
	do
		echo "Copying $i"
		fnn=$(echo -n "$LEDGER_BEGIN - $LEDGER_END")
		fn=$(sanitize_file_name "$fnn")
		mkdir -p ~/"$i"/csv/
		tpath=~/"$i" LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger csv -S account,date > ~/$i/csv/"$fn".csv
		LEDGER_BEGIN=1970/01/01 LEDGER_END=2099/12/31 tpath=~/"$i" LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger csv -S account,date > ~/$i/csv/år.csv
		fn=$(echo -n "$LEDGER_BEGIN - $LEDGER_END"|sed 's/\//\\\//g';echo .html)
		LEDGER_DEPTH=999 tpath=~/"$i" bash /svn/svnroot/Applications/key_html.bash > ~/$i/html/balance_"$fn".html
		LEDGER_BEGIN=1970/01/01 LEDGER_END=2099/12/31 bash /svn/svnroot/Applications/key_html.bash > ~/$i/html/balance_år.html
		#LEDGER_BEGIN=1970/01/01 LEDGER_END=2099/12/31 LEDGER_DEPTH=999 tpath=~/"$i" php /svn/svnroot/Applications/key.php ledger bal
		#ncftpput -v -u myaccounts.dk -p y6wh94pe -v linux131.unoeuro.com /public_html/"$i" ~/$i/html/balance.html
		pushd .
		cd /svn/svnroot/Applications/
		ncftpput -v -u myaccounts.dk -p y6wh94pe -v linux131.unoeuro.com /public_html/"$i" index.php
		popd
		ncftpput -v -R -u myaccounts.dk -p y6wh94pe -v linux131.unoeuro.com /public_html/ ~/"$i"

done
popd > /dev/null
