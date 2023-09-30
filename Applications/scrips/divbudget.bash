exit
if [ "$1" == "" ]; then
	echo "; exiting divbudget.bash "
	exit
fi
if [ "$momskonto" == "" ]; then
	momskonto="Uspec"
fi
tekst="$1"
startdato=$(date +%Y-%m-01 --date='next month')
belob="$2"
echo -n "$belob +" >> ~/tmp/regnestykke_budget
konto="$3"
modkonto="$4"
momsbelob="$5"
dag="$6"
if [ "$dag" == "" ]; then
	dag=01
fi

scriptnavn=`basename "$0"`
start=$(date +%s --date=$startdato)
thismonth=$(date +%s --date=$(date +%Y-%m-01 --date='+24 months'))
while [ "$start" -lt "$thismonth" ]; do
	sm=$(date +%Y-%m-%d -ud @$start)
	start=$(date +%s --date="$sm + 1 month")
	dato=$(date +%Y-%m-$dag -ud @$start)
	echo -e "$dato ($scriptnavn) $tekst\n\t$konto  $belob\n\t$modkonto\n\n"
	if [ "$momsbelob" != "" ]; then
		echo -e "$dato ($scriptnavn) $tekst ($momskonto)\n\tPassiver:Moms:$momskonto  $momsbelob\n\t$modkonto\n\n"
	fi
done
