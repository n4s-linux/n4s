function difffile() {
        bn1=$(basename "$1")
        bn2=$(basename "$2")
        dato=$(echo -n $(date +'%Y-%m-%d %H:%M: '))
	op="$(whoami)"
        #diff -c1 "$1" "$2"|grep -vE '^---$'|grep -vE '^---$'|while read i 
        #diff -u "$1" "$2"|grep -vE '^---$'|grep -vE '^---$'|while read i 
	diff --changed-group-format='%>' --unchanged-group-format='' "$1" "$2"|while read i
        do
		echo -e "\t$dato\t+$i ($op)"|sed 's/⌫/⌦⌦⌦/g'|sed 's/ℹ/ℹℹℹ/g'
        done
	diff --changed-group-format='%<' --unchanged-group-format='' "$1" "$2"|while read i
        do
		echo -e "\t$dato\t⁒$i ($op)"|sed 's/⌫/⌦⌦⌦/g'|sed 's/ℹ/ℹℹℹ/g'
        done
}

