#!/bin/bash
ls */curl|while read i; 
do 
	date=$(LEDGER_DEPTH=5 LEDGER_BEGIN=1970/1/1 LEDGER_END=2099/12/31 ledger -f "$i" r Bank --date-format="%Y-%m-%d"|tail -n1|echo $(awk '{print $1}'))
	if [ "$date" == "" ]; then
		date="1970-01-01"
	fi
	echo "$date $i"
done|sort --reverse
