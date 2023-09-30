tags=$(echo 'mappe
bilag
debitor
kreditor
beløb
beløbvaluta
valuta
dokumenttype
kommentar
moms
konto
betaling'|fzf --multi)
while true
do
	tmpfn=$tpath/.tmpentry
	echo "{" > "$tmpfn"
	echo "$tags"|while read curtag
	do
		if [ -f "$tpath/.lastval_$curtag" ]; then
			lastval=$(cat $tpath/.lastval_$curtag)
		else
			lastval=""
		fi
		echo -n "#$curtag: ($lastval)"
		read input </dev/tty
		if [ "$lastval" != "" ]  && [ "$input" == "" ]; then
			echo
		else
			lastval="$input"
		fi
		echo "$lastval" > $tpath/.lastval_$curtag
		if [ "$lastval" != "" ]; then
			echo -e "\"$curtag\":\"$lastval\"," >> "$tmpfn"
		fi
	done
		echo >> "$tmpfn"
		#sed -zri 's/,([^,]*$)/\1/' "$tmpfn"
		now=$(date)
		whoami=$(whoami)
		echo "\"History\": [ { \"date\": \"$now\",\"desc\":\"created from bilag.bash\",\"updatedby\":\"$whoami\" } ]" >> "$tmpfn"
		echo "}" >> "$tmpfn"
		cat "$tmpfn"|jq
done
