then="$(date +%Y-%m-%d" "%H:%M --date="+25 minutes")"
while true
do
now=$(date +%Y-%m-%d" "%H:%M)
(
	echo "pomodoro time left"
	secs=$(dateutils.ddiff "$now" "$then"|sed 's/s//g')
	echo "$secs / 60"|bc 
	echo "also time is now"
	echo "lets add another greeting here to brighten the day, something really wise"
	echo "oh how about this, here is your STY" $STY
	date +%H:%M
)|cowsay -W80
sleep 60
done
exit
$(date +%Y-%m-%d" "%H:%M)
