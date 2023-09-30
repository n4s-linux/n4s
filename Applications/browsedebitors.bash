let "y=0"
uid=$(uuidgen)
mkdir -p $tpath/.debtorrecon/
uid=$tpath/.debtorrecon/browsedebtors_"$uid".dat
echo -n "Indtast kontoområde for afstemningsark på kontobasis: "
read account
echo "Afstemning af $account foretaget af $(date) $(whoami)" > $uid
count=$(nopopup=1 php /svn/svnroot/Applications/key.php ledger --no-pager accounts "$account"|wc -l)
if [ "$count" == "0" ]; then
	echo ingen rækker
	exit
fi
nopopup=1 php /svn/svnroot/Applications/key.php ledger --no-pager accounts "$account"|while read i
do 
	let "y=y+1"
	echo "# $i " >> "$uid"
	ledger -f $tpath/curl r "$i"$ >> "$uid"
	echo -e "$y / $count\t$i"
	echo  >> "$uid"
done
tmux new-window -n Debitorafstemning "vi \"$uid\""
