exit
curl=$tpath/curl
cache=$tpath/.cache_interest
echo "; cache file $cache"
if [ ! -f "$cache" ] || [[ "$(find "$cache" -mmin 60 -print)" != "" ]]; then
	rm -f "$cache"
	echo ";caching interest..."
	echo 'Passiver:Gældsstyrelsen|0.04
Passiver:Lån Fsweld 5%|0.04'|while read konto
	do
		annual=$(echo "$konto"|cut -d'|' -f2)
		konto=$(echo "$konto"|cut -d'|' -f1)
		shortkonto=$(echo "$konto"|cut -d':' -f2)
		hledger-interest --today -f $curl --source="Udgifter:Renteudgifter:$shortkonto" --target="$konto:970_Interest" --annual=$annual "$konto$" -q --30E-360 >> $cache
	done
	cat "$cache"
else
	echo ";cached interest transactions"
	cat $cache
fi
