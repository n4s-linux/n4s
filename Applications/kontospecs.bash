function level3() {
	echo "##""$1"
	(
	hledger -f $tpath/curl reg "$2":"$1" --depth=2
	) |while read line
	do
		echo -e "\t$line"
	done
	echo
}
function level2() {
	echo "#""$1"
	hledger -f $tpath/curl accounts --depth=2 $1:|while read lvl2
	do
		level3 "$(echo "$lvl2"|sed "s/$1://g")" "$1"
	done
}
(
hledger -f $tpath/curl accounts --depth=1|while read level1
do 
	level2 "$level1"
done
)> ~/tmp/kontokort.md

