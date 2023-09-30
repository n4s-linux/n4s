if [ "$1" != "calc" ]; then
exit
fi
selskabskapital=290000
LEDGER_BEGIN=2020-01-01
LEDGER_END=2021-01-01
enddate=$(echo -n $(date +%Y-%m-%d --date="$LEDGER_END -1 days"))
LEDGER_DEPTH=9 
output="$tpath/skatteregnskab_$enddate".ledger
rm -rf "$output"
rep=$(echo -n $(php /svn/svnroot/Applications/key.php ledger b udgifter:reklame...Markedsføring:Repræsentation --balance-format="%(amount)"))
res=$(echo -n $(php /svn/svnroot/Applications/key.php ledger b Resultatdisponering: --balance-format="%(amount)"))
egenkapital=$(echo -n $(LEDGER_DEPTH=1 php /svn/svnroot/Applications/key.php ledger b egenkapital: --balance-format="%(total)"))

imm=$(echo -n $(php /svn/svnroot/Applications/key.php ledger b Aktiver:Anlægsaktiver:Immaterielle --balance-format="%(amount)"))
udviklingsfradrag=$(echo -n $(echo "$imm * -0.3"|bc))
#res=$(echo "$res * -1"|bc)

regselskabsskat=2115
repreg=$(echo -n $(echo $rep*0.75|bc))

skatresultat=$(echo -n $(echo $res + $repreg + $udviklingsfradrag + $regselskabsskat |bc))
selskabsskat=$(echo -n $(echo $skatresultat*0.22|bc))
echo -e ";Genereret af skatteregnskab.bash"
echo -e ";Resultat jf. bogføring:\t$res" >> $output
echo -e ";Regulering repræsentation 75%:\t$repreg" >> $output
echo -e ";Forhøjet fradrag udvikling\t$udviklingsfradrag" >> $output
#echo -e ";Aktiverede udviklingsomkostninger: -$imm" >> $output
echo -e ";Regulering selskabsskat:\t$regselskabsskat" >> $output
echo -e ";Skattemæssigt resultat:\t$skatresultat" >> $output
echo -e ";Selskabsskat\t$selskabsskat" >> $output
echo "$enddate (skatteregnskab.bash) Selskabsskat" >> $output
echo -e "\tUdgifter:Selskabsskat  $selskabsskat" >> $output
echo -e "\tPassiver:Skyldig selskabsskat" >> $output


udbytte=$(echo -n $(echo $selskabsskat $egenkapital + $selskabskapital|bc))
udbytte=$(echo -n $(echo $udbytte *-1|bc))

echo -e "; Udbytte $udbytte"
echo "$enddate (skatteregnskab.bash) Udbytte" >> $output
echo -e "\tEgenkapital:Udbytte  $udbytte" >> $output
echo -e "\tAktiver:Mellemregning med holding" >> $output


echo >> $output
cat $output

output="/home/joo/regnskaber/transactions_0lsen/olsensrevision_udbytte_$enddate.ledger"
echo -e "; Udbytte $udbytte"
echo "$enddate (skatteregnskab.bash) Udbytte" >> $output
echo -e "\tPassiver:Mellemregning Olsens Revision ApS  $udbytte" >> $output
echo -e "\tIndtægter:Udbytte:Olsens Revision ApS" >> $output
echo >> $output
echo
cat $output

echo calculating ledger again...
php /svn/svnroot/Applications/key.php ledger b >/dev/null
