#!/bin/bash
cd ~
ls|grep rt|
while read i
do 
	IFS="rt"
	filnavn=$i
	set -- $i
	sagsnr=$3
	unset IFS
	subject=$(echo -n $(grep -i Subject ~/$filnavn))
	echo "subjekt: $subject"
	rt comment -m "Gmail: $subject" -a ~/$filnavn -w 2 $sagsnr
	mkdir ~/.rtarchive 2>/dev/null
	mv ~/$filnavn ~/.rtarchive/
done
