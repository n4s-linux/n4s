echo "KONTOSPECIFIKATIONER FOR $LEDGER_BEGIN - $LEDGER_END ($tpath)" > /tmp/specs
chmod 777 /tmp/specs
echo -e "Indtægter\nUdgifter\nResultatdisponering\nAktiver\nPassiver\nEgenkapital\nFejl/analyse"|
while read konto; do 
	hledger --begin $LEDGER_BEGIN --end $LEDGER_END -f curl --depth 3 bal "$konto:";echo;
	echo "Kontokort for $konto" >> /tmp/specs
	hledger --begin $LEDGER_BEGIN --end $LEDGER_END -f curl --depth 3 reg "$konto:">> /tmp/specs
 done
cat /tmp/specs
