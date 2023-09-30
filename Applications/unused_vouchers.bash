#!/bin/bash
#ls "$tpath/vouchers"|while read i; do echo -n $(grep "$i" "$tpath/"*.trans|wc -l);echo -n "-_-";echo $i; done
#exit
if [ "$1" == "preview" ]; then
	#cat /tmp/selection_cur
	php /svn/svnroot/Applications/unused_vouchers.php selected # > /tmp/selection.html
	#cat /tmp/selection_cur
	#cat /tmp/selection.html
	#cat /tmp/selection.html|sed 's/\n//g'
	#/tmp/curfn
	cp "$tpath/vouchers/$(echo -n $(cat /tmp/curfn))" /svn/svnroot/tmp/cur.pdf
	cat /tmp/.current_voucher
	exit
fi






#BEGIN FIND UNUSED
ls "$tpath/vouchers"|grep -v ignored|while read i; do
	 num=$(echo -n $(grep "$i" "$tpath/"*.trans|wc -l));
	if [ "$num" == 0 ]; then
		echo -n $num;echo -n "-_-";echo $i; 
	fi
done|sort > /tmp/.unused_vouchers
#END FIND UNUSED


php /svn/svnroot/Applications/unused_vouchers.php > /tmp/unused.html #OUTPUT UNUSED TABLE
#BEGIN JUST OUTPUT THE DATA AS TEXT OPTION
if [ "$1" == "show" ]; then
	cat /tmp/.unused_vouchers
#	rm /tmp/.unused_vouchers
	exit
fi
#END JUST OUTPUT THE DATA AS TEXT OPTION


#php /svn/svnroot/Applications/unused_vouchers.php > /tmp/unused.html #OUTPUT UNUSED TABLE
w3m -dump /tmp/unused.html|fzf --no-sort --tac --preview-window=down:5 --preview="echo '{}' > /tmp/selection_cur;/svn/svnroot/Applications/unused_vouchers.bash preview"

rm -rf /tmp/unused_ids.txt
rm -rf /tmp/selection.html
rm /tmp/unused.html -rf
rm /tmp/.unused_vouchers -rf
rm -rf /tmp/curfn
