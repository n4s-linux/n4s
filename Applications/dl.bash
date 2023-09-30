echo "|dato|antal sager|"
echo "|---|---|"
tpath=/data/regnskaber/regnskabsdeadlines/ noend=1 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 php /svn/svnroot/Applications/key.php ledger print|hledger  -f /dev/stdin balance Opgaver --depth=2 no-total|sed s/Opgaver://g |while read i;
do 
dato=$(echo -n "$i"|awk '{print $2}')
if [ "$dato" == "" ]; then
	continue
fi
ts=$(date +%s --date="$dato")
now=$(date +%s)
antal=$(echo -n "$i"|awk '{print $1}')
if [ "$ts" -gt "$now" ] ; then
	color=green
else
	color=yellow⏳
fi
echo "|<font color=$color>$dato</font>|<font color=$color>$antal</font>|"
done|sed 's/-09-01/-09-01 #momshalvår H1/'|sed 's/-03-01/-03-01 #momshalvår H2/'|sed 's/-07-01/-07-01 #årsregnskab/'|sed 's/-12-01/-12-01 #momskvartal Q3/'|sed 's/-09-01/-09-01 #momskvartal Q2/'
