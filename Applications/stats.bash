source /svn/svnroot/aliases
if [ "$1" == "" ]; then
while true
do
	clear
	(
	ledger -f ~/regnskaber/igangv/curl print Indtægter: Udgifter:  --depth=3 --begin=$(date +%Y-%m-%d --date="6 months ago") --end=$(date +%Y-%m-%d --date=tomorrow) --no-pager
	)> ~/tmp/stats.ledger
	hledger -f ~/tmp/stats.ledger balance indtægter: udgifter: -M --begin=$(date +%Y-%m-%d --date="6 months ago") --end=$(date +%Y-%m-%d --date=tomorrow) --row-total
	exit
done
elif [ "$1" == "uge" ]; then
while true
do
	clear
	(
	ledger -f ~/regnskaber/igangv/curl print Indtægter: Udgifter:  --depth=3 --begin=$(date +%Y-%m-%d --date="1 months ago") --end=$(date +%Y-%m-%d --date=tomorrow) --no-pager
	)> ~/tmp/stats.ledger
	hledger -f ~/tmp/stats.ledger balance indtægter: udgifter: -W --begin=$(date +%Y-%m-%d --date="1 months ago") --end=$(date +%Y-%m-%d --date=tomorrow) --row-total
	exit
done
fi
