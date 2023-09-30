created=$(date +%s --date=$(rt ls $1 -f Created|tail -n1|awk '{print $2}'))
now=$(date +%s)
diffdays=$(echo "($now-$created)/86400"|bc)
echo $diffdays
diffdays=" (svartid $diffdays dage) "
dato=$(date +%Y-%m-%dT%H:%M)
id=$((
echo -e "Slut\nRediger\nResolve\nReject"
rt show $1 2>/dev/null
rt show $1/history 2>/dev/null
rt show $1/attachments 2>/dev/null
rt ls $1 -f created,subject,id,priority,queue,requestor $1 2>/dev/null|column -ts $'\t'
rt ls "Requestor like $(rt ls $1 -f Requestors|tail -n1|awk '{print $2}') and (status='stalled' or status='new' or status='open')" -f id,Subject|sed 1d|while read mail; do echo "#åbenmail rt#$mail"; done
)|fzf --ansi --tac|awk '{print $1}'|sed 's/://g')

dato="$dato $(whoami)"

valg="$id"
id="$1"
id=$(rt ls $id -f subject,Created,Requestors|tail -n1)
if [ "$valg" == "Slut" ]; then
	exit
elif [ "$valg" == "Rediger" ]; then
	rt edit $1
	echo -e "$dato\tEdit rt#$id $diffdays" >> ~/regnskaber/stuff/.tags/rtedit.auto
elif [ "$valg" == "Resolve" ]; then
	rt resolve $1
	dato=$(date +%Y-%m-%d)
	echo -e "$dato\tResolved rt#$id $diffdays" >> ~/regnskaber/stuff/.tags/rtresolved.auto
elif [ "$valg" == "Reject" ]; then
	rt edit $1 set status=rejected
	echo -e "$dato\tRejected rt#$id $diffdays" >> ~/regnskaber/stuff/.tags/rtrejected.auto
elif [ "$valg" == "Kommentar" ]; then
	echo -n "Indtast kommentar: "; read comment
	rt comment $1 -m "$comment"
	echo -e "$dato\tKommentar rt#$id $diffdays" >> ~/regnskaber/stuff/.tags/rtcomments.auto
elif [ "$valg" == "Svar" ]; then
	rt correspond $1
	echo -e "$dato\tSvaret rt#$id $diffdays" >> ~/regnskaber/stuff/.tags/rtcomments.auto
fi


