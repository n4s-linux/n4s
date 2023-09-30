date=$1
dayafter=$(date --date="$date +1 day" +%Y-%m-%d)
echo searching $date - $dayafter
cd /home/joo/Dropbox
find . -mount -newermt $date -not -newermt $dayafter -type f|sed 's/^.\///g'

