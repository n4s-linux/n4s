#!/bin/bash
cd ~/tmp/vitouch/
y=1
ls -t|head -n5|while read i
do
	date="$(echo $i|cut -d_ -f1)"
	tag="$(echo $i|cut -d_ -f2)"
	tp="$(echo $i|cut -d_ -f3)"
	echo "$y $tp/$tag "
	  ((y=y+1))


done|fzf
