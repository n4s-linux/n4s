res=$(ledger --no-pager -B -f $tpath/curl b ^Indtægter ^Udgifter|tail -n1|awk '{print $1}')
if [ "$res" == "" ]; then
res=0
fi
dato=$(date --date="$LEDGER_END -1 day" +%Y-%m-%d)
echo -e "$dato Periodens resultat\n\tEgenkapital:Periodens resultat  $res\n\tResultatdisponering:Periodens resultat\n\n"

exit


if [ "$noend" == "1" ]; then
	exit
fi
echo "$LEDGER_BEGIN Overført saldo"
LEDGER_END=$(date --date="$LEDGER_BEGIN -1 day" +%Y-%m-%d) LEDGER_BEGIN=1970/1/1 noend=1 ledger --no-pager -B -f $tpath/curl b --flat ^Aktiver ^Passiver ^Egenkapital ^Fejlkonto --no-total --balance-format="%(account)\t%(total)\n"|while read i
do
	konto=$(echo "$i"|awk '{print $1}')
	belob=$(echo "$i"|awk '{print $2}')
	re='^[+-]?[0-9]+([.][0-9]+)?$'
	if  [[ $belob =~ $re ]] ; then
		echo -e "\t$konto  $belob"
	fi

done
echo -e "\tEgenkapital:Overført resultat\n\n"
