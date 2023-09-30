if [ ! -f $tpath/.config/regnskabsår.bash ]; then
	mkdir -p $tpath/.config/
	cp /svn/svnroot/Applications/config/regnskabsår.bash $tpath/.config/
	vi $tpath/.config/regnskabsår.bash < /dev/tty
fi
source $tpath/.config/regnskabsår.bash

if [ "$noend" == "" ]; then
	for ar in "${regnskabsar[@]}"; do
		start=$(echo "$ar"|awk '{print $1}')
		slut=$(echo "$ar"|awk '{print $2}')
		echo -ne "$slut Åbning\n" 
		LEDGER_DEPTH=999 LEDGER_BEGIN=$start LEDGER_END=$slut
		ledger --no-pager -B -f ./curl equity ^aktiver ^passiver ^egenkapital ^fejl|tail -n +2|sed 's/Equity:Opening Balances/Egenkapital:Overført resultat/g' 
	done
else
	echo "; running Periodresultat with noend"
fi
cp ./curl ./.stats
LEDGER_DEPTH=99 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 ledger --no-pager -B -f ./curl reg ^Indtægter: ^Udgifter: --register-format="%(date) (%(code)) %(payee)\n\tEgenkapital:Periodens resultat  %(display_amount)\n\tResultatdisponering:Overført til egenkapital\n\n" 


