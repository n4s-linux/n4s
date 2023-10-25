cd ~/regnskaber/stuff/.tags/
if [ "$select" == "" ]; then
valg=$(ls |grep -v .diff|grep -v .scrabble|fzf)
else
valg=thenow
fi

if [ "$valg" == "" ]; then
	valg=thenow
fi

if [ "$1" == "hide" ]; then
	txt=$(whiptail --passwordbox "Hvad siger du ?" 8 80 3>&1 1>&2 2>&3)
	txt=$(echo "$txt🔒")
else
	txt=$(whiptail --inputbox "Hvad siger du ?" 8 80 3>&1 1>&2 2>&3)
fi

date=$(date +%Y-%m-%dT%H:%M)
if [ "$txt" != "" ]; then
	echo -e "\t$date $(whoami)\t$txt" >> ~/regnskaber/stuff/.tags/"$valg".scrabble
fi


# Historik
	# 2023-07-05T12:56 joo	tilføjet scrabble filer til omit liste
