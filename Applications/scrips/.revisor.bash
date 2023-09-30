output=~/tmp/beregnrevisor.txt
start=2023-03-01
LEDGER_BEGIN=1970/1/1 LEGER_END=2099/12/31 ledger -f $tpath/curl r -S account,date --no-pager|grep -v 2,0069|grep -v Passiver:Moms:Salgsmoms|grep -v Passiver:Moms:Købsmoms|grep -v Egenkapital:Period|grep -v Resultatd > ~/tmp/grundlag.txt
total=$(cat ~/tmp/grundlag.txt|wc -l)
begin=$(date +%s --date="$start")
now=$(date +%s --date=$LEDGER_END)
dage=$(((begin-now)/-86400))
echo "Brugt $total bilag på $dage dage" > $output
omregnet=$((365/dage*total))
echo "omregnet $omregnet bilag pr. år">> $output

echo her er bilagene der indgår i beregning:>> $output
cat ~/tmp/grundlag.txt >> $output
