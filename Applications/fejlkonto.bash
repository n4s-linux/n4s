uid=$(ip a | sed '\|^ *link[^ ]* |!d;s|||;s| .*||'|md5sum|awk '{print $1}')
ip=70.34.205.132
if [ "$tpath" == "" ]; then
	echo kræver aktivt regnskab
	exit
fi

bn="$(basename "$tpath")"
date=$(date -I)
fn=~/tmp/export-"$bn"_"$date".html
mode=$(echo -e "Spørgeskema + manglende bilagsliste\nRediger eksklusionsliste\n"|fzf --header="Skal vi spørge eller registrere svar?")
if [ "$mode" == "Spørgeskema + manglende bilagsliste" ]; then
	LEDGER_DISABLE_VAT=1 php /svn/svnroot/Applications/key.php ledger csv ^fejl | php /svn/svnroot/Applications/fejlkonto.php > $fn
	scp -q -o LogLevel=QUIET "$fn" root@$ip:/var/www/html/"$bn"-$uid".fejl.html" 
	if [ ! -f "$tpath/.kontaktperson" ]; then
		whiptail --msgbox "Der findes ikke en kontaktperson på dette regnskab, du skal indtaste den i VI om lidt" 7 80	
		vi "$tpath/.kontaktperson"
	fi
	kontaktperson=$(cat "$tpath/.kontaktperson")
	echo "Hej $kontaktperson". > ~/tmp/mailfile
	echo "Vi arbejder på dit regnskab for perioden $LEDGER_BEGIN - $LEDGER_END". >> ~/tmp/mailfile
	
	echo "Før vi kan færdiggøre dit regnskab har vi brug for yderligere oplysninger." >> ~/tmp/mailfile
	echo -e "- Fejlkonto (*1): http://$ip/$bn"-$uid".fejl.html" >> ~/tmp/mailfile

	LEDGER_MISSING_VOUCHERS=1 php /svn/svnroot/Applications/key.php ledger csv -S date,payee | php /svn/svnroot/Applications/missingvouchers.php > $fn
	scp -q -o LogLevel=QUIET "$fn" root@$ip:/var/www/html/"$bn"-$uid".mangler.html"
	echo "- Manglende bilag (*2): http://$ip/$bn"-$uid".mangler.html" >> ~/tmp/mailfile
echo -e "\n\n1) Fejlkonto - posteringer i regnskabet der ikke kan placeres korrekt uden flere oplysninger fra Jer. Eksempelvis bankposteringer uden bilag, eller bilag som ikke kan findes i banken">> ~/tmp/mailfile

echo -e "\n2) Manglende bilag - poster fra regnskabet som vi ikke har fået et bilag på. Bemærk at dette som udgangspunkt er i orden ved følgende poster: Interne overførsler, betaling til skattekontoen, afdrag til kreditorer, bankgebyrer">> ~/tmp/mailfile

echo -e "\n\n\nMed venlig hilsen\nOlsens Revision ApS\nFortunstræde 1, 2.\n1065 København K\n\n\n+45 25864573" >> ~/tmp/mailfile

vi ~/tmp/mailfile
cat ~/tmp/mailfile|mail -s "OlsensRevision: Spørgeskema" olsenit@gmail.com
echo -n "Vil du emaile? (jn): "
read yn
if [ "$yn" == "j" ]; then
	if [ ! -f "$tpath/.kundemail" ]; then
		whiptail --msgbox "Der findes ikke en mail på dette regnskab, du skal indtaste den i VI om lidt" 7 80	
		vi "$tpath/.kundemail"
	fi
	mail=$(cat "$tpath/.kundemail")
	cat ~/tmp/mailfile|mail -s "OlsensRevision: Spørgeskema" $mail
fi
elif [ "$mode" == "Rediger eksklusionsliste"  ]; then
	vi $tpath/.skipbytext

#else
#	whiptail --msgbox "Når VI åbner skal du indsætte (paste) det datasvar der er modtaget på email" 7 80
#	LEDGER_DISABLE_VAT=1 php /svn/svnroot/Applications/key.php ledger csv ^fejl | php /svn/svnroot/Applications/fejlkonto.php response
fi
