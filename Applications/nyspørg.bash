source ~/rtconfig #get RTSERVER, RTPASS ETC
cd /data/regnskaber/transactions_crm/.tags/
grep ^\#\ Spørgsmål$ *|while read kunde
do
kunde=$(echo "$kunde"|awk '{print $1}'|sed s/://|sed s/\#//g)
php /svn/svnroot/Applications/getmdheading.php "$kunde" Spørgsmål
done|grep -v ^\#\ Spørgsmål$|sed '/^$/d' > ~/tmp/"questions_$kunde"

cat ~/tmp/"questions_$kunde"|while read line
do
	md=$(echo "$kunde $line" |md5sum|awk '{print $1}')
	if [ ! -f /data/mails/"$kunde"_"$md" ]; then
		echo "$line" > /data/mails/"$kunde"_"$md"	
		subject="Spørgsmål fra OlsensRevision"
	ticket=$(rt create -t ticket set subject="$subject" add requestor=olsenit@gmail.com|awk '{print $3}')
		echo "new ticket $ticket"
		rt edit "$ticket" set Queue='Spørgsmål'
		rt comment -m "$line" $ticket
	fi

done
