#!/bin/bash
#START BALANCE
#LEDGER_BEGIN=1970/1/1
#LEDGER_END=2099/12/31
uid=$(date +%s)
out=/tmp/out_$uid
echo "<title>Resultat & Balance</title>" > $out.html
php /svn/svnroot/Applications/key.php ledger -B balance XXXXXXXXXXXXXXXXXXXXXX > /dev/null
in="$tpath/curl"
resultat=$(LEDGER_DEPTH=999 LEDGER_DEPTH=999 ledger -B --invert -f "$in" bal Indtægter: Udgifter: --balance-format="%(to_int(total))\n"|tail -n1)
if [ -f "$tpath/Forside.html" ]; then
	cat "$tpath/Forside.html" >> $out.html
	echo "<br><br>" >> $out.html


fi
echo "<p align=right>Rapport udarbejdet " $(date) " af $(whoami)<br></p>" >> $out.html
echo "<p align=right>Rapport vedrører perioden: $LEDGER_BEGIN - $LEDGER_END</p><br><br>" >> $out.html
echo "<p style=\"page-break-before: always\">" >> $out.html
echo '<meta charset=utf8><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">' >> $out.html
echo "<table width=100% class='table-borderless'>" >> $out.html
echo "Indtægter
Udgifter
Aktiver
Passiver
Egenkapital"|while read i
do
	inv=""
	if [ "$i" = "Indtægter" ] || [ "$i" = "Udgifter" ] || [ "$i" == "Passiver" ] || [ "$i" == "Egenkapital" ] ; then
		inv=" --invert"
	fi
	echo "<tr><td width=500><h3>$i</h3></td></tr>" >>$out.html
	(echo -n "$(date +%Y-%m-%d) Ovf resultat";echo -ne "\n\tEgenkapital:Periodens resultat  -$resultat\n\tResultatoverførsel\n\n"  )> ~/curovf
	cat "$in" >> ~/curovf
	ledger -B $inv -f ~/curovf  --balance-format="<tr><td><p align=left>%a</p></td><td><p align=right>%(to_int(total))</p></td>\n" --depth 2 bal "$i" >> /tmp/foobar_$uid


	#ledger -B $inv -f ~/curovf  --balance-format="<tr><td><p align=left>%a</p></td><td><p align=right>%(to_int(total))</p></td></tr>\n" --depth 2 bal "$i" > /tmp/foobar_$uid
	wc=$(cat /tmp/foobar_$uid|wc -l)
	if [ "$wc" != "1" ]; then
		cat /tmp/foobar_$uid|tail -n +2 > /tmp/foobar2_$uid
		cp /tmp/foobar2_$uid /tmp/foobar_$uid
	fi
	cat /tmp/foobar_$uid|sed "s/$i://" >> $out.html
	if [ "$i" = "Udgifter" ]; then

	echo "<tr><td>&nbsp;</td></tr>">>$out.html
	echo "<tr><td><p align=left><b><u>Resultat</b></u></p></td><td><p align=right><b><u>$resultat</u></b></p></td></tr>">>$out.html
	fi
	echo "<tr><td>&nbsp;</td></tr>">>$out.html
done
# START SPECIFIKATIONSHÆFTE
echo "</table><p style=\"page-break-before: always\"><table width=100% class='table-borderless'><h2>Specifikationer</h2>" >> $out.html
echo "Indtægter
Udgifter
Aktiver
Passiver
Egenkapital"|while read y
do
	inv=""
	if [ "$y" = "Indtægter" ] || [ "$y" = "Udgifter" ] || [ "$y" == "Passiver" ] || [ "$y" == "Egenkapital" ] ; then
		inv=" --invert"
	fi
	ledger -B -f "$in" b "$y" --balance-format="%(account)\n" --no-total|tail -n +2|while read i
		do
		ii=$(echo $i|sed 's/\//\\\//g')
		LEDGER_DEPTH=3 ledger -B $inv -f "$in" b "$ii:" --balance-format="<tr><td><p align=left>%(account)</p></td><td><p align=right>%(to_int(total))</p></td>" > /tmp/loutput_$uid
		#LEDGER_BEGIN=1970/1/1 LEDGER_DEPTH=3 ledger -B $inv -f "$in" b "$ii:" --balance-format="<td>%(to_int(total))</td>" >> /tmp/loutput_$uid
		echo "</tr>\n" --no-total >> /tmp/loutput_$uid
		lwc=$(cat /tmp/loutput_$uid|wc -l)
		if [ "$lwc" -gt 1 ]; then
			echo $(echo "<tr><td><h3>$i</h3></td></tr>"|sed "s/$y://g"; cat /tmp/loutput_$uid|tail -n +2|sed "s/$i://g") >> $out.html
			echo "<tr><td>&nbsp;</td></tr>" >> $out.html
		fi
	done
done

echo "</table><p style=\"page-break-before: always\"><h2>Kontospecifikationer</h2>">>$out.html
php /svn/svnroot/Applications/key_kontokort_html.php >> $out.html
cat $out.html
#START Kontospecifikationer
exit
echo "<p style=\"page-break-before: always\"><h2>Kontospecifikationer</h2><pre>" >> $out.html
	LEDGER_DEPTH=999 ledger -B -f "$in" r --register-format="<a href=../%(tag('Filename'))>%-30.30A\t%(date)\t%-20.20C\t%-30.30P\t%20.20(amount)\t%20.20(total)\t%(tag('Filename'))</a>\n" --no-pager -S account,date >> $out.html
echo "</pre>" >> $out.html

#SHOW OUTPUT
cat $out.html
rm $out.html

# Please rewrite this in LaTeX once we achieve world domination #htmlsucks
