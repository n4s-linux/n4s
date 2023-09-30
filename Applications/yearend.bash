#!/bin/bash
lastdate=$(date --date="$LEDGER_END -1 day" +%Y-%m-%d)
echo -n "Bekræft af der skal dannes åbningsposter $LEDGER_BEGIN - $LEDGER_END (j/n): "
read yn
if [ "$yn" != "j" ]; then
	exit
fi
opening=$LEDGER_END
ofile=$tpath/Åbning_$opening.ledger
echo "$LEDGER_END Opening $lastdate" > $ofile

LEDGER_END=$opening
php /svn/svnroot/Applications/key.php ledger equity ^Aktiver ^Passiver ^Egenkapital ^Fejl |tail -n +2|grep -v "Opening"|while read i
do
	echo -e "\t$i"|php /svn/svnroot/Applications/replaceacc.php >> $ofile
done
echo -e "\tEgenkapital:Overført resultat\n" >> $ofile
less $ofile
