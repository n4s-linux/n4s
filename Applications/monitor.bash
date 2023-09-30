y=0
freq=500 ;inotifywait -m -r / |while read i
	do 
			if  (( $freq % 100 == 0)) 
			then
				beep -f $freq -l 25 >/dev/null
#				bash /svn/svnroot/Applications/music.bash 8888 $freq 0.75&

			fi
			date=$(date)
			echo "$date $i" > /dev/stderr
			let "freq=freq+25"
			if [ "$freq" -gt 2000  ]; then 
				freq=500; 
			fi
	done
