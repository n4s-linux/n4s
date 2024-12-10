export color=none
if [ "$retning" == "" ]; then
	retning=$(echo -e "lodret\nvandret\n"|fzf)
fi
if [ "$retning" == "lodret" ]; then
	cmd="balance -T"
else
	cmd="register"
fi
if [ "$depth" == "" ]; then
	depth=$(echo -e "2\n3\n4\n1\n"|fzf)
fi

if [ "$period" == "" ]; then
	period=$(echo -e "monthly\nquarterly\nyearly\nweekly\ndaily"|fzf)
fi
#echo -n "Indtast kontostreng: "
#read account
account="$@"
if [ "$period" == "monthly" ]; then
	php /svn/svnroot/Applications/newl.php print |hledger --depth=$depth -f /dev/stdin $cmd $@ --monthly  --sort-amount 
elif [ "$period" == "quarterly" ]; then
php /svn/svnroot/Applications/newl.php print |hledger --depth=$depth -f /dev/stdin $cmd $@ --quarterly  --sort-amount 
	elif [ "$period" == "yearly" ]; then
php /svn/svnroot/Applications/newl.php print |hledger  --depth=$depth -f /dev/stdin $cmd $@ --yearly  --sort-amount 
	elif [ "$period" == "weekly" ]; then
php /svn/svnroot/Applications/newl.php print |hledger --depth=$depth -f /dev/stdin $cmd $@ --weekly  --sort-amount 
	elif [ "$period" == "daily" ]; then
php /svn/svnroot/Applications/newl.php print |hledger  --depth=$depth -f /dev/stdin $cmd $@ --daily --sort-amount 
fi
