cd ~/regnskaber
echo -n Indtast dato: 
read date < /dev/tty
if [ "$date" == "" ]; then
	date=$(date +%Y-%m-%d)
fi
grep "$date" */.tags/*|grep -v .diff|sort -k2 -t':'|sed 's/^ *//;s/ *$//'|grep -v ":#"|grep -v :'[[:space:]]\+'#|column -ts $'\t'|sed 's/^[ \t]*//;s/[ \t]*$//'|vim -
