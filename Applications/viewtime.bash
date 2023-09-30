#!/bin/bash
ref="$1"
#ref=$(grep "#ledgerkonto" /data/regnskaber/transactions_crm/.tags/$1|xargs|awk '{print $3}')
if [ "$ref" == "" ]; then
	ref="$1"
	if [ "$ref" == "" ]; then
		echo "<h1>Ingen tidsregistreringer fundet</h1>"
	fi
fi
echo "<h1>Seneste tidsregistreringer for '$ref':</h1><table class=table><tr><th style='text-align:left'>Dato</th><th style='text-align:left'>Tekst</th><th style='text-align:left'>Beløb</th><th>Total</th>";                                                                          
LEDGER_BEGIN=1970/1/1 LEDGER_END=$(date +%Y-%m-%d --date=tomorrow) ledger -f ~/regnskaber/igangv/curl r "$ref" --register-format="<tr><td>%(date)</td><td>%(payee)</td><td><p align=right>%(display_amount)</p></td><td><p align=right>%(display_total)</p></td></tr>\n"|awk '/<p align=right>0<\/p><\/td><\/tr>/{p=1}p'

echo "</table>";
