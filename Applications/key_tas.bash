function ctas() { # any custom query
if [ "$1" == "" ]; then
	echo "tas [tag] [kommando] [evt ekstra param]"
	return
fi
LEDGER_SORT=-date,payee LEDGER_ADDTIME_TO_PAYEE=1 LEDGER_DEPTH=999 ledger -f $tpath/curl -l "$1" $2 $3 $4 $5
}

function tas() {
if [ "$1" == "" ]; then
	echo "tas [tag] [kommando] [evt ekstra param]"
	return
fi
LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 LEDGER_SORT=-date,payee LEDGER_ADDTIME_TO_PAYEE=1 LEDGER_DEPTH=999 ledger -f $tpath/curl -l "payee =~ /$1/" $2 $3 $4 --payee-width=45 --account-width=10
}


function ttas() {
if [ "$1" == "" ]; then
	echo "tas [tag] [kommando] [evt ekstra param]"
	return
fi
LEDGER_SORT=-date,payee LEDGER_ADDTIME_TO_PAYEE=1 LEDGER_DEPTH=999 ledger -f $tpath/curl -l "payee =~ /$1/" $2 $3 $4
}


