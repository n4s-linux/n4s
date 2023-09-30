res=$(ledger --no-pager -B -f $tpath/curl b ^Indtægter ^Udgifter|tail -n1|awk '{print $1}')
if [ "$res" == "" ]; then
res=0
fi
dato=$(date --date="$LEDGER_END -1 day" +%Y-%m-%d)
echo -e "$dato Periodens resultat\n\tEgenkapital:Periodens resultat  $res\n\tResultatdisponering:Periodens resultat\n\n"
