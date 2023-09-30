fn=$(echo -n $(basename $tpath)-$(date|md5sum)).html
LEDGER_DEPTH=3
noend=1
cp $tpath/curl /tmp/curlexp
LEDGER_FILE=/tmp/curl
LEDGER_PAYEE_WIDTH=50
LEDGER_ACCOUNT_WIDTH=50
LEDGER_SORT=date,payee
end=$(date +%Y-%m-%d -d "$LEDGER_BEGIN -1 day")
(echo $LEDGER_BEGIN 1 - Overført
LEDGER_BEGIN=1970/1/1 LEDGER_END=$end php /svn/svnroot/Applications/key.php ledger equity ^Aktiver: ^Passiver: ^Egenkapital|tail -n +2) >> /tmp/curlexp
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">'
echo "<meta charset=utf8>"
echo '<style type="text/css" media="print">
            @page {
                size: auto;   /* auto is the initial value */
                margin: 0;  /* this affects the margin in the printer settings */
            }
            @page { size: portrait; }
@media print {
   a[href]:after {
      visibility: hidden !important;
   }
}
</style>'

echo "<div width=1200><left><pre><p align=left>"
(echo -e Regnskab: "\t" $(basename "$tpath")|sed 's/transactions_//g'
echo -e Start: "\t"$LEDGER_BEGIN
echo -e Slut: "\t"$LEDGER_END
echo -e Medarbejder:"\t"$(whoami)
echo -e "Eksporteret\t $(date +%y-%m-%d)"
)|column -t|sed "s/\\n/<br>/g"
echo "<pre>"
cat /tmp/comments
echo "</pre>"
echo "</p></pre></left>"
echo "<P style='page-break-before: always'>"
echo
echo
echo
for i in $(echo "Indtægter Udgifter Resultatdisponering Aktiver Passiver Egenkapital")
do
	echo "<h3>$i</h3>"|sed -e 's/\(.*\)/\U\1/'
	echo "<a name=top></a><div width=1200><table width=1200 class='table-striped table-bordered'>";
	ledger --no-total --no-pager bal --flat   "^$i:" --no-color --balance-format="<tr><td width=550><a name='#back-%(account)'><a href='#%(account)'>%(account)</a></a></td><td width=350><p align=right><a href='#%(account)'>%(to_int(total))</a></p></td></tr>\n"> /tmp/out
	php /svn/svnroot/Applications/replace_link.php ##//> /tmp/out2
##	php /svn/svnroot/Applications/replace_link2.php 
	rm /tmp/out
	LEDGER_DEPTH=1 ledger bal "^$i:" --balance-format="<tr><td width=350><b><u>$i I alt</b></u></td><td width=450><p align=right><b><u>%(to_int(total))</b></u></p></td></tr>\n";
	echo "</table></div>";
	echo "<br><br>"
done
LEDGER_FILE=/tmp/curlexp
echo "<P style='page-break-before: always'>"
for tla in $(echo "Indtægter Udgifter Resultatdisponering Aktiver Passiver Egenkapital")
do
	ledger accounts --no-pager $tla --flat|while read i
	do
		oldi="$i"
		md=$(echo -n "$i"|md5sum|awk '{print $1}')
		echo -e "<a name=\"$md\"><b>Kontoudtog for: $i:</b></a>"
		i=$(echo "$i" | sed 's/\//\\\//g')
	echo "<div width=1200><table width=1200 class='table-striped table-bordered'>";
		 LEDGER_DEPTH=4 ledger --no-pager reg --no-color  "^$i$" -S date,payee --register-format="<tr><td width=75>%(date)</td><td width=140>%(code)</td><td width=200>%(payee)</td><td width=75><p align=right>%(display_amount)</p></td><td width=75><p align=right>%(display_total)</p></td></tr>";
	echo "</table></div><a href='#top'>Til tops</a><br><br>";
		echo
		echo
	done
done
