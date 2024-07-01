if [ "$1" == "" ] ; then 
	echo usage mkd [file] [command]
	echo require markdown file
	exit
elif [ "$2" == "" ]; then 
	echo usage mkd [file] [command]
	echo "require argument (sections, ...)"
	exit
fi

if [ "$2" == "sections" ]; then
grep "^# " "$1"|sed s/^#\ //

elif [ "$2" == "select" ]; then
grep "^# " "$1"|sed s/^#\ //|fzf --multi --header="chose markdown headings to print - PRESS SPACE TO SELECT ALL" --query="!Fakturering !Færdig !Hvidvask !Mapper !Samkøring !Stamdata !Logins" --bind space:select-all|while read heading
do
	php /svn/svnroot/Applications/getmdheading.php "$1" "$heading" 
done
elif [ "$2" == "getsection" ]; then
	php /svn/svnroot/Applications/getmdheading.php "$1" "$3"
fi
