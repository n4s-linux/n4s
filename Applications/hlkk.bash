#!/bin/bash
(
rm -rf /tmp/lvl4
echo "<meta charset=utf8><pre>"
bn=$(basename "$tpath")
echo "<h1>Saldobalance med specifikationer for $bn for perioden $LEDGER_BEGIN - $LEDGER_END"
hledger --begin=$LEDGER_BEGIN --end=$LEDGER_END  -f "$tpath/curl" --depth 99 b
function makelevel4() {
	hledger --begin=$LEDGER_BEGIN --end=$LEDGER_END  -f "$tpath/curl" accounts "$1:" --depth 4|while read subsub
	do
		lvl=$(echo -n $(echo "$subsub" | tr -cd : | wc -c))
		if [ "$lvl" -gt "2" ]; then
			echo "<h2>Kontokort for $subsub:</h2>" >> /tmp/lvl4;
			hledger --begin=$LEDGER_BEGIN --end=$LEDGER_END  -f "$tpath/curl" register "$subsub" >> /tmp/lvl4
		fi
	done
}
function showlevel3() {
	echo "<h3>Kontokort for $1:</h3>"
	hledger --begin=$LEDGER_BEGIN --end=$LEDGER_END  -f "$tpath/curl" register "$1"
	makelevel4 "$1"
	echo
	echo
}
function showlevel2() {
	hledger --begin=$LEDGER_BEGIN --end=$LEDGER_END  -f "$tpath/curl" accounts "$1:" --depth 2|while read subacc
	do
		showlevel3 "$subacc"
	done
}
hledger --begin=$LEDGER_BEGIN --end=$LEDGER_END  -f "$tpath/curl" accounts --depth 1|while read account
do
	showlevel2 "$account"
	
done
cat /tmp/lvl4
rm -rf /tmp/lvl4
) > /tmp/export.html
w3m /tmp/export.html
