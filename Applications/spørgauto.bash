cd /data/regnskaber/transactions_crm/.tags
function newlines() {
	read line
	kunde="$1"
	md=$(echo "$kunde $line" |md5sum|awk '{print $1}')
	if [ ! -f /data/mails/"$kunde"_"$md" ]; then
		echo $line
		echo $line > /data/mails/"$kunde"_"$md"
	fi

}
function sendspørgsmål() {
	kunde="$1"
	mail="$2"
	fn=~/tmp/spm_$kunde
	echo "Spørgsmål vedr $kunde: " > "$fn"
	grep -v "#private" "$kunde"|grep -v "✔"|newlines "$kunde" > "$fn"
	if [ -s "$fn" ]; then
		echo sender til $kunde
		cp "$kunde" ~/tmp/spørg.md
		pandoc ~/tmp/spørg.md -o ~/tmp/spørg.html
		#cat "$kunde"|mail -s "Opdateret Journal med spørgsmål" olsenit@gmail.com
		mail -a "Content-type: text/html" -s "Built notification" olsenit@gmail.com < ~/tmp/spørg.html
		#mail -a "Content-type: text/html" -s "Built notification" address@example.com < /var/www/report.html
	else
		echo "ingen nye spørgsmål til $kunde"
	fi
}
function spørgkunde() {
	kunde="$1"
	mail=$(bash /svn/svnroot/Applications/mkd.bash "$kunde" getsection Stamdata|grep -i mail|grep -i -o '[A-Z0-9._%+-]\+@[A-Z0-9.-]\+\.[A-Z]\{2,4\}'|head -n1)
	if [ "$mail" == "" ]; then
		mail="olsenit+$kunde@gmail.com"
	fi
	sendspørgsmål "$kunde" "$mail"
	exit
}
function kundermedspørgsmål() {
	grep "#spørg" *|grep -v .diff|grep -v "✔" |awk '{print $1}'|sort|uniq|sed s/://
}
kundermedspørgsmål|while read kunde
do
	spørgkunde "$kunde"
done
