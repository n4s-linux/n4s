#if [ "$noend" != 1 ]; then
	LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 LEDGER_DEPTH=99 ledger --no-pager -B -f ./curl reg ^Indtægter: ^Udgifter: --register-format="%(date) (%(code)) %(payee)\n\tEgenkapital:Periodens resultat  %(display_amount)\n\tResultatdisponering:Overført til egenkapital\n\n" 
#fi
