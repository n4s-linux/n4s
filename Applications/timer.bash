dato=$(date)
mkdir -p /data/otimers/$(whoami)
pushd . 2&>/dev/null
cd /data/otimers/$(whoami)
valg="$2"
if [ "$1" == "vis" ] ; then
	if [ "$valg" == "" ]; then
		valg="$((ls|while read i; do
			mt=$(stat "$i" -c %Y)
			now=$(date +%s)
			diff=$(echo "($now-$mt)/60"|bc)
			echo -e "$diff""🕑\t$i"
		done;echo NY)|column -ts $'\t'|fzf --tac --header="Vælg en timer eller NY for at oprette en ny" |sed -e 's/^[ \t]*//;s/[ \t]*$//'
		)"
	fi
		if [ "$valg" == "NY" ]; then
			min=0
			if [ "timernavn" == "" ]; then
				echo -n "Indtast navn på timer: "
				read timer
			else
				timer="$timernavn"
			fi
			if [ "$timer" == "" ]; then
				timer="NA"
			fi
			if [ ! -f "$timer" ]; then
				touch "$timer"
				echo "$dato Timer $fn stoppet" >> /data/otimers/$(whoami)/.log
				(
				date1=`date +%s`; while true; do 
				# tmux rename-window "🕑$min $timer" this renames current active window - big fail
				min=$(echo "$min+0.5"|bc -l)
				clear
				fortune |cowsay
				sleep 30
				echo -ne "$(date -u --date @$((`date +%s` - $date1)) +%H:%M:%S)\r"; done)&(echo tryk ENTER;read;exit)
			else
				echo "$timer findes allerede - tryk ENTER"
				read
			fi
		elif [ "$valg" == "" ]; then
			exit
		else
			fn=$(echo "$valg"|cut -d' ' -f2- )
			echo -n Indtast evt. kommentar: 
			read kommentar
			echo "$dato Timer stoppet: $valg"". $kommentar" >> /data/otimers/$(whoami)/.log
			fn="$(echo "$fn"|sed -e 's/^[ \t]*//;s/[ \t]*$//')"
			rm "$fn"
		fi
	
fi
popd 2&>/dev/null
