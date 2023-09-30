source ~/rtconfig
i=0
rt ls -o +Subject -i '(Requestor like 'olsenit@gmail.com' or requestor like 'joo@0lsen.com') and status='new''|while read i; do 
	rt show "$i" > ~/tmp/rtout 2> /dev/null
	id=$(echo "$i"|cut -d "/" -f2|xargs)
	if [ "$id" == "" ]; then
	continue
	fi
	date=$(grep Date ~/tmp/rtout|cut -c 7-|xargs)
	sub=$(grep Subject ~/tmp/rtout|cut -d "]" -f2|xargs)
	date=$(date --date="$date" +%Y-%m-%dT%H:%M)
	echo -e "$date joo\t$sub (#rt $id)"
	rt resolve "$id" >/dev/null 2>/dev/null
done
