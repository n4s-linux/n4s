function difffile() {
	php /svn/svnroot/Applications/md2logformat.php "$1" > "$1".mdl
	php /svn/svnroot/Applications/md2logformat.php "$2" > "$2".mdl
        dato=$(echo -n $(date +'%Y-%m-%d %H:%M: '))
	op="$(whoami)"
	diff --changed-group-format='%>' --unchanged-group-format='' "$1".mdl "$2".mdl|while read i
        do
		echo -e "\t$dato\t+$i ($op)"|sed 's/⌫/⌦⌦⌦/g'|sed 's/ℹ/ℹℹℹ/g'
        done
	diff --changed-group-format='%<' --unchanged-group-format='' "$1".mdl "$2".mdl|while read i
        do
		echo -e "\t$dato\t⁒$i ($op)"|sed 's/⌫/⌦⌦⌦/g'|sed 's/ℹ/ℹℹℹ/g'
        done
	rm "$1".mdl "$2".mdl
}

