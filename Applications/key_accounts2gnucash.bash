noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger|while read i
do
if [[ $i == *"Udgifter"* ]]; then
	type="EXPENSE"
elif [[ $i == *"Indtægter"* ]]; then
	type="INCOME"
elif [[ $i == *"Aktiver"* ]]; then
	type="ASSET"
elif [[ $i == *"Passiver"* ]]; then
	type="LIABILITY"
elif [[ $i == *"Egenkapital"* ]]; then
	type="EQITY"
elif [[ $i == *"Resultatdisponering"* ]]; then
	type="EQUITY"
fi

done
