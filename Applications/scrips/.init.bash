SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
export LEDGER_ADDTIME_TO_PAYEE=1
function getemoji() {
	fn="$1".diff
	date="$2"
	grep -P -o '\p{So}' "$fn"|grep "$date"|uniq|while read i; do echo -n "$i";done
	# grep -oP "[^\x00-\x7F]" "$fn"|grep "$date"|while read i; do echo -n "$i ";done
}
function gettime() {
 grep "$2" "$1".diff|grep -Eo "#[0-9]{1,4}min"|sed 's/#//g'|sed 's/min/+/g'|while read i; do echo -n "$i";done || echo "NA"
}
if [ "$tpath" == "" ]; then
	tpath="$SCRIPT_DIR"
fi
mkdir $tpath/.tags/
export LEDGER_BEGIN=$(date -d "today" +"%Y-%m-%d");
export LEDGER_END=$(date -d "tomorrow" +"%Y-%m-%d");

function idag() {
	grep $(date +%Y-%m-%d) .tags/*|awk '{print $1}'|sed 's/:.*//'|sed 's/.tags\///g'|sed 's/\t//g'|sed 's/://g'|sort|sed "s/\.diff//g"|uniq
}
function today() {
	date=$(date +%Y-%m-%d)
	( if [ "$1" != "yesterday" ] ; then
		idag
	else
		igår
		date=$(date +%Y-%m-%d --date="yesterday")
	fi)|(while read tag
	do
		#mentions=$(grep "$tag" .tags/*|grep "$date"|wc -l	)
		added=$(grep "$date" .tags/"$tag".diff|grep ">"|wc -l)
		deleted=$(grep "$date" .tags/"$tag".diff|grep "<"|wc -l)
		diff=$(echo "added-$deleted"|bc 2>/dev/null || echo "NA" )
		expect=$(grep "#expect" .tags/"$tag"|head -n1|sed 's/#expect//g'|sed 's/://g'|sed 's/\ //g')
		emoji=$(getemoji .tags/"$tag" $date)
		time=$(gettime .tags/"$tag" $date)
		echo -e "$tag\t$added\t$deleted\t$expect\t$diff\t$time\t$emoji"

	done|sort)|column -t|fzf --multi|while read line
	do
		added=$(echo "$line"|awk '{print $2}')
		deleted=$(echo "$line"|awk '{print $3}')
		tag=$(echo "$line"|awk '{print $1}')
		name="$tag"
		tmux new-window -n "$name" "tpath=$tpath bash $tpath/.menu.bash tags $name"


		
	done
}
function igår() {
	grep $(date +%Y-%m-%d --date=yesterday) .tags/*|awk '{print $1}'|sed 's/:.*//'|sed 's/.tags\///g'|sed 's/\t//g'|sed 's/://g'|sort|sed "s/\.diff//g"|uniq

}
function miss() {
	igår > ~/tmp/igår
	idag > ~/tmp/idag
	echo "Manglende tags idag i forhold til igår"
	(comm -23 ~/tmp/igår ~/tmp/idag|while read i 
	do
		echo -n "$i "|sed 's/\.diff//g'
	done
	echo)|uniq
}
miss
function tag(){
	STY=$(ls $tpath/.tags|fzf)
	bn=${STY:0:5}
	tmux rename-session "$bn"
} 
alias t="m tags"
function v() {
	pushd . >/dev/null
	cd $tpath
	date="$(echo -n $(date +"%Y-%m-%d"))"
	valg="$((find $tpath/ -printf "%TY-%Tm-%Td %TH:%TM\t%p\n";tail /var/log/olsen)|grep -v config.bash|grep "$date"|grep -v chart_of_account|sort -r|grep -v "budget.ledger"|grep -v "ledger_accounts"|grep -v ".lr"|grep -v ".git"|grep -v "budget.ledger"|grep -v "/curl"|grep -v ledger_accounts.txt|grep -v .watch|bash $tpath/.color_dosha.bash|fzf --ansi)"
	if [ ! "$?" -eq 130 ]; then 
		valg="$(echo -n $valg|awk '{print $NF}')"
		echo "valg=$valg"
		vi "$valg"
	else
		echo "intet valgt"
	fi
	popd > /dev/null
}

alias w="$tpath/.watch.bash|fzf --ansi --tac"
echo remember to add @@@@ to any line in tags to make it executed !
function m() {
	pushd . >/dev/null
	cd "$tpath"
	$tpath/.menu.bash $@
	popd >/dev/null
}
function mtp() {
	pushd . >/dev/null
	cd "$tpath"
	name=$((echo NY;ls -t $tpath/.tags)|grep -v ".diff"|uniq|fzf)
	if [ "$name" == "NY" ]; then
		echo -n "Indtast navn: "
		read name	
	fi	
	tmux new-window -n $name "tpath=$tpath bash $tpath/.menu.bash tags $name"
	popd >/dev/null
	stty sane
}
function mt() {
	pushd . >/dev/null
	cd "$tpath"
	echo "running custom menu if any..."
	$tpath/.menu.bash tags $@
	git add .
	git commit . -m "mt $@"
	popd >/dev/null
	stty sane
}
