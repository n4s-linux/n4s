#!/bin/bash
fn=/data/regnskaber/transactions_crm/.tags/$(whoami)
cp /data/regnskaber/transactions_crm/.tags/$(whoami) ~/review.md
first=first
while true
	do
		cat ~/review.md|fzf --border=rounded --multi --nth 1 --cycle --tac --header="Kommenterer - vælg flere linier med TAB, klar på ENTER" > ~/customercomments
		if [ $? == 130 ]; then
			exit
		fi
		echo -e "\n# Interaktion modtaget fra $(whoami) $(date +"%Y-%m-%d")" >> "$fn"
		cat ~/customercomments|while read line; do
			echo -n "$line : "
			read kommentar < /dev/tty
			if [ "$kommentar" != "" ]; then
				if [ "$first" == "first" ]; then
					echo -e "\n# Interaktion modtaget fra $(whoami) $(date +"%Y-%m-%d")" >> "$fn"
					first=next
				fi
				echo -e "\t$kommentar" >> "$fn";
			fi	
		done
		
	done

nano --mouse ~/review.md
diff -u /data/regnskaber/transactions_crm/.tags/$(whoami) ~/review.md > ~/diff
if [ $? -eq 1 ]; then
	echo -e "\n# Kommentarer modtaget fra $(whoami) $(date +"%Y-%m-%d")" >> $fn
	cat ~/diff|while read i
	do
		echo -e "\t$i" >> $fn
	done
fi
