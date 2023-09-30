#!/bin/bash
if [ ! -f "/tmp/filter_$(whoami).txt" ]; then
echo -e "### Insert query on next line, comments with '###' in line\ntrue" > /tmp/filter_$(whoami).txt
fi
vi "/tmp/filter_$(whoami).txt"
filter=$(cat "/tmp/filter_$(whoami).txt"|grep -v "###")
codepath=/tmp/curupdate_$(whoami).txt
if [ "$1" = "update" ]; then #update, edit update command
vi "$codepath"
	(LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger -E -l "$filter" r --register-format="%(date)|%(payee)|%(account)|%(amount)|%(tag('Filename'))|%(tag('TransID'))|%(total)\n")|
while read line
		do
			fn=$(echo "$line"|awk 'BEGIN { FS = "|" } ; { print $5 }')
			id=$(echo "$line"|awk 'BEGIN { FS = "|" } ; { print $6 }')
			re='^[0-9]+$'
			if ! [[ $id =~ $re ]] ; then
			   echo "error: Not a number: $fn/$id" >&2;
				continue;
			fi
			code=$(cat $codepath|sed "s/CURID/$id/g")
			new="$(cat "$tpath/$fn")"
			echo "$code"|while read codeline 
			do
				old="$new"
		                new=$(echo "$old" |jq "$codeline")
				echo "$new" > /tmp/diffgen.txt

			done
			if [ "$2" = "apply" ]; then
				echo "Applied to $fn"
				cp /tmp/diffgen.txt "$tpath/$fn"
			else
				diff "$tpath/$fn" /tmp/diffgen.txt -bu
			fi
		done|less
else
#not updating , just showing results
#(LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger -E -l "$filter" r --register-format="%(date)|%(payee)|%(account)|%(amount)|%(tag('Filename'))|%(tag('TransID'))|%(total)\n")|column -t -n -s"|"|vi -
(LEDGER_DEPTH=999 php /svn/svnroot/Applications/key.php ledger -E -l "$filter" r --register-format="%(date)\t%(payee)\t%(account)\t%(amount)\t%(tag('Filename'))\t%(tag('TransID'))\t%(total)\n")|vi -

fi
