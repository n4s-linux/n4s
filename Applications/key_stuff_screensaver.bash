TXT='the quick brown fox jumped over the lazy dog.'
WORDS=( $TXT )
for WORD in "${WORDS[@]}"; do
	    let "i=$RANDOM % 256"
	        echo -en "\e[38;5;${i}m$WORD \e[0m";
	done;
	echo

while true; do (
	for tpath in ~/regnskaber/transactions_stuff ~/regnskaber/transactions_crm; do
	i=$(shuf -i 1-50 -n 1)
	ls -pt $tpath/.tags|head -n$i #get 50 most popular
	i=$(shuf -i 1-50 -n 1)
	ls -pt $tpath/.tags|head -n$i #get 50 most popular
	i=$(shuf -i 1-50 -n 1)

	ls -pt $tpath/.tags|tail -n$i #get 15 least popular - maybe we should just get rid of them completely
	ls -pt $tpath/.tags|shuf|head -n$i # get 5 random
	done
	)|grep -v .diff|shuf|while read y; 
	do 
		col=$(shuf -i 1-256 -n 1)
		word="$y"
		echo -en "\e[38;5;${col}m$word \e[0m";
		sleep 0.5
	done;
done
