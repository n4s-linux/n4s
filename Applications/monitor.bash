y=0
freq=500 ;inotifywait -m -r /data/ |while read i
	do 
			date=$(date)
			echo "$date $i" > /dev/stderr
			let "freq=freq+25"
			if [ "$freq" -gt 2000  ]; then 
				freq=500; 
			fi
	done
