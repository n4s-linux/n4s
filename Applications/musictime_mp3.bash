MAX=3000
hour=$(date +%H)
echo hour=$hour
echo max=$MAX
freq=$(echo "$hour/24*$MAX"|bc -l)
seq 0.1 0.2 3|while read i
do
	i=$(echo "$i"|sed 's/,/./g')
	echo bash music.bash 8888 $freq $i
	bash music.bash 8888 $freq $i

done

