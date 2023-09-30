source ~/tmp/.rtcred
date=$(date +%Y-%m-%d)
rt ls "LastUpdatedBy = 'joo' and Resolved = '$date'" -f Subject|sed 's/\\n/<br>/g'|while read line
do
echo "$line<br>"
done
