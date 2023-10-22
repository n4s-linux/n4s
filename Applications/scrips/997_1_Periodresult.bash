LEDGER_DEPTH=99 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 ledger -f $tpath/curl --no-pager -B reg -M ^Indtægter: ^Udgifter: --register-format="%(date) (997_1_Periodresult.bash) Periodens resultat\n\tEgenkapital:Periodens resultat  %(display_amount)\n\tResultatdisponering:Overført til egenkapital\n\n" 


