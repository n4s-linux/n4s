match=Time:$(cat ~/tmp/.cw)
(


echo -e "\e[1;33m$match\e[0m"

echo -e "\e[1;33mRecent daily on $match\e[0m"
echo
LEDGER_END=2099-12-31 tpath=~/regnskaber/vimtime/ LEDGER_BEGIN=$(date +%Y-%m-%d --date='-4 days') php /svn/svnroot/Applications/newl.php r ^$match -D

echo -e "\e[1;33mDaily\e[0m"
LEDGER_END=2099-12-31 tpath=~/regnskaber/vimtime/ LEDGER_BEGIN=$(date +%Y-%m-%d --date='-1 weeks') period=daily depth=5 retning=lodret bash /svn/svnroot/Applications/hpivot.bash Time | while IFS= read -r i; do
    if [[ $i == *$match* ]]; then
        # Highlight matching lines in bold yellow
        echo -e "\e[1;33m$i\e[0m"
    else
        echo "$i"
    fi
done
echo

echo -e "\e[1;33mWeekly\e[0m"
LEDGER_END=2099-12-31 tpath=~/regnskaber/vimtime/ LEDGER_BEGIN=$(date +%Y-%m-%d --date='-2 months') period=weekly depth=5 retning=lodret bash /svn/svnroot/Applications/hpivot.bash Time | while IFS= read -r i; do
    if [[ $i == *$match* ]]; then
        # Highlight matching lines in bold yellow
        echo -e "\e[1;33m$i\e[0m"
    else
        echo "$i"
    fi
done

)|less -R

