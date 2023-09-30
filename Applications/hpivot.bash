if [ "$retning" == "" ]; then
	retning=$(echo -e "lodret\nvandret\n"|fzf)
fi
if [ "$retning" == "lodret" ]; then
	cmd="balance -T"
else
	cmd="register"
fi
depth=$(echo -e "2\n3\n4\n1\n"|fzf)

if [ "$period" == "" ]; then
	period=$(echo -e "monthly\nquarterly\nyearly\nweekly\ndaily"|fzf)
fi
#echo -n "Indtast kontostreng: "
#read account
account="$@"
if [ "$period" == "monthly" ]; then
	php /svn/svnroot/Applications/key.php ledger print |hledger --depth=$depth -f /dev/stdin $cmd ^"$account" --monthly  
elif [ "$period" == "quarterly" ]; then
php /svn/svnroot/Applications/key.php ledger print |hledger --depth=$depth -f /dev/stdin $cmd ^"$account" --quarterly  
	elif [ "$period" == "yearly" ]; then
php /svn/svnroot/Applications/key.php ledger print |hledger  --depth=$depth -f /dev/stdin $cmd ^"$account" --yearly  

	elif [ "$period" == "weekly" ]; then
php /svn/svnroot/Applications/key.php ledger print |hledger --depth=$depth -f /dev/stdin $cmd ^"$account" --weekly 
	elif [ "$period" == "daily" ]; then
php /svn/svnroot/Applications/key.php ledger print |hledger  --depth=$depth -f /dev/stdin $cmd ^"$account" --daily 
fi
