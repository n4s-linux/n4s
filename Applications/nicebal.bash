#!/bin/bash
function nicebal() {
if [ "$tpath" == "" ]; then
	echo "nicebal requires tpath"
	return
fi
bg=$LEDGER_BEGIN
end=$LEDGER_END
if [ "$1" = "year" ]; then
	LEDGER_BEGIN=1970/1/1
	LEDGER_END=2099/12/31
fi
php /svn/svnroot/Applications/key.php ledger bal >/dev/null
echo "Indtægter
Udgifter"|while read i; 
	do
	echo "$i"
	wc=$(ledger -B -f "$tpath/curl" b "$i:"|wc -l)
	if [ "$wc" != "1" ] ; then
	LEDGER_DEPTH=2 ledger -B -f "$tpath/curl" b "$i:"|tail -n +2;echo;
	else
	LEDGER_DEPTH=2 ledger -B -f "$tpath/curl" b "$i"|tail -n +1;echo;
	fi
	done
total=$(LEDGER_DEPTH=1 ledger -f "$tpath/curl" b Indtægter: Udgifter: --balance-format="%(total)\n"|tail -n1)
echo "Resultat: $total"
echo
echo "Aktiver
Passiver
Egenkapital"|while read i; do
	echo "$i"
	wc=$(ledger -B -f "$tpath/curl" b "$i:"|wc -l)
	if [ "$wc" != "1" ] ; then
	LEDGER_DEPTH=2 ledger -B -f "$tpath/curl" b "$i:"|tail -n +2;echo;
	else
	LEDGER_DEPTH=2 ledger -B -f "$tpath/curl" b "$i"|tail -n +1;echo;
	fi

	done

LEDGER_BEGIN=$bg
LEDGER_END=$end
}
