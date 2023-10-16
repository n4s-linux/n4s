function difffile() {
	php /svn/svnroot/Applications/md2logformat.php "$1" > "$1".mdl
	php /svn/svnroot/Applications/md2logformat.php "$2" > "$2".mdl
        dato=$(echo -n $(date +'%Y-%m-%d %H:%M: '))
	op="$(whoami)"
	diff --unified=0 "$1".mdl "$2".mdl|while read i
	do
			echo -e "\t$dato joo\t$i"
	done
	rm "$1".mdl "$2".mdl
}

