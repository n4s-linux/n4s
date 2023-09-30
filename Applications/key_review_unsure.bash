rm ~/tmp/.review
grep 😬 .. -R|while read i; do echo "$i"|awk '{print $1}'; done|sed 's/://g'|uniq|while read file
do
	echo ":e $file" >> ~/tmp/.review
done
vi -s ~/tmp/.review
